<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\CutiStatusUpdated;
use Illuminate\Support\Facades\Mail;
use App\Models\Message as InAppMessage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Mail\GenericNotification;
use Illuminate\Support\Facades\URL;

class CutiApprovalController extends Controller
{
    public function __construct()
    {
        // Middleware is_hrd sudah diterapkan di route group
    }
    public function index()
    {
        // Statistik
        $pendingCount = Cuti::where('status', 'pending')
            ->whereNull('processed_at')
            ->count();
        $approvedCount = Cuti::where('status', 'approved')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->count();
        $rejectedCount = Cuti::where('status', 'rejected')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->count();

        // Data cuti dengan filter
        $query = Cuti::with('user')
                    ->where('status', '!=', 'draft')  // Jangan tampilkan yang masih draft
                    ->orderBy('created_at', 'desc');  // Urutkan dari yang terbaru

        // Filter berdasarkan status jika ada
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Filter berdasarkan pencarian jika ada
        if (request()->filled('search')) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $cutiList = $query->latest()->paginate(10);

        // Hitung sisa cuti per user untuk baris yang sedang ditampilkan
        $year = now()->year;
        $userIds = $cutiList->pluck('user_id')->unique()->values()->all();
        $remainingByUser = [];
        foreach ($userIds as $uid) {
            $user = User::find($uid);
            $quota = $user->cuti_quota ?? 12;
            $usedDays = Cuti::where('user_id', $uid)
                ->where('status', 'approved')
                ->whereYear('processed_at', $year)
                ->get()
                ->map(function($c) { return $c->duration ?? $c->getDurationAttribute(); })
                ->sum();
            $remainingByUser[$uid] = max(0, $quota - $usedDays);
        }

        if (request()->ajax()) {
            return response()->json([
                'table_html' => view('hrd.cuti._cuti_table', compact('cutiList', 'remainingByUser'))->render(),
                'counts' => [
                    'pending' => $pendingCount,
                    'approved' => $approvedCount,
                    'rejected' => $rejectedCount,
                ]
            ]);
        }

    return view('hrd.cuti.index', compact('cutiList', 'pendingCount', 'approvedCount', 'rejectedCount', 'remainingByUser'));
    }

    public function show(Cuti $cuti)
    {
        // Hitung sisa cuti user untuk ditampilkan di halaman detail
        $year = now()->year;
        $defaultQuota = $cuti->user->cuti_quota ?? 12;
        $usedDays = Cuti::where('user_id', $cuti->user_id)
            ->where('status', 'approved')
            ->whereYear('processed_at', $year)
            ->get()
            ->map(function($c) { return $c->duration ?? $c->getDurationAttribute(); })
            ->sum();
        $remaining = max(0, $defaultQuota - $usedDays);

        return view('hrd.cuti.show', compact('cuti', 'remaining'));
    }

