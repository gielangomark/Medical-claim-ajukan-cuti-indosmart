@extends('layouts.slip')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Slip Gaji {{ ucfirst($brand) }}</h1>
        <a href="{{ route('hrd.slip.index') }}" class="text-gray-600 hover:text-gray-900">
            <span class="text-sm">&larr; Kembali ke Pilihan Format</span>
        </a>
    </div>

    <div class="text-center mb-8">
        @if($brand === 'smarttech')
            <img src="{{ asset('smarttech_logo.png') }}" alt="Logo Smarttech" class="mx-auto h-20 mb-4">
        @else
            <img src="{{ asset('indosmart_logo.png') }}" alt="Logo Indosmart" class="mx-auto h-20 mb-4">
        @endif
        <h2 class="text-2xl font-bold">Slip Gaji</h2>
    </div>

    <div class="flex justify-between items-start text-sm text-slate-600 mb-6">
        <div class="text-left">
            <div class="font-semibold">
                @if($brand === 'smarttech')
                    PT. Smarttech Global Komunikasi
                @else
                    PT. Indosmart Komunikasi Global
                @endif
            </div>
            <div>Plaza Marin Lt. 12 Jl. Jend. Sudirman</div>
            <div>Setiabudi Jakarta Selatan</div>
        </div>

        <div class="text-right">
            <div class="mb-2">
                <input type="text" name="employee_name" class="border rounded px-2 py-1 w-64" placeholder="Nama / NIK">
            </div>
            <div class="mb-2">
                <input type="text" name="department" class="border rounded px-2 py-1 w-64" placeholder="Dept / Jabatan">
            </div>
            <div class="mb-2">
                <input type="text" name="join_date" class="border rounded px-2 py-1 w-64" placeholder="Tgl Mulai Bekerja (DD/MM/YYYY)">
            </div>
            <div>Periode Gaji: {{ now()->format('Y/m') }}</div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Pendapatan</th>
                    <th class="p-2 border">Rp.</th>
                    <th class="p-2 border">Potongan</th>
                    <th class="p-2 border">Rp.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border">Gaji Pokok</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount income" data-label="gaji_pokok"></td>
                    <td class="p-2 border">Potongan Absen</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount deduction" data-label="potongan_absen"></td>
                </tr>
                <tr>
                    <td class="p-2 border">Tunjangan Jabatan</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount income" data-label="tunjangan_jabatan"></td>
                    <td class="p-2 border">Potongan Datang Terlambat</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount deduction" data-label="potongan_terlambat"></td>
                </tr>
                <tr>
                    <td class="p-2 border">Tunjangan Fungsional</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount income" data-label="tunjangan_fungsional"></td>
                    <td class="p-2 border">Potongan BPJS</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount deduction" data-label="potongan_bpjs"></td>
                </tr>
                <tr>
                    <td class="p-2 border">Tunjangan Transport</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount income" data-label="tunjangan_transport"></td>
                    <td class="p-2 border">Potongan Pajak PPh21</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount deduction" data-label="potongan_pph21"></td>
                </tr>
                <tr>
                    <td class="p-2 border">Dll</td>
                    <td class="p-2 border"><input type="number" min="0" step="0.01" class="w-full amount income" data-label="dll"></td>
                    <td class="p-2 border"></td>
                    <td class="p-2 border"></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <td class="p-2 border font-semibold">Total Pendapatan</td>
                    <td class="p-2 border" id="total-income">-</td>
                    <td class="p-2 border font-semibold">Total Potongan</td>
                    <td class="p-2 border" id="total-deduction">-</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-6 text-right">
        <div class="inline-block text-center border p-4" style="min-width:220px;">
            <div class="text-sm text-slate-500">Total Penerimaan Bulan Ini</div>
            <div class="text-3xl font-bold" id="total-net">Rp. 0,00</div>
        </div>
    </div>

    <div class="mt-8 text-right text-sm text-slate-500">
        <div>Mengetahui</div>
        <div class="mt-6">HRD Management</div>
    </div>

    <div class="mt-8 flex justify-end">
        <button type="button" onclick="printPDF()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Cetak PDF
        </button>
    </div>
</div>

@push('scripts')
<script>
    function formatRupiah(value) {
        if (!isFinite(value)) return 'Rp. 0,00';
        const abs = Math.abs(value);
        const parts = abs.toFixed(2).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        const sign = value < 0 ? '-' : '';
        return sign + 'Rp. ' + parts.join(',');
    }

    function parseInputVal(el) {
        const v = parseFloat(el.value);
        return isNaN(v) ? 0 : v;
    }

    function recalcTotals() {
        const incomes = document.querySelectorAll('.amount.income');
        const deductions = document.querySelectorAll('.amount.deduction');
        let sumIncome = 0;
        let sumDeduction = 0;
        incomes.forEach(i => sumIncome += parseInputVal(i));
        deductions.forEach(d => sumDeduction += parseInputVal(d));

        document.getElementById('total-income').textContent = formatRupiah(sumIncome);
        document.getElementById('total-deduction').textContent = formatRupiah(sumDeduction);
        document.getElementById('total-net').textContent = formatRupiah(sumIncome - sumDeduction);
    }

    function getIncomeData() {
        const incomeData = {};
        document.querySelectorAll('.amount.income').forEach(el => {
            incomeData[el.dataset.label] = parseInputVal(el);
        });
        return incomeData;
    }

    function getDeductionData() {
        const deductionData = {};
        document.querySelectorAll('.amount.deduction').forEach(el => {
            deductionData[el.dataset.label] = parseInputVal(el);
        });
        return deductionData;
    }

    function printPDF() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("hrd.slip.pdf") }}';
        form.target = '_blank';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const data = {
            brand: '{{ $brand }}',
            employee_name: document.querySelector('[name="employee_name"]')?.value || '',
            department: document.querySelector('[name="department"]')?.value || '',
            join_date: document.querySelector('[name="join_date"]')?.value || '',
            income: getIncomeData(),
            deduction: getDeductionData(),
            total_income: parseFloat(document.getElementById('total-income').textContent.replace(/[^\d,-]/g, '').replace(',', '.')),
            total_deduction: parseFloat(document.getElementById('total-deduction').textContent.replace(/[^\d,-]/g, '').replace(',', '.')),
            total_net: parseFloat(document.getElementById('total-net').textContent.replace(/[^\d,-]/g, '').replace(',', '.'))
        };

        for (const [key, value] of Object.entries(data)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = typeof value === 'object' ? JSON.stringify(value) : value;
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.amount').forEach(el => {
            el.addEventListener('input', recalcTotals);
        });
        recalcTotals();
    });
</script>
@endpush