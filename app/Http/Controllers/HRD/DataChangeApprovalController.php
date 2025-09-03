<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\DataChangeRequest;
use App\Mail\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DataChangeApprovalController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan perubahan data.
     */
    public function index()
    {
        $requests = DataChangeRequest::where('status', 'pending')
                                     ->with('user')
                                     ->latest()
                                     ->paginate(10);

        return view('hrd.data-changes.index', compact('requests'));
    }

    
    public function show(DataChangeRequest $data_change)
    {
        // Pass the model as 'change' to avoid colliding with the HTTP request variable in views
        return view('hrd.data-changes.show', ['change' => $data_change]);
    }

    /**
     * Memproses persetujuan atau penolakan pengajuan perubahan data.
     */
    public function update(Request $request, DataChangeRequest $data_change)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|min:10',
        ]);

        try {
            // Memulai Database Transaction
            DB::beginTransaction();

            $user = $data_change->user;
            $data_change->status = $validated['status'];
            $data_change->processed_by = Auth::id();

            $subject = '';
            $lines = [];
            $flashMessage = '';

            if ($validated['status'] === 'approved') {
                if ($data_change->request_type === 'marital_status') {
                    $newData = $data_change->new_data;
                    $user->marital_status = $newData['marital_status'];
                    
                    $user->familyMembers()->delete();
                    $user->familyMembers()->create([
                        'name' => $newData['spouse_name'],
                        'relationship' => $user->gender === 'pria' ? 'istri' : 'suami',
                        'date_of_birth' => $newData['spouse_dob'],
                    ]);
                    $user->save();
                }
                
                $subject = "Pengajuan Perubahan Data Disetujui";
                $lines = [
                    "Kabar baik! Pengajuan perubahan status perkawinan Anda telah disetujui.",
                    "Data pribadi Anda di sistem telah berhasil diperbarui."
                ];
                $flashMessage = 'Pengajuan perubahan data telah disetujui.';

            } elseif ($validated['status'] === 'rejected') {
                $data_change->rejection_reason = $validated['rejection_reason'];
                $subject = "Pengajuan Perubahan Data Ditolak";
                $lines = [
                    "Mohon maaf, pengajuan perubahan data Anda ditolak.",
                    "<strong>Alasan:</strong> " . $validated['rejection_reason'],
                ];
                $flashMessage = 'Pengajuan perubahan data telah ditolak.';
            }
            
            $data_change->save();

            // Kirim notifikasi email
            $greeting = "Halo, {$user->name}!";
            $actionText = 'Lihat Detail';
            $actionUrl = route('request-change.show', $data_change);
            Mail::to($user->email)->send(new GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));

            // Jika semua berhasil, commit transaksi
            DB::commit();

            return redirect()->route('hrd.data-changes.index')->with('success', $flashMessage);

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua perubahan di database
            DB::rollBack();

            // Catat error ke log untuk debugging
            Log::error('Gagal memproses perubahan data: ' . $e->getMessage());

            // Kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan internal saat memproses data. Silakan coba lagi.');
        }
    }

    /**
     * Handle rejection via DELETE form (legacy UI posts a DELETE with rejection_reason).
     */
    public function destroy(Request $request, DataChangeRequest $data_change)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        try {
            DB::beginTransaction();

            $user = $data_change->user;
            $data_change->status = 'rejected';
            $data_change->rejection_reason = $validated['rejection_reason'];
            $data_change->processed_by = Auth::id();
            $data_change->save();

            // Kirim notifikasi email ke pemohon
            $subject = "Pengajuan Perubahan Data Ditolak";
            $lines = [
                "Mohon maaf, pengajuan perubahan data Anda ditolak.",
                "<strong>Alasan:</strong> " . $validated['rejection_reason'],
            ];
            $greeting = "Halo, {$user->name}!";
            $actionText = 'Lihat Pengajuan';
            $actionUrl = route('request-change.show', $data_change);
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\GenericNotification($subject, $greeting, $lines, $actionText, $actionUrl));

            DB::commit();

            return redirect()->route('hrd.data-changes.index')->with('success', 'Pengajuan telah ditolak dan pemberitahuan dikirimkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menolak pengajuan perubahan data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses penolakan.');
        }
    }
}