    public function update(Request $request, Cuti $cuti)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => 'required_if:status,rejected|nullable|string|min:10',
            'pengganti_id' => 'nullable|exists:users,id',
        ]);

        // If trying to approve but no pengganti is set, require explicit confirmation
    if ($validated['status'] === 'approved' && empty($cuti->pengganti_id) && ! $request->filled('no_pengganti_confirm') && empty($validated['pengganti_id'])) {
            // Ask the user to confirm they want to approve without pengganti
            return redirect()->back()
                ->withInput()
                ->with('confirm_no_pengganti', true)
                ->with('warning', 'Belum memilih pengganti. Konfirmasi diperlukan jika Anda yakin tidak perlu pengganti.');
        }

        $cuti->status = $validated['status'];
        $cuti->catatan = $validated['catatan'] ?? null;
        $cuti->processed_by = Auth::id();
        $cuti->processed_at = now();
        // If HRD selected a pengganti during approval, persist it and notify
        if (! empty($validated['pengganti_id'])) {
            $pengganti = User::find($validated['pengganti_id']);
            if ($pengganti) {
                $cuti->pengganti_id = $pengganti->id;
                $cuti->pengganti_status = 'pending';
                // create in-app message and email like assignPengganti
                try {
                    InAppMessage::create([
                        'user_id' => $pengganti->id,
                        'cuti_id' => $cuti->id,
                        'title' => 'Permintaan Menjadi Pengganti Cuti',
                        'body' => "Anda diminta menjadi pengganti untuk cuti oleh {$cuti->user->name} pada tanggal {$cuti->tanggal_mulai->format('d M Y')} sampai {$cuti->tanggal_selesai->format('d M Y')}. Silakan konfirmasi.",
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('Gagal membuat in-app message untuk pengganti (via update): ' . $e->getMessage(), ['cuti_id' => $cuti->id, 'pengganti_id' => $pengganti->id]);
                }
                try {
                    $actionUrl = URL::signedRoute('cuti.pengganti.confirm', ['cuti' => $cuti->id]);
                    $acceptUrl = URL::temporarySignedRoute('cuti.pengganti.respond.oneclick', now()->addDays(7), ['cuti' => $cuti->id, 'action' => 'accept']);
                    $declineUrl = URL::temporarySignedRoute('cuti.pengganti.respond.oneclick', now()->addDays(7), ['cuti' => $cuti->id, 'action' => 'decline']);
                    Mail::to($pengganti->email)->send(new GenericNotification('Permintaan Pengganti Cuti', "Halo {$pengganti->name},", ["Anda diminta menjadi pengganti untuk cuti oleh {$cuti->user->name} untuk tanggal {$cuti->tanggal_mulai->format('d M Y')} sampai {$cuti->tanggal_selesai->format('d M Y')}.",], 'Tidak Bersedia', $declineUrl, 'Saya Bersedia', $acceptUrl));
                } catch (\Throwable $e) {
                    Log::error('Gagal mengirim email pengganti (via update): ' . $e->getMessage(), ['cuti_id' => $cuti->id, 'pengganti_id' => $pengganti->id]);
                }
            }
        }
        $cuti->save();

        // Hitung sisa cuti (default 12 hari/tahun jika tidak ada kebijakan per-user)
    $year = now()->year;
    $defaultQuota = $cuti->user->cuti_quota ?? 12;

        // Total approved cuti user untuk tahun ini (termasuk cuti yang baru saja disetujui)
        $usedDays = Cuti::where('user_id', $cuti->user_id)
            ->where('status', 'approved')
            ->whereYear('processed_at', $year)
            ->get()
            ->map(function($c) { return $c->duration ?? $c->getDurationAttribute(); })
            ->sum();

        $remaining = max(0, $defaultQuota - $usedDays);

        // Buat notifikasi in-app ke user tentang perubahan status + sisa cuti
        try {
            InAppMessage::create([
                'user_id' => $cuti->user_id,
                'cuti_id' => $cuti->id,
                'title' => 'Status pengajuan cuti: ' . ucfirst($cuti->status),
                'body' => "Pengajuan cuti Anda telah {$cuti->status}. Sisa cuti Anda saat ini: {$remaining} hari.",
            ]);
        } catch (\Exception $e) {
            // jika gagal, catat saja - email tetap dikirim
            logger()->error('Gagal membuat in-app message: ' . $e->getMessage());
        }

        // Kirim email notifikasi ke user, sertakan sisa cuti jika ada
        try {
            Mail::to($cuti->user->email)
                ->send(new CutiStatusUpdated($cuti, $remaining));
        } catch (\Exception $e) {
            logger()->error('Gagal mengirim email notifikasi cuti: ' . $e->getMessage());
        }

        return redirect()->route('hrd.cuti.index')
            ->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Assign a substitute (pengganti) for a pending cuti.
     */
    public function assignPengganti(Request $request, Cuti $cuti)
    {
        $request->validate([
            'pengganti_id' => 'required|exists:users,id',
        ]);

        // Ensure cuti is pending
        if ($cuti->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan yang masih pending dapat ditugaskan pengganti.');
        }

        $pengganti = User::find($request->pengganti_id);
        if (! $pengganti) {
            return redirect()->back()->with('error', 'Pengganti tidak ditemukan.');
        }

        // Set pengganti and initial status
        $cuti->pengganti_id = $pengganti->id;
        $cuti->pengganti_status = 'pending';
        $cuti->save();

    // Create in-app message
        try {
            InAppMessage::create([
                'user_id' => $pengganti->id,
                'cuti_id' => $cuti->id,
                'title' => 'Permintaan Menjadi Pengganti Cuti',
                'body' => "Anda diminta menjadi pengganti untuk cuti oleh {$cuti->user->name} pada tanggal {$cuti->tanggal_mulai->format('d M Y')} sampai {$cuti->tanggal_selesai->format('d M Y')}. Silakan konfirmasi.",
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal membuat in-app message untuk pengganti: ' . $e->getMessage(), ['cuti_id' => $cuti->id, 'pengganti_id' => $pengganti->id]);
        }

        // Send email fallback
        try {
            $subject = 'Permintaan Pengganti Cuti';
            $greeting = "Halo {$pengganti->name},";
            $lines = [
                "Anda diminta menjadi pengganti untuk cuti oleh {$cuti->user->name} untuk tanggal {$cuti->tanggal_mulai->format('d M Y')} sampai {$cuti->tanggal_selesai->format('d M Y')}.",
            ];
            $actionText = 'Konfirmasi Permintaan Pengganti';
            $actionUrl = URL::signedRoute('cuti.pengganti.confirm', ['cuti' => $cuti->id]);
            $acceptUrl = URL::temporarySignedRoute('cuti.pengganti.respond.oneclick', now()->addDays(7), ['cuti' => $cuti->id, 'action' => 'accept']);
            $declineUrl = URL::temporarySignedRoute('cuti.pengganti.respond.oneclick', now()->addDays(7), ['cuti' => $cuti->id, 'action' => 'decline']);
            // Also create a database notification with meta so the notifications view can render actions only for intended recipient
            try {
                $meta = ['type' => 'pengganti', 'to_user_id' => $pengganti->id, 'cuti_id' => $cuti->id];
                $pengganti->notify(new \App\Notifications\GeneralNotification('Permintaan Pengganti Cuti', $lines[0], URL::signedRoute('cuti.pengganti.confirm', ['cuti' => $cuti->id]), $meta));
            } catch (\Throwable $nex) {
                Log::warning('Gagal membuat database notification untuk pengganti (hrd assign): ' . $nex->getMessage(), ['pengganti_id' => $pengganti->id, 'cuti_id' => $cuti->id]);
            }

            Mail::to($pengganti->email)->send(new GenericNotification($subject, $greeting, $lines, 'Tidak Bersedia', $declineUrl, 'Saya Bersedia', $acceptUrl));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email pengganti: ' . $e->getMessage(), ['cuti_id' => $cuti->id, 'pengganti_id' => $pengganti->id]);
        }

        return redirect()->back()->with('success', 'Pengganti telah ditugaskan dan notifikasi telah dikirim.');
    }
}
