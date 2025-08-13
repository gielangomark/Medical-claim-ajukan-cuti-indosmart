<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // <-- BARIS PENTING YANG MEMPERBAIKI ERROR

class CheckIsHrd
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Gunakan Auth facade untuk dukungan IDE yang lebih baik
        if (Auth::check() && Auth::user()->department === 'hrd') {
            return $next($request);
        }

        // Jika bukan HRD, lempar ke halaman dasbor biasa
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}