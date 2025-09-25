<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SlipController extends Controller
{
    public function index()
    {
        return view('hrd.slip.index');
    }

    public function show($brand)
    {
        if (!in_array($brand, ['indosmart', 'smarttech'])) {
            return redirect()->route('hrd.slip.index');
        }
        return view('hrd.slip.form', ['brand' => $brand]);
    }

    public function generatePdf(Request $request)
    {
        $brand = $request->input('brand', 'indosmart');
        $logo = $brand === 'smarttech' ? 
            public_path('smarttech_logo.png') : 
            public_path('indosmart_logo.png');

        $data = [
            'logo' => $logo,
            'period' => Carbon::now()->format('Y/m'),
            'employee_name' => $request->input('employee_name'),
            'department' => $request->input('department'),
            'join_date' => $request->input('join_date'),
            'income' => json_decode($request->input('income', '{}'), true),
            'deduction' => json_decode($request->input('deduction', '{}'), true),
            'total_income' => floatval($request->input('total_income', 0)),
            'total_deduction' => floatval($request->input('total_deduction', 0)),
            'total_net' => floatval($request->input('total_net', 0))
        ];

        $pdf = PDF::loadView('hrd.slip-pdf', $data);
        $pdf->setPaper('A4');

        return $pdf->stream('slip-gaji.pdf');
    }
}