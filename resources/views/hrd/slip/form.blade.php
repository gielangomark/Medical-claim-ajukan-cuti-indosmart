@extends('layouts.slip')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50 to-blue-50 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header Navigation -->
        <div class="mb-8 flex justify-between items-center">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Slip Gaji {{ ucfirst($brand) }}
                </h1>
                <div class="w-20 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 mt-2 rounded-full"></div>
            </div>
            
            <a href="{{ route('hrd.slip.index') }}" class="group flex items-center space-x-3 bg-white hover:bg-slate-50 text-slate-600 hover:text-slate-900 px-6 py-3 rounded-2xl shadow-lg border border-slate-200/60 transition-all duration-300">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium">Kembali ke Pilihan Format</span>
            </a>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden">
            <!-- Logo and Title Section -->
            <div class="text-center py-12 px-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 border-b border-slate-200/60">
                <div class="relative inline-block">
                    @if($brand === 'smarttech')
                        <div class="bg-white rounded-2xl p-6 shadow-lg inline-block">
                            <img src="{{ asset('smarttech_logo.png') }}" alt="Logo Smarttech" class="h-20 w-auto object-contain">
                        </div>
                        <div class="absolute -top-2 -right-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            SMARTTECH
                        </div>
                    @else
                        <div class="bg-white rounded-2xl p-6 shadow-lg inline-block">
                            <img src="{{ asset('indosmart_logo.png') }}" alt="Logo Indosmart" class="h-20 w-auto object-contain">
                        </div>
                        <div class="absolute -top-2 -right-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            INDOSMART
                        </div>
                    @endif
                </div>
                
                <h2 class="text-4xl font-bold mt-6 bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Slip Gaji
                </h2>
                <div class="flex justify-center mt-4">
                    <div class="w-32 h-1 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 rounded-full"></div>
                </div>
            </div>

            <div class="p-8">
                <!-- Company and Employee Info -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    <!-- Company Info -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100/60">
                        <div class="flex items-center mb-4">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl p-3 mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-800">Informasi Perusahaan</h3>
                        </div>
                        <div class="space-y-2 text-slate-600">
                            <div class="font-bold text-slate-800 text-lg">
                                @if($brand === 'smarttech')
                                    PT. Smarttech Global Komunikasi
                                @else
                                    PT. Indosmart Komunikasi Global
                                @endif
                            </div>
                            <div class="text-sm">Plaza Marin Lt. 12 Jl. Jend. Sudirman</div>
                            <div class="text-sm">Setiabudi Jakarta Selatan</div>
                        </div>
                    </div>

                    <!-- Employee Info -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100/60">
                        <div class="flex items-center mb-4">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-3 mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-800">Informasi Karyawan</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-2">Nama / NIK</label>
                                <input type="text" name="employee_name" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 shadow-sm" placeholder="Masukkan nama dan NIK">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-2">Dept / Jabatan</label>
                                <input type="text" name="department" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 shadow-sm" placeholder="Masukkan departemen dan jabatan">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-2">Tgl Mulai Bekerja</label>
                                <input type="text" name="join_date" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 shadow-sm" placeholder="DD/MM/YYYY">
                            </div>
                            <div class="pt-2 flex items-center">
                                <svg class="w-5 h-5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-slate-600">Periode Gaji: </span>
                                <span class="text-sm font-bold text-purple-600 ml-1">{{ now()->format('Y/m') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Salary Table -->
                <div class="bg-gradient-to-r from-slate-50 to-indigo-50 rounded-2xl p-6 border border-slate-200/60 mb-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl p-3 mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 0v8a2 2 0 01-2 2H9m6-10a2 2 0 00-2-2H7a2 2 0 00-2 2v6a2 2 0 002 2h2m6-10V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">Detail Penggajian</h3>
                    </div>

                    <div class="overflow-hidden rounded-xl shadow-lg">
                        <table class="w-full bg-white">
                            <thead>
                                <tr class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                                    <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Pendapatan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Jumlah (Rp)</th>
                                    <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Potongan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-sm uppercase tracking-wider">Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr class="hover:bg-indigo-50/50 transition-all duration-200">
                                    <td class="px-6 py-4 font-medium text-slate-700">Gaji Pokok</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent bg-emerald-50/50 transition-all duration-200" data-label="gaji_pokok">
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-700">Potongan Absen</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount deduction px-3 py-2 border border-rose-200 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-transparent bg-rose-50/50 transition-all duration-200" data-label="potongan_absen">
                                    </td>
                                </tr>
                                <tr class="hover:bg-indigo-50/50 transition-all duration-200">
                                    <td class="px-6 py-4 font-medium text-slate-700">Tunjangan Jabatan</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent bg-emerald-50/50 transition-all duration-200" data-label="tunjangan_jabatan">
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-700">Potongan Datang Terlambat</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount deduction px-3 py-2 border border-rose-200 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-transparent bg-rose-50/50 transition-all duration-200" data-label="potongan_terlambat">
                                    </td>
                                </tr>
                                <tr class="hover:bg-indigo-50/50 transition-all duration-200">
                                    <td class="px-6 py-4 font-medium text-slate-700">Tunjangan Fungsional</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent bg-emerald-50/50 transition-all duration-200" data-label="tunjangan_fungsional">
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-700">Potongan BPJS</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount deduction px-3 py-2 border border-rose-200 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-transparent bg-rose-50/50 transition-all duration-200" data-label="potongan_bpjs">
                                    </td>
                                </tr>
                                <tr class="hover:bg-indigo-50/50 transition-all duration-200">
                                    <td class="px-6 py-4 font-medium text-slate-700">Tunjangan Transport</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent bg-emerald-50/50 transition-all duration-200" data-label="tunjangan_transport">
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-700">Potongan Pajak PPh21</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount deduction px-3 py-2 border border-rose-200 rounded-lg focus:ring-2 focus:ring-rose-400 focus:border-transparent bg-rose-50/50 transition-all duration-200" data-label="potongan_pph21">
                                    </td>
                                </tr>
                                <tr class="hover:bg-indigo-50/50 transition-all duration-200">
                                    <td class="px-6 py-4 font-medium text-slate-700">Lain-lain</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent bg-emerald-50/50 transition-all duration-200" data-label="dll">
                                    </td>
                                    <td class="px-6 py-4"></td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gradient-to-r from-slate-100 to-indigo-100 border-t-2 border-slate-200">
                                    <td class="px-6 py-4 font-bold text-slate-800 text-lg">Total Pendapatan</td>
                                    <td class="px-6 py-4 font-bold text-emerald-600 text-lg" id="total-income">-</td>
                                    <td class="px-6 py-4 font-bold text-slate-800 text-lg">Total Potongan</td>
                                    <td class="px-6 py-4 font-bold text-rose-600 text-lg" id="total-deduction">-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Total Net -->
                <div class="flex justify-center mb-10">
                    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white rounded-2xl p-10 shadow-2xl transform hover:scale-105 transition-all duration-300 max-w-md w-full">
                        <div class="text-center">
                            <div class="bg-white/20 rounded-full p-3 inline-block mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="text-white/90 text-sm font-medium uppercase tracking-wide mb-3">Total Penerimaan Bulan Ini</div>
                            <div class="text-4xl font-bold mb-3" id="total-net">Rp. 0,00</div>
                            <div class="w-24 h-1 bg-white/30 mx-auto rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Signature and Action -->
                <div class="flex justify-between items-end border-t border-slate-200 pt-8">
                    <!-- Signature -->
                    <div class="text-right">
                        <div class="text-slate-500 text-sm mb-2">Mengetahui</div>
                        <div class="w-48 h-20 border-b-2 border-slate-300 mb-2"></div>
                        <div class="font-bold text-slate-700 text-lg">HRD Management</div>
                    </div>

                    <!-- Print Button -->
                    <button type="button" onclick="printPDF()" class="group bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-2xl flex items-center space-x-3 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-semibold text-lg">Cetak PDF</span>
                    </button>
                </div>
            </div>
        </div>
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