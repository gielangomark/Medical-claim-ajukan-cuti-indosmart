<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Claim;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Hitung total klaim yang sudah disetujui tahun ini
        $approvedClaimsTotal = Claim::where('user_id', $user->id)
                                    ->where('status', 'approved')
                                    ->whereYear('submitted_at', date('Y'))
                                    ->sum('total_amount');

        // Hitung sisa jatah
        $remainingAllotment = $user->claim_allotment - $approvedClaimsTotal;

        // Ambil 5 riwayat klaim terakhir
        $recentClaims = Claim::where('user_id', $user->id)
                            ->where('status', '!=', 'draft')
                            ->latest('submitted_at')
                            ->take(5)
                            ->get();

        return view('dashboard', compact('remainingAllotment', 'recentClaims'));
    }
}