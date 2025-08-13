<?php

// Mengimpor semua controller yang akan digunakan
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataChangeRequestController;
use App\Http\Controllers\HRD\ClaimApprovalController;
use App\Http\Controllers\HRD\DataChangeApprovalController;
use App\Http\Controllers\HRD\UserController as HRDUserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PushSubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda mendaftarkan semua rute web untuk aplikasi Anda.
|
*/

// Rute untuk halaman login
Route::get('/', function () {
    return redirect() -> route('login'); // Arahkan ke halaman login
});

// Grup rute yang hanya bisa diakses oleh pengguna yang sudah login
Route::middleware('auth')->group(function () {
    
    // --- RUTE UNTUK KARYAWAN ---

    // Rute dasbor utama karyawan
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute profil pengguna (dibuat oleh Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk fitur klaim medis karyawan
    Route::resource('claims', ClaimController::class);
    Route::post('claims/{claim}/details', [ClaimController::class, 'storeDetail'])->name('claims.details.store');
    Route::delete('claims/details/{claim_detail}', [ClaimController::class, 'destroyDetail'])->name('claims.details.destroy');

    // Rute untuk pengajuan perubahan data oleh karyawan
    Route::get('/request-change', [DataChangeRequestController::class, 'create'])->name('request-change.create');
    Route::post('/request-change', [DataChangeRequestController::class, 'store'])->name('request-change.store');
    Route::get('/request-change/{data_change}', [DataChangeRequestController::class, 'show'])->name('request-change.show');

    // Rute untuk melihat notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Rute untuk halaman hasil aksi (sukses/gagal)
    Route::get('/result', function () {
        if (!session('result_data')) {
            return redirect('/dashboard');
        }
        return view('pages.result');
    })->name('result.page');

    //web push
    Route::post('/store-subscription', [PushSubscriptionController::class, 'store'])->name('subscription.store');


    // --- RUTE UNTUK HRD ---

    // Grup rute ini hanya bisa diakses oleh user dengan departemen 'hrd'
    Route::middleware('is_hrd')
        ->prefix('hrd') // Semua URL akan diawali dengan /hrd/...
        ->name('hrd.') // Semua nama rute akan diawali dengan hrd.
        ->group(function () {
            // Rute untuk manajemen karyawan (tambah, ubah, hapus)
            Route::resource('users', HRDUserController::class);

            // =======================================================
            // == BAGIAN YANG DIPERBARUI UNTUK PERSETUJUAN KLAIM ==
            // =======================================================
            Route::get('claims', [ClaimApprovalController::class, 'index'])->name('claims.index');
            Route::get('claims/{claim}', [ClaimApprovalController::class, 'show'])->name('claims.show');
            Route::put('claims/{claim}', [ClaimApprovalController::class, 'update'])->name('claims.update');
            
            // Rute untuk persetujuan perubahan data
            Route::resource('data-changes', DataChangeApprovalController::class);
        });
});

// Memuat rute-rute autentikasi (login, logout, dll.)
require __DIR__.'/auth.php';