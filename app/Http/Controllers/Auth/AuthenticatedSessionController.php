<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
// PERBAIKAN: Menghapus 'use' yang tidak diperlukan lagi
// use App\Providers\RouteServiceProvider; 

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Menangani permintaan autentikasi yang masuk.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form login (boleh NIK atau email)
        $request->validate([
            'nik' => ['required_without:email', 'string'],
            'email' => ['required_without:nik', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Siapkan data kredensial untuk percobaan login (prioritaskan email jika ada)
        if ($request->filled('email')) {
            $credentials = $request->only('email', 'password');
        } else {
            $credentials = $request->only('nik', 'password');
        }

        // 3. Coba lakukan proses autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil, buat ulang session untuk keamanan
            $request->session()->regenerate();

            // 4. Cek departemen pengguna yang berhasil login
            $user = Auth::user();
            if ($user->department === 'hrd') {
                // Jika departemennya 'hrd', arahkan ke dasbor HRD
                return redirect()->route('hrd.users.index');
            }

            // PERBAIKAN: Mengarahkan langsung ke rute 'dashboard'
            // Jika bukan HRD, arahkan ke dasbor karyawan biasa
            return redirect()->intended(route('dashboard'));
        }

        // 5. Jika autentikasi gagal, kembali ke halaman login
        //    dengan pesan error dan simpan input NIK.
        return back()
    ->withErrors([
        'nik' => 'NIK atau Password yang Anda masukkan salah.',
    ])
    ->with('skip_splash', true)
    ->onlyInput('nik'); // <- titik koma di sini penting
    }

    /**
     * Menghancurkan sesi autentikasi (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
