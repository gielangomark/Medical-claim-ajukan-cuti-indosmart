<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class CutiController extends Controller
{
    public function index()
    {
        $cuti = Cuti::where('user_id', Auth::id())
                    ->latest()
                    ->paginate(10);
        // Hitung sisa cuti untuk user saat ini
        $user = Auth::user();
        $year = now()->year;
        $quota = $user->cuti_quota ?? 12;
        $usedDays = Cuti::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('processed_at', $year)
            ->get()
            ->map(function ($c) { return $c->duration ?? $c->getDurationAttribute(); })
            ->sum();
        $remaining = max(0, $quota - $usedDays);

        return view('cuti.index', compact('cuti', 'remaining'));
    }

    public function create()
    {
        $user = Auth::user();
        // Hitung sisa cuti untuk ditampilkan di form (default 12 hari/tahun)
    $year = now()->year;
    $defaultQuota = $user->cuti_quota ?? 12;
    $usedDays = Cuti::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('processed_at', $year)
            ->get()
            ->map(function ($c) { return $c->duration ?? $c->getDurationAttribute(); })
            ->sum();
        $remaining = max(0, $defaultQuota - $usedDays);

        return view('cuti.create', compact('user', 'remaining'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|min:10',
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ], [
            'alasan.min' => 'Alasan minimal 10 huruf.'
        ]);

        // Prevent accidental duplicate submissions: check for an existing pending cuti
        $existing = Cuti::where('user_id', Auth::id())
            ->where('tanggal_mulai', $validated['tanggal_mulai'])
            ->where('tanggal_selesai', $validated['tanggal_selesai'])
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subSeconds(10))
            ->first();
        if ($existing) {
            return redirect()->route('pengajuan.cuti.index')
                ->with('warning', 'Sepertinya pengajuan yang sama baru saja dikirim. Silakan cek daftar pengajuan Anda.');
        }

        // Server-side: periksa kuota tahunan sebelum menyimpan
        $requestedDays = Carbon::parse($validated['tanggal_mulai'])->diffInDays(Carbon::parse($validated['tanggal_selesai'])) + 1;
    $year = now()->year;
    $user = Auth::user();
    $defaultQuota = $user->cuti_quota ?? 12;
    $usedDays = Cuti::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->whereYear('processed_at', $year)
            ->get()
            ->map(function ($c) { return $c->duration ?? $c->getDurationAttribute(); })
            ->sum();
        $remaining = max(0, $defaultQuota - $usedDays);

        if ($remaining <= 0) {
            return redirect()->back()->withInput()->with('error', 'Anda sudah tidak memiliki sisa cuti tahunan. Hubungi HRD jika ada keperluan khusus.');
        }

        if ($requestedDays > $remaining) {
            return redirect()->back()->withInput()->with('error', "Permintaan cuti melebihi sisa cuti Anda ({$remaining} hari). Silakan kurangi durasi cuti.");
        }

        // Simpan cuti dalam transaksi singkat
        DB::beginTransaction();
        try {
            $cuti = new Cuti();
            $cuti->user_id = Auth::id();
            $cuti->tanggal_mulai = $validated['tanggal_mulai'];
            $cuti->tanggal_selesai = $validated['tanggal_selesai'];
            $cuti->alasan = $validated['alasan'];
            $cuti->status = 'pending';

            if ($request->hasFile('dokumen_pendukung')) {
                $path = $request->file('dokumen_pendukung')->store('cuti-documents', 'public');
                $cuti->dokumen_pendukung = $path;
            }

            $cuti->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan cuti: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            if ($request->wantsJson() || $request->ajax() || str_contains($request->header('Accept', ''), 'application/json')) {
                return response()->json(['ok' => false, 'message' => 'Gagal menyimpan pengajuan cuti.'], 500);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pengajuan cuti. Silakan coba lagi.');
        }

        // Notifikasi ke HRD — bungkus dengan try/catch supaya kegagalan insert notifikasi
        // tidak menggagalkan penyimpanan pengajuan cuti.
        // Notifikasi: coba simpan ke tabel messages; jika gagal, kirim email fallback
        $hrUsers = User::where('role', 'hrd')->get();
        $notified_db = [];
        $emailed = [];
        $failed_db = [];

        foreach ($hrUsers as $hrUser) {
            try {
                Message::create([
                    'user_id' => $hrUser->id,
                    'cuti_id' => $cuti->id,
                    'title' => 'Pengajuan Cuti Baru',
                    'body' => "Ada pengajuan cuti baru dari {$cuti->user->name} untuk tanggal {$cuti->tanggal_mulai} sampai {$cuti->tanggal_selesai}."
                ]);
                // Also create a database notification so it appears in the notifications UI
                try {
                    $hrUser->notify(new \App\Notifications\GeneralNotification('Pengajuan Cuti Baru', "Ada pengajuan cuti baru dari {$cuti->user->name} untuk tanggal {$cuti->tanggal_mulai} sampai {$cuti->tanggal_selesai}.", url("/hrd/cuti/{$cuti->id}")));
                } catch (\Throwable $nex) {
                    Log::warning('Gagal membuat database notification untuk HRD: ' . $nex->getMessage(), ['hr_user_id' => $hrUser->id, 'cuti_id' => $cuti->id]);
                }
                $notified_db[] = $hrUser->id;
            } catch (\Throwable $e) {
                Log::warning('Gagal membuat Message untuk HRD, jadwalkan email fallback: ' . $e->getMessage(), [
                    'cuti_id' => $cuti->id,
                    'hr_user_id' => $hrUser->id,
                ]);
                $failed_db[] = $hrUser;
            }
        }

        // Jika ada yang gagal di DB, kirim email fallback ke mereka
        foreach ($failed_db as $hrUser) {
            try {
                $subject = 'Pengajuan Cuti Baru';
                $greeting = "Halo {$hrUser->name},";
                $lines = [
                    "Ada pengajuan cuti baru dari {$cuti->user->name} untuk tanggal {$cuti->tanggal_mulai} sampai {$cuti->tanggal_selesai}.",
                ];
                $actionText = 'Lihat Pengajuan';
                $actionUrl = url("/hrd/cuti/{$cuti->id}");

                Mail::to($hrUser->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));
                $emailed[] = $hrUser->id;
                Log::info('Email fallback dikirim ke HRD', ['hr_user_id' => $hrUser->id, 'cuti_id' => $cuti->id]);
            } catch (\Throwable $mailEx) {
                Log::error('Gagal mengirim email fallback ke HRD: ' . $mailEx->getMessage(), [
                    'cuti_id' => $cuti->id,
                    'hr_user_id' => $hrUser->id,
                ]);
            }
        }

        // Response untuk AJAX/JSON
        if ($request->wantsJson() || $request->ajax() || str_contains($request->header('Accept', ''), 'application/json')) {
            return response()->json([
                'ok' => true,
                'cuti_id' => $cuti->id,
                'notified_db_ids' => $notified_db,
                'emailed_hr_ids' => $emailed,
                'message' => 'Pengajuan cuti berhasil dibuat.'
            ]);
        }

        // Non-AJAX: redirect with messages
        $redirect = redirect()->route('pengajuan.cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dibuat.');

        if (count($notified_db) === 0 && count($emailed) === 0) {
            $redirect = $redirect->with('warning', 'Pengajuan dibuat, namun notifikasi ke HRD gagal dibuat di sistem; silakan hubungi HRD untuk konfirmasi.');
        } elseif (count($notified_db) === 0 && count($emailed) > 0) {
            $redirect = $redirect->with('warning', 'Pengajuan dibuat. Notifikasi sistem gagal, tetapi email pemberitahuan telah dikirim ke HRD.');
        }

        return $redirect;
    }

    public function show(Cuti $cuti)
    {
        if ($cuti->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('cuti.show', compact('cuti'));
    }

    /**
     * Show a public signed confirmation page for the assigned pengganti.
     * This route is intended to be used from email links.
     */
    public function confirmPenggantiPage(Cuti $cuti)
    {
        // Ensure a pengganti was assigned
        if (! $cuti->pengganti_id) {
            abort(404, 'Tidak ada pengganti yang ditugaskan untuk pengajuan ini.');
        }

        $pengganti = $cuti->pengganti;
        return view('cuti.pengganti_confirm', compact('cuti', 'pengganti'));
    }

    public function ajukanPengganti(Request $request, $id)
    {
        $request->validate([
            'pengganti_id' => 'required|exists:users,id',
        ]);
        $cuti = Cuti::findOrFail($id);
        $cuti->pengganti_id = $request->pengganti_id;
        $cuti->save();

        // Kirim notifikasi ke user pengganti (bungkus try/catch seperti di atas)
        $pengganti = User::find($request->pengganti_id);
        $pemohon = $cuti->user;
        $body = "Anda diminta sebagai pengganti oleh HRD untuk cuti atas nama: {$pemohon->name}, mulai tanggal {$cuti->tanggal_mulai} sampai {$cuti->tanggal_selesai}. Silakan konfirmasi ke HRD jika bersedia.";
        try {
            Message::create([
                'user_id' => $pengganti->id,
                'cuti_id' => $cuti->id,
                'title' => 'Permintaan Persetujuan Pengganti Cuti',
                'body' => $body,
            ]);
            // Also send a database notification to the pengganti
                try {
                    $meta = ['type' => 'pengganti', 'to_user_id' => $pengganti->id, 'cuti_id' => $cuti->id];
                    $pengganti->notify(new \App\Notifications\GeneralNotification('Permintaan Pengganti Cuti', $body, URL::signedRoute('cuti.pengganti.confirm', ['cuti' => $cuti->id]), $meta));
                } catch (\Throwable $nex) {
                Log::warning('Gagal membuat database notification untuk pengganti: ' . $nex->getMessage(), ['pengganti_id' => $pengganti->id, 'cuti_id' => $cuti->id]);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal membuat Message untuk pengganti: ' . $e->getMessage(), [
                'cuti_id' => $cuti->id,
                'pengganti_id' => $pengganti->id,
            ]);
            // fallback email
            try {
                $subject = 'Permintaan Pengganti Cuti';
                $greeting = "Halo {$pengganti->name},";
                $lines = [$body];
                $actionText = 'Konfirmasi Permintaan Pengganti';
                // Generate a signed URL so the recipient can open the confirmation page without login
                $actionUrl = URL::signedRoute('cuti.pengganti.confirm', ['cuti' => $cuti->id]);
                // Also generate one-click accept/decline links (valid 7 days)
                $acceptUrl = URL::temporarySignedRoute('cuti.pengganti.respond.oneclick', now()->addDays(7), ['cuti' => $cuti->id, 'action' => 'accept']);
                $declineUrl = URL::temporarySignedRoute('cuti.pengganti.respond.oneclick', now()->addDays(7), ['cuti' => $cuti->id, 'action' => 'decline']);
                // Pass accept as actionText2 to show two buttons (green accept, red decline)
                Mail::to($pengganti->email)->send(new GenericNotification($subject, $greeting, $lines, 'Tidak Bersedia', $declineUrl, 'Saya Bersedia', $acceptUrl));
                Log::info('Email fallback dikirim ke pengganti', ['pengganti_id' => $pengganti->id, 'cuti_id' => $cuti->id]);
            } catch (\Throwable $mailEx) {
                Log::error('Gagal mengirim email fallback ke pengganti: ' . $mailEx->getMessage(), [
                    'cuti_id' => $cuti->id,
                    'pengganti_id' => $pengganti->id,
                ]);
            }
        }

        return redirect()->route('hrd.dashboard')->with('success', 'Permintaan pengganti telah dikirim.');
    }

    /**
     * Endpoint for pengganti to respond (accept/decline) a substitute request.
     * Expects: action=accept|decline
     */
    public function respondPengganti(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:accept,decline'
        ]);

        $cuti = Cuti::findOrFail($id);
        $user = Auth::user();

        // Ensure current user is the assigned pengganti
        if ($cuti->pengganti_id !== $user->id) {
            abort(403, 'Anda bukan pengganti yang ditugaskan untuk permintaan ini.');
        }

        $action = $request->action;
        $cuti->pengganti_status = $action === 'accept' ? 'accepted' : 'declined';
        $cuti->save();

        // Notify HRD and applicant about the response
        try {
            // Message to HRD
            $hrUsers = User::where('role', 'hrd')->get();
            foreach ($hrUsers as $hr) {
                Message::create([
                    'user_id' => $hr->id,
                    'cuti_id' => $cuti->id,
                    'title' => 'Respon Pengganti Cuti: ' . strtoupper($cuti->pengganti_status),
                    'body' => "{$user->name} telah {$cuti->pengganti_status} menjadi pengganti untuk pengajuan cuti ID {$cuti->id}.",
                ]);
                try {
                    $meta = ['type' => 'pengganti_response', 'cuti_id' => $cuti->id, 'by_user_id' => $user->id];
                    $hr->notify(new \App\Notifications\GeneralNotification('Respon Pengganti Cuti', "{$user->name} telah {$cuti->pengganti_status} menjadi pengganti untuk pengajuan cuti ID {$cuti->id}.", url("/hrd/cuti/{$cuti->id}"), $meta));
                } catch (\Throwable $nex) {
                    Log::warning('Gagal membuat database notification untuk HRD (respondPengganti): ' . $nex->getMessage());
                }
            }

            // Message to applicant
            Message::create([
                'user_id' => $cuti->user_id,
                'cuti_id' => $cuti->id,
                'title' => 'Respon Pengganti Cuti',
                'body' => "Pengganti ({$user->name}) telah {$cuti->pengganti_status} permintaan pengganti cuti Anda.",
            ]);
            try {
                $meta = ['type' => 'pengganti_response', 'cuti_id' => $cuti->id, 'by_user_id' => $user->id];
                $cuti->user->notify(new \App\Notifications\GeneralNotification('Respon Pengganti Cuti', "Pengganti ({$user->name}) telah {$cuti->pengganti_status} permintaan pengganti cuti Anda.", url("/pengajuan/cuti/{$cuti->id}"), $meta));
            } catch (\Throwable $nex) {
                Log::warning('Gagal membuat database notification untuk pemohon (respondPengganti): ' . $nex->getMessage());
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal membuat notifikasi respons pengganti: ' . $e->getMessage());
        }

        // Email fallback
        try {
            $subject = 'Respon Pengganti Cuti';
            $greeting = "Halo,
";
            $lines = ["Pengganti ({$user->name}) telah {$cuti->pengganti_status} permintaan pengganti cuti ID {$cuti->id}."];
            $actionText = 'Lihat Pengajuan';
            $actionUrl = url("/hrd/cuti/{$cuti->id}");

            $hrUsers = User::where('role', 'hrd')->get();
            foreach ($hrUsers as $hr) {
                Mail::to($hr->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));
            }

            Mail::to($cuti->user->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email notifikasi respons pengganti: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Terima kasih atas respons Anda. Status pengganti telah diperbarui.');
    }

    /**
     * Signed POST endpoint to respond to pengganti request (for email links).
     */
    public function respondPenggantiSigned(Request $request, Cuti $cuti)
    {
        $request->validate([
            'action' => 'required|in:accept,decline'
        ]);

        // Ensure a pengganti was assigned
        if (! $cuti->pengganti_id) {
            abort(404, 'Tidak ada pengganti yang ditugaskan untuk pengajuan ini.');
        }

        // For signed routes we don't require auth, but verify the signature middleware did its job.
        $action = $request->input('action');
        $cuti->pengganti_status = $action === 'accept' ? 'accepted' : 'declined';
        $cuti->save();

        // Notify HRD and applicant similarly to the authenticated flow
        try {
            $hrUsers = User::where('role', 'hrd')->get();
            foreach ($hrUsers as $hr) {
                Message::create([
                    'user_id' => $hr->id,
                    'cuti_id' => $cuti->id,
                    'title' => 'Respon Pengganti Cuti: ' . strtoupper($cuti->pengganti_status),
                    'body' => "Pengganti ({$cuti->pengganti->name}) telah {$cuti->pengganti_status} menjadi pengganti untuk pengajuan cuti ID {$cuti->id}.",
                ]);
                    try {
                        $hr->notify(new \App\Notifications\GeneralNotification('Respon Pengganti Cuti', "Pengganti ({$cuti->pengganti->name}) telah {$cuti->pengganti_status} menjadi pengganti untuk pengajuan cuti ID {$cuti->id}.", url("/hrd/cuti/{$cuti->id}")));
                    } catch (\Throwable $nex) {
                        Log::warning('Gagal membuat database notification untuk HRD (respondPenggantiSigned): ' . $nex->getMessage());
                    }
            }

            Message::create([
                'user_id' => $cuti->user_id,
                'cuti_id' => $cuti->id,
                'title' => 'Respon Pengganti Cuti',
                'body' => "Pengganti ({$cuti->pengganti->name}) telah {$cuti->pengganti_status} permintaan pengganti cuti Anda.",
            ]);
            try {
                $cuti->user->notify(new \App\Notifications\GeneralNotification('Respon Pengganti Cuti', "Pengganti ({$cuti->pengganti->name}) telah {$cuti->pengganti_status} permintaan pengganti cuti Anda.", url("/pengajuan/cuti/{$cuti->id}")));
            } catch (\Throwable $nex) {
                Log::warning('Gagal membuat database notification untuk pemohon (respondPenggantiSigned): ' . $nex->getMessage());
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal membuat notifikasi respons pengganti (signed): ' . $e->getMessage());
        }

        // Email fallback
        try {
            $subject = 'Respon Pengganti Cuti';
            $greeting = "Halo,";
            $lines = ["Pengganti ({$cuti->pengganti->name}) telah {$cuti->pengganti_status} permintaan pengganti cuti ID {$cuti->id}."];
            $actionText = 'Lihat Pengajuan';
            $actionUrl = url("/hrd/cuti/{$cuti->id}");

            foreach (User::where('role', 'hrd')->get() as $hr) {
                Mail::to($hr->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));
                try {
                    (new \App\Http\Controllers\PushSubscriptionController)->sendNotification($hr, ['title' => $subject, 'body' => $lines[0], 'url' => $actionUrl]);
                } catch (\Throwable $pex) {
                    Log::warning('Gagal mengirim push ke HRD: ' . $pex->getMessage());
                }
            }
            Mail::to($cuti->user->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email notifikasi respons pengganti (signed): ' . $e->getMessage());
        }

        return view('cuti.pengganti_thanks', ['cuti' => $cuti]);
    }

    /**
     * One-click signed GET endpoint to accept/decline from email buttons.
     * Expects query param 'action' = accept|decline
     */
    public function respondPenggantiOneClick(Request $request, Cuti $cuti)
    {
        $action = $request->query('action');
        if (! in_array($action, ['accept', 'decline'])) {
            abort(400, 'Invalid action');
        }

        if (! $cuti->pengganti_id) {
            abort(404, 'Tidak ada pengganti yang ditugaskan untuk pengajuan ini.');
        }

        // Update status
        $cuti->pengganti_status = $action === 'accept' ? 'accepted' : 'declined';
        $cuti->save();

    // Reuse logic from respondPenggantiSigned to notify
        try {
            $hrUsers = User::where('role', 'hrd')->get();
            foreach ($hrUsers as $hr) {
                Message::create([
                    'user_id' => $hr->id,
                    'cuti_id' => $cuti->id,
                    'title' => 'Respon Pengganti Cuti: ' . strtoupper($cuti->pengganti_status),
                    'body' => "Pengganti ({$cuti->pengganti->name}) telah {$cuti->pengganti_status} menjadi pengganti untuk pengajuan cuti ID {$cuti->id}.",
                ]);
            }

            Message::create([
                'user_id' => $cuti->user_id,
                'cuti_id' => $cuti->id,
                'title' => 'Respon Pengganti Cuti',
                'body' => "Pengganti ({$cuti->pengganti->name}) telah {$cuti->pengganti_status} permintaan pengganti cuti Anda.",
            ]);
                } catch (\Throwable $e) {
            Log::warning('Gagal membuat notifikasi respons pengganti (oneclick): ' . $e->getMessage());
        }

        // Email fallback + push
        try {
            $subject = 'Respon Pengganti Cuti';
            $greeting = "Halo,";
            $lines = ["Pengganti ({$cuti->pengganti->name}) telah {$cuti->pengganti_status} permintaan pengganti cuti ID {$cuti->id}."];
            $actionText = 'Lihat Pengajuan';
            $actionUrl = url("/hrd/cuti/{$cuti->id}");

            foreach (User::where('role', 'hrd')->get() as $hr) {
                Mail::to($hr->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));
                try {
                    (new \App\Http\Controllers\PushSubscriptionController)->sendNotification($hr, ['title' => $subject, 'body' => $lines[0], 'url' => $actionUrl]);
                } catch (\Throwable $pex) {
                    Log::warning('Gagal mengirim push ke HRD: ' . $pex->getMessage());
                }
            }
            Mail::to($cuti->user->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email notifikasi respons pengganti (oneclick): ' . $e->getMessage());
        }

        return view('cuti.pengganti_thanks', ['cuti' => $cuti]);
    }
}
// Auto refresh dashboard HRD setelah submit pengganti
// Tambahkan di view dashboardHRD.blade.php:
// @if(session('success'))
// <script>setTimeout(() => { location.reload(); }, 1200);</script>
// @endif
