<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Notifications\NewClaimToHrNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Claim;
use App\Models\ClaimDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    // Tampilkan dashboard/daftar klaim untuk user yang sedang login
    $user = Auth::user();

    // Ambil klaim milik user
    $claims = Claim::where('user_id', $user->id)->orderByDesc('created_at')->get();

    // Hitung total klaim yang sudah disetujui tahun ini untuk menghitung sisa jatah
    $approvedClaimsTotal = Claim::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->whereYear('submitted_at', date('Y'))
                    ->sum('total_amount');

    $remainingAllotment = $user->claim_allotment - $approvedClaimsTotal;

    // Ambil 5 riwayat klaim terakhir (non-draft)
    $recentClaims = Claim::where('user_id', $user->id)
                ->where('status', '!=', 'draft')
                ->latest('submitted_at')
                ->take(5)
                ->get();

    return view('claims.dashboard', compact('claims', 'remainingAllotment', 'recentClaims'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $user->load('familyMembers');

        $claim = Claim::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'draft'],
            ['period_month' => date('F'), 'period_year' => date('Y')]
        );
        $claim->load('details');

        $patientOptions = [];
        $patientOptions[] = ['name' => $user->name, 'relationship' => 'Diri Sendiri'];

        if ($user->marital_status === 'menikah') {
            foreach ($user->familyMembers as $member) {
                $patientOptions[] = [
                    'name' => $member->name,
                    'relationship' => ucfirst($member->relationship)
                ];
            }
        }

        $approvedClaimsTotal = Claim::where('user_id', $user->id)
                                        ->where('status', 'approved')
                                        ->whereYear('submitted_at', date('Y'))
                                        ->sum('total_amount');
        $remainingAllotment = $user->claim_allotment - $approvedClaimsTotal;

        return view('claims.create', compact('claim', 'patientOptions', 'remainingAllotment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tidak digunakan untuk alur ini
    }

    /**
     * Method ini menangani penyimpanan rincian klaim dari modal.
     */
    public function storeDetail(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $filePath = $file->store('claims/proofs', 'public');
        } 

        $claim->details()->create([
            'transaction_date' => $validated['transaction_date'],
            'description' => $validated['description'],
            'patient_name' => $validated['patient_name'],
            'amount' => $validated['amount'],
            'proof_file_path' => $filePath,
        ]);

        $claim->total_amount = $claim->details()->sum('amount');
        $claim->save();

        return redirect()->route('pengajuan.medical.create')->with('success', 'Rincian klaim berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Claim $claim)
    {
    // Allow any authenticated user to view the claim (per request)
    // Keep loading details and show the view. Admin/HRD logic still applies elsewhere.
    $claim->load('details');
    return view('claims.show', compact('claim'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Claim $claim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Ini adalah logika untuk tombol [Ajukan Sekarang]
     */
   public function update(Request $request, Claim $claim)
    {
        $user = Auth::user();

        // If the current user is not the claim owner, log it but allow submission per requested behavior
        if ($claim->user_id !== $user->id) {
            Log::info('Claim submitted by non-owner', ['claim_id' => $claim->id, 'claim_user_id' => $claim->user_id, 'auth_user_id' => $user->id]);
            // optionally, you may want to notify HRD or the original owner here
        }

        // Debug: log detail count and items to help investigate cases where count() seems 0
        try {
            $detailCount = $claim->details()->count();
            $detailSamples = $claim->details()->select('id','amount','description')->take(5)->get()->toArray();
            Log::debug('Claim update - details snapshot', ['claim_id' => $claim->id, 'detail_count' => $detailCount, 'details' => $detailSamples]);
        } catch (\Exception $e) {
            Log::error('Failed to snapshot claim details for debug', ['claim_id' => $claim->id, 'error' => $e->getMessage()]);
        }

        // Ensure we have the latest DB state for this claim and its relations
        try {
            $claim->refresh();
            $claim->load('details');
        } catch (\Exception $e) {
            Log::warning('Failed to refresh claim before validation', ['claim_id' => $claim->id, 'error' => $e->getMessage()]);
        }

        $claim->update($request->only(['period_month', 'period_year']));

        if ($claim->details()->count() === 0) {
            return redirect()->route('result.page')->with('result_data', [
                'status' => 'failure',
                'title' => 'Pengajuan Gagal!',
                'message' => 'Anda harus menambahkan setidaknya satu rincian klaim sebelum mengajukan.',
                'button_text' => 'Kembali ke Formulir',
                'button_url' => route('pengajuan.medical.create')
            ]);
        }

        $approvedClaimsTotal = Claim::where('user_id', $user->id)
                                    ->where('status', 'approved')
                                    ->whereYear('submitted_at', date('Y'))
                                    ->sum('total_amount');
        $remainingAllotment = $user->claim_allotment - $approvedClaimsTotal;

        if ($claim->total_amount > $remainingAllotment) {
            return redirect()->route('result.page')->with('result_data', [
                'status' => 'failure',
                'title' => 'Pengajuan Gagal!',
                'message' => 'Total klaim yang Anda ajukan (Rp '.number_format($claim->total_amount,0,',','.').') melebihi sisa jatah Anda (Rp '.number_format($remainingAllotment,0,',','.').').',
                'button_text' => 'Kembali ke Formulir',
                'button_url' => route('pengajuan.medical.create')
            ]);
        }

        $claim->status = 'pending_approval';
        $claim->submitted_at = now();
        $claim->save();

        // --- LOGIKA BARU UNTUK KIRIM NOTIFIKASI KE HRD ---
        // 1. Cari semua user yang departemennya adalah 'hrd'
        $hrdUsers = User::where('department', 'hrd')->get();

        // 2. Kirim notifikasi ke semua user HRD yang ditemukan
        if ($hrdUsers->isNotEmpty()) {
            Notification::send($hrdUsers, new NewClaimToHrNotification($claim));
        }
        // --- AKHIR DARI LOGIKA BARU ---

        return redirect()->route('result.page')->with('result_data', [
            'status' => 'success',
            'title' => 'Pengajuan Terkirim!',
            'message' => 'Pengajuan klaim Anda telah berhasil dikirim dan akan segera diproses oleh HRD.',
            'button_text' => 'Kembali ke Dasbor',
            'button_url' => route('dashboard')
        ]);
    }

    /**
     * Menghapus satu rincian klaim.
     */
    public function destroyDetail(ClaimDetail $claim_detail)
    {
        $claim = $claim_detail->claim;

        if ($claim->user_id !== Auth::id() || $claim->status !== 'draft') {
            abort(403, 'Tindakan tidak diizinkan.');
        }

        if ($claim_detail->proof_file_path) {
        Storage::disk('public')->delete($claim_detail->proof_file_path);
         }
    
        $claim_detail->delete();

        $claim->total_amount = $claim->details()->sum('amount');
        $claim->save();

        return back()->with('success', 'Rincian klaim berhasil dihapus.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Claim $claim)
    {
        // Tidak digunakan untuk alur ini
    }
}
