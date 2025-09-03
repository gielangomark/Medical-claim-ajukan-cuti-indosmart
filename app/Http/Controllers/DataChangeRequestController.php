<?php

namespace App\Http\Controllers;

// Import class-class yang dibutuhkan
use App\Models\DataChangeRequest; // Model untuk tabel pengajuan perubahan data
use Illuminate\Http\Request; // Untuk mengelola data dari form
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan data user yang sedang login
use Illuminate\Support\Facades\Storage; // Ditambahkan untuk mengelola file
use Illuminate\Support\Facades\Log;

class DataChangeRequestController extends Controller
{
    /**
     * Menampilkan halaman formulir untuk membuat pengajuan perubahan data baru.
     * Method ini dipanggil saat karyawan menekan tombol [Ubah Data Pribadi].
     */
    public function create()
    {
        // Tampilkan view 'profile.request-change' yang ada di folder 'profile'.
        return view('profile.request-change');
    }

    /**
     * Menyimpan pengajuan perubahan data yang baru ke database.
     * Method ini dipanggil saat karyawan menekan tombol [Ajukan Perubahan].
     */
    public function store(Request $request)
    {
        // ========================================================================
        // PERBAIKAN 1: Tambahkan Pemeriksaan Keamanan di Awal
        // ========================================================================
        // Sebelum memproses apapun, cek dulu status user yang sedang login.
        if (strtolower(Auth::user()->marital_status) === 'menikah') {
            // Jika sudah menikah, hentikan proses dan kembalikan ke halaman sebelumnya
            // dengan pesan error. Ini mencegah pengajuan ganda.
            return back()->withErrors(['request' => 'Aksi tidak diizinkan. Status Anda sudah tercatat sebagai menikah.']);
        }

        // 1. Validasi semua input dari form. Jika ada yang tidak sesuai,
        //    Laravel akan otomatis kembali ke form dengan pesan error.
        //    'proof_document' diubah dari nullable menjadi required agar selaras dengan form.
        $validated = $request->validate([
            'new_marital_status' => 'required|in:menikah',
            'spouse_name' => 'required|string|max:255',
            'spouse_dob' => 'required|date',
            'proof_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // maks 5MB
        ]);

        // 2. Dapatkan data user yang sedang login.
        $user = Auth::user();
        
        // ========================================================================
        // PERBAIKAN 2: Logika untuk Menyimpan File yang Di-upload
        // ========================================================================
        $filePath = null; // Inisialisasi variabel path file.

        // Cek apakah ada file 'proof_document' yang dikirim dari form.
        if ($request->hasFile('proof_document')) {
            // Simpan file ke folder 'storage/app/public/proofs/data-changes'
            // dan simpan path-nya ke dalam variabel $filePath.
            // Pastikan Anda sudah menjalankan 'php artisan storage:link'.
            $filePath = $request->file('proof_document')->store('public/proofs/data-changes');
            // Normalize path to avoid stray whitespace/newlines
            $filePath = trim(str_replace('\\', '/', $filePath));
        }

        // 4. Buat record baru di tabel 'data_change_requests'.
        //    Sekarang 'proof_document_path' akan terisi dengan benar.
        $user->dataChangeRequests()->create([
            'request_type' => 'marital_status',
            // Simpan data baru dalam format JSON.
            'new_data' => [
                'marital_status' => $validated['new_marital_status'],
                'spouse_name' => $validated['spouse_name'],
                'spouse_dob' => $validated['spouse_dob'],
            ],
            'proof_document_path' => $filePath ? trim($filePath) : null,
        ]);

        // 5. Arahkan pengguna ke halaman hasil aksi dengan pesan sukses.
        return redirect()->route('result.page')->with('result_data', [
            'status' => 'success',
            'title' => 'Pengajuan Terkirim!',
            'message' => 'Pengajuan perubahan data Anda berhasil dikirim dan sedang menunggu verifikasi HRD.',
            'button_text' => 'Kembali ke Dasbor',
            'button_url' => route('dashboard')
        ]);
    }

    /**
     * Menampilkan detail dari satu pengajuan perubahan data.
     * Method ini dipanggil saat karyawan mengklik notifikasi.
     */
    public function show(DataChangeRequest $data_change)
    {
        // Keamanan: Pastikan user yang melihat adalah pemilik pengajuan atau HRD.
        // HRD harus bisa melihat semua pengajuan untuk kebutuhan verifikasi.
        if ($data_change->user_id !== Auth::id() && Auth::user()->department !== 'hrd') {
            abort(403); // Tampilkan halaman 'Forbidden' jika mencoba akses data orang lain.
        }

        // Tampilkan view 'profile.request-change-show' dan kirim data pengajuan yang dipilih.
    return view('profile.request-change-show', ['change' => $data_change]);
    }

    /**
     * Stream proof document to authorized users (owner or HRD).
     */
    public function proof(DataChangeRequest $data_change)
    {
        // Authorization: owner or HRD
        if ($data_change->user_id !== Auth::id() && Auth::user()->department !== 'hrd') {
            abort(403);
        }

        if (! $data_change->proof_document_path) {
            abort(404);
        }

        // Normalize path: trim, remove newlines and leading "public/" if present
        $raw = $data_change->proof_document_path;
        $rawTrim = trim($raw);
        // Remove any stray CR/LF that were accidentally saved
        $rawSanitized = str_replace(["\r", "\n"], '', $rawTrim);
        $path = preg_replace('/^public\//', '', $rawSanitized);

        // Debug log to help reproduce missing/invalid image issues
        Log::info('proof.request', [
            'data_change_id' => $data_change->id,
            'user_id' => Auth::id(),
            'raw_value' => $raw,
            'sanitized' => $rawSanitized,
            'storage_path' => $path,
        ]);

        // Use Storage facade to get full path
        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            Log::warning('proof.missing_file', ['data_change_id' => $data_change->id, 'checked_path' => $path]);
            abort(404);
        }

        $fullPath = $disk->path($path);

        return response()->file($fullPath);
    }
}   