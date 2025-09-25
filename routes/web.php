<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DataChangeRequestController;
use App\Http\Controllers\HRD\UserController as HRDUserController;
use App\Http\Controllers\HRD\ClaimApprovalController;
use App\Http\Controllers\HRD\CutiApprovalController;
use App\Http\Controllers\HRD\DataChangeApprovalController;

// --- LOGIN PAGE ---
Route::get('/', function () {
    return redirect()->route('login');
});

// --- AUTHENTICATED USER ---
Route::middleware('auth')->group(function () {
    // --- PORTAL PILIHAN ---
    Route::get('/portal', function () {
        return view('portal'); // Halaman pilih medical/cuti
    })->name('portal');

    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- NOTIFIKASI ---
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // --- WEB PUSH ---
    Route::post('/store-subscription', [PushSubscriptionController::class, 'store'])->name('subscription.store');

    // --- HASIL AKSI ---
    Route::get('/result', function () {
        if (!session('result_data')) {
            return redirect('/dashboard');
        }
        return view('pages.result');
    })->name('result.page');

    // --- MEDICAL & CUTI DALAM 1 DATABASE ---
    // Semua pengajuan (medical/cuti) bisa diakses dan dikelola dalam satu database
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
    // Medical Claim
    // Use 'claim' as the route parameter name so it matches controller method signatures and custom routes
    Route::resource('medical', ClaimController::class)->parameters(['medical' => 'claim']);
        Route::post('medical/{claim}/details', [ClaimController::class, 'storeDetail'])->name('medical.details.store');
        Route::delete('medical/details/{claim_detail}', [ClaimController::class, 'destroyDetail'])->name('medical.details.destroy');

        // Cuti
        Route::resource('cuti', CutiController::class);
    });

    // --- PENGAJUAN PERUBAHAN DATA ---
    Route::get('/request-change', [DataChangeRequestController::class, 'create'])->name('request-change.create');
    Route::post('/request-change', [DataChangeRequestController::class, 'store'])->name('request-change.store');
    Route::get('/request-change/{data_change}', [DataChangeRequestController::class, 'show'])->name('request-change.show');
    // Serve proof documents via controller (authorized) to avoid direct storage/public access issues
    Route::get('/request-change/{data_change}/proof', [DataChangeRequestController::class, 'proof'])->name('request-change.proof');

    // --- HRD ---
    Route::middleware('is_hrd')
        ->prefix('hrd')
        ->name('hrd.')
        ->group(function () {
            // Dashboard HRD
            Route::get('/dashboard', [DashboardController::class, 'hrdIndex'])->name('dashboard');
            
            // Manajemen User
            Route::resource('users', HRDUserController::class);

            // Approve Medical Claim
            Route::get('claims', [ClaimApprovalController::class, 'index'])->name('claims.index');
            Route::get('claims/{claim}', [ClaimApprovalController::class, 'show'])->name('claims.show');
            Route::put('claims/{claim}', [ClaimApprovalController::class, 'update'])->name('claims.update');

            // Approve Cuti
            Route::get('cuti', [CutiApprovalController::class, 'index'])->name('cuti.index');
            Route::get('cuti/{cuti}', [CutiApprovalController::class, 'show'])->name('cuti.show');
            Route::put('cuti/{cuti}', [CutiApprovalController::class, 'update'])->name('cuti.update');
            // Assign a substitute (pengganti)
            Route::post('cuti/{cuti}/assign-pengganti', [CutiApprovalController::class, 'assignPengganti'])->name('cuti.assignPengganti');

            // Approve Perubahan Data
            Route::resource('data-changes', DataChangeApprovalController::class);

            // Slip Gaji Routes
            Route::prefix('slip-gaji')->name('slip.')->group(function () {
                Route::get('/', [App\Http\Controllers\HRD\SlipController::class, 'index'])->name('index');
                Route::get('/{brand}', [App\Http\Controllers\HRD\SlipController::class, 'show'])
                    ->name('show')
                    ->where('brand', 'indosmart|smarttech');
                Route::post('/generate-pdf', [App\Http\Controllers\HRD\SlipController::class, 'generatePdf'])->name('pdf');
            });
        });
});

// Route for pengganti to respond to requests (accept/decline)
Route::middleware('auth')->post('/cuti/{cuti}/respond-pengganti', [CutiController::class, 'respondPengganti'])->name('cuti.respondPengganti');

// Public signed link for pengganti confirmation (usable from email)
Route::get('/cuti/{cuti}/pengganti/confirm', [CutiController::class, 'confirmPenggantiPage'])->name('cuti.pengganti.confirm')->middleware('signed');
Route::post('/cuti/{cuti}/pengganti/respond', [CutiController::class, 'respondPenggantiSigned'])->name('cuti.pengganti.respond')->middleware('signed');
// One-click signed GET links for email buttons (accept/decline)
Route::get('/cuti/{cuti}/pengganti/respond/oneclick', [CutiController::class, 'respondPenggantiOneClick'])->name('cuti.pengganti.respond.oneclick')->middleware('signed');

// Auth route (login, register, dll.)
require __DIR__.'/auth.php';

// Temporary debug route: serve proof file by id with a one-time token.
// USAGE: /debug/request-change/{id}/proof/{token}
// Token defaults to 'devdebug' or can be set via .env DEBUG_PROOF_TOKEN
Route::get('/debug/request-change/{id}/proof/{token}', function ($id, $token) {
    $expected = env('DEBUG_PROOF_TOKEN', 'devdebug');
    if (! hash_equals($expected, $token)) {
        abort(403);
    }

    $r = App\Models\DataChangeRequest::find($id);
    if (! $r || ! $r->proof_document_path) {
        abort(404);
    }

    $path = preg_replace('/^public\//', '', trim(str_replace(["\r", "\n"], '', $r->proof_document_path)));
    $disk = Illuminate\Support\Facades\Storage::disk('public');
    if (! $disk->exists($path)) {
        abort(404);
    }

    return response()->file($disk->path($path));
});
