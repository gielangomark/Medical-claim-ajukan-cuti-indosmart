<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            line-height: 1.3;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .header .logo-container {
            margin-bottom: 10;
            height: 10px;
            position: relative;
            text-align: center;
        }
        /* Default logo style */
        .header .logo {
            position: absolute;
            transform: translateX(-50%);
            top: 0;
        }
        /* Specific style for Smarttech logo */
        .header .logo.smarttech {
            max-height: 70px;
            max-width: 1100px;
            left: 65%;
        }
        /* Specific style for Indosmart logo */
        .header .logo.indosmart {
            max-height: 360px; /* Lebih besar */
            max-width: 480px; /* Lebih besar */
            left: 28%;
        }
        .header .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        .company-info {
            float: left;
            font-size: 12px;
        }
        .employee-info {
            float: right;
            font-size: 12px;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f5f5f5;
        }
        .total-box {
            margin-top: 20px;
            text-align: right;
        }
        .total-amount {
            display: inline-block;
            border: 1px solid #ddd;
            padding: 10px 20px;
            min-width: 200px;
            text-align: center;
        }
        .total-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .total-value {
            font-size: 18px;
            font-weight: bold;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header" style="margin-top: 20px; margin-bottom: 30px;">
        <div class="logo-container">
            @if(str_contains($logo, 'smarttech'))
                <img src="{{ $logo }}" alt="Logo Smarttech" class="logo smarttech" style="margin: 0 auto;">
            @else
                <img src="{{ $logo }}" alt="Logo Indosmart" class="logo indosmart" style="margin: 0 auto;">
            @endif
        </div>
        <div class="title" style="margin-top: 90px;">Slip Gaji</div>
    </div>

    <div class="clearfix">
        <div class="company-info">
            <div style="font-weight: bold;">PT. Indosmart Komunikasi Global</div>
            <div>Plaza Marin Lt. 12 Jl. Jend. Sudirman</div>
            <div>Setiabudi Jakarta Selatan</div>
        </div>

        <div class="employee-info">
            <div >Nama / NIK: {{ $employee_name ?? '________________' }}</div>
            <div>Dept / Jabatan: {{ $department ?? '________________' }}</div>
            <div>Tgl Mulai Bekerja: {{ $join_date ?? '00/00/2025' }}</div>
            <div>Periode Gaji: {{ $period }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Pendapatan</th>
                <th>Rp.</th>
                <th>Potongan</th>
                <th>Rp.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td>{{ number_format($income['gaji_pokok'] ?? 0, 2, ',', '.') }}</td>
                <td>Potongan Absen</td>
                <td>{{ number_format($deduction['potongan_absen'] ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan Jabatan</td>
                <td>{{ number_format($income['tunjangan_jabatan'] ?? 0, 2, ',', '.') }}</td>
                <td>Potongan Datang Terlambat</td>
                <td>{{ number_format($deduction['potongan_terlambat'] ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan Fungsional</td>
                <td>{{ number_format($income['tunjangan_fungsional'] ?? 0, 2, ',', '.') }}</td>
                <td>Potongan BPJS</td>
                <td>{{ number_format($deduction['potongan_bpjs'] ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan Transport</td>
                <td>{{ number_format($income['tunjangan_transport'] ?? 0, 2, ',', '.') }}</td>
                <td>Potongan Pajak PPh21</td>
                <td>{{ number_format($deduction['potongan_pph21'] ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Dll</td>
                <td>{{ number_format($income['dll'] ?? 0, 2, ',', '.') }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td style="font-weight: bold;">Total Pendapatan</td>
                <td>{{ number_format($total_income ?? 0, 2, ',', '.') }}</td>
                <td style="font-weight: bold;">Total Potongan</td>
                <td>{{ number_format($total_deduction ?? 0, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="total-box">
        <div class="total-amount">
            <div class="total-label">Total Penerimaan Bulan Ini</div>
            <div class="total-value">Rp. {{ number_format($total_net ?? 0, 2, ',', '.') }}</div>
        </div>
    </div>

    <div class="signature">
        <div>Mengetahui</div>
        <div style="margin-top: 70px; font-weight: bold;">HRD Management</div>
    </div>
</body>
</html>