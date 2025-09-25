<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SlipController extends Controller
{
    public function index(Request $request)
    {
        $brand = $request->query('brand', 'indosmart');
        return view('hrd.slip', compact('brand'));
    }

    public function generatePdf(Request $request)
    {
        $brand = $request->input('brand', 'indosmart');
        $logo = $brand === 'smarttech' ? 
            public_path('smarttech_logo.png') : 
            public_path('Indosmart_logo.png');

        $data = [
            'logo' => $logo,
            'period' => Carbon::now()->format('Y/m'),
            'employee_name' => $request->input('employee_name'),
            'department' => $request->input('department'),
            'join_date' => $request->input('join_date'),
            'income' => $request->input('income', []),
            'deduction' => $request->input('deduction', []),
            'total_income' => $request->input('total_income', 0),
            'total_deduction' => $request->input('total_deduction', 0),
            'total_net' => $request->input('total_net', 0),
        ];

        $pdf = PDF::loadView('hrd.slip-pdf', $data);
        $pdf->setPaper('A4');

        return $pdf->stream('slip-gaji.pdf');
    }
}