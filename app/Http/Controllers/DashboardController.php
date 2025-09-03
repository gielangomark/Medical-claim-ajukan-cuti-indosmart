<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Claim;

class DashboardController extends Controller
{
    public function index()
    {
    // Redirect ke halaman pilihan portal agar user memilih modul (medical/cuti)
    return redirect()->route('portal');
    }
}