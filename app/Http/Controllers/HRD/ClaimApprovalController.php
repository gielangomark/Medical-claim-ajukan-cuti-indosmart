<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Mail\GenericNotification; // Gunakan Mailable ini
// use App\Notifications\GeneralNotification; // Bisa dihapus jika email sudah cukup
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
// use App\Mail\StatusUpdateMail; // Tidak lagi digunakan
use App\Http\Controllers\PushSubscriptionController;

class ClaimApprovalController extends Controller
{
    public function index(Request $request)
    {
        // --- LOGIKA STATISTIK ---
        $pendingCount = Claim::where('status', 'pending_approval')->count();
        $approvedCount = Claim::where('status', 'approved')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->count();
        $rejectedCount = Claim::where('status', 'rejected')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->count();

        // --- LOGIKA FILTER ---
        $query = Claim::where('status', '!=', 'draft')->with('user');
        if ($request->filled('search')) {
            // ... logika search Anda ...
        }
        if ($request->filled('status')) {
            // ... logika filter status Anda ...
        }
        $claims = $query->latest('submitted_at')->paginate(10);

        // --- PENGIRIMAN DATA KE VIEW ---
        if ($request->ajax()) {
            // PERBAIKAN: Kirim juga data 'counts' agar statistik bisa update
            return response()->json([
                'table_html' => view('hrd.claims._claims_table', compact('claims'))->render(),
                'counts' => [
                    'pending' => $pendingCount,
                    'approved' => $approvedCount,
                    'rejected' => $rejectedCount,
                ]
            ]);
        }
        return view('hrd.claims.index', compact('claims', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }
    
    public function show(Claim $claim)
    {
        $claim->load(['user', 'details']);
        return view('hrd.claims.show', compact('claim'));
    }

    /**
     * PERBAIKAN: Satu method untuk menyetujui atau menolak klaim.
     */
    public function update(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|min:10',
        ]);

        $claim->status = $validated['status'];
        
        // Menyiapkan variabel umum
        $userToNotify = $claim->user;
        $greeting = 'Halo, ' . $userToNotify->name;
        $actionText = 'Lihat Detail Klaim';
        $actionUrl = route('claims.show', $claim);
        $flashMessage = '';
        
        // Menyiapkan variabel spesifik berdasarkan status
        $subject = '';
        $lines = [];
        $notificationPayload = []; // <-- Siapkan array kosong untuk payload push notification

        if ($claim->status === 'approved') {
            $subject = "Pengajuan Klaim Disetujui";
            $lines = [
                "Kabar baik! Pengajuan klaim Anda untuk periode <strong>{$claim->period_month} {$claim->period_year}</strong> telah disetujui.",
                "Dana akan segera diproses."
            ];
            $flashMessage = 'Klaim berhasil disetujui.';

            // 2. Siapkan data untuk push notification "Disetujui"
            $notificationPayload = [
                'title' => 'Klaim Disetujui!',
                'body' => 'Pengajuan klaim Anda sebesar Rp '.number_format($claim->total_amount,0,',','.').' telah disetujui.',
                'url' => $actionUrl,
            ];
            
        } elseif ($claim->status === 'rejected') {
            $claim->rejection_reason = $validated['rejection_reason'];
            $subject = "Pengajuan Klaim Ditolak";
            $lines = [
                "Dengan berat hati kami informasikan bahwa pengajuan klaim Anda ditolak.",
                "<strong>Alasan:</strong> " . $validated['rejection_reason'],
            ];
            $flashMessage = 'Klaim telah ditolak.';

            // 2. Siapkan data untuk push notification "Ditolak"
            $notificationPayload = [
                'title' => 'Klaim Ditolak',
                'body' => 'Mohon maaf, pengajuan klaim Anda ditolak. Klik untuk melihat alasannya.',
                'url' => $actionUrl,
            ];
        }
        
        $claim->save();

        // --- PENGIRIMAN NOTIFIKASI ---

        // Kirim Notifikasi Email
        Mail::to($userToNotify->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));

        // 3. Kirim Web Push Notification
        if (!empty($notificationPayload) && $userToNotify->push_subscription) {
            (new PushSubscriptionController)->sendNotification($userToNotify, $notificationPayload);
        }

        return redirect()->route('hrd.claims.index')->with('success', $flashMessage);
    }

    // Method destroy() tidak lagi diperlukan untuk menolak klaim, bisa dihapus.
}