@extends('layouts.hrd')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
        <div class="max-w-5xl mx-auto">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 p-6 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold text-white">Generator Slip Gaji</h1>
                                    <p class="text-blue-100 mt-1">Sistem Penggajian Digital</p>
                                </div>
                            </div>
                            
                            <button type="button" onclick="printPDF()" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-all duration-300 text-white px-6 py-3 rounded-xl flex items-center space-x-3 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="font-semibold">Cetak PDF</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200/60 overflow-hidden">
                @php
                    $logo = match($brand ?? 'indosmart') {
                        'smarttech' => asset('smarttech_logo.png'),
                        default => asset('Indosmart_logo.png'),
                    };
                    $period = \Carbon\Carbon::now()->format('Y/m');
                @endphp

                <!-- Logo and Title Section -->
                <div class="text-center py-10 px-6 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100">
                    <div class="inline-block bg-white rounded-2xl p-4 shadow-lg mb-6">
                        <img src="{{ $logo }}" alt="Logo" class="h-16 w-auto object-contain" onerror="this.onerror=null;this.src='https://placehold.co/180x60/3B82F6/FFFFFF?text=LOGO'">
                    </div>
                    <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Slip Gaji</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto mt-4 rounded-full"></div>
                </div>

                <div class="p-8">
                    <!-- Company and Employee Info -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                        <!-- Company Info -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-500 rounded-xl p-2 mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-700">Informasi Perusahaan</h3>
                            </div>
                            <div class="space-y-2 text-slate-600">
                                <div class="font-semibold text-slate-800">PT. Indosmart Komunikasi Global</div>
                                <div class="text-sm">Plaza Marin Lt. 12 Jl. Jend. Sudirman</div>
                                <div class="text-sm">Setiabudi Jakarta Selatan</div>
                            </div>
                        </div>

                        <!-- Employee Info -->
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                            <div class="flex items-center mb-4">
                                <div class="bg-purple-500 rounded-xl p-2 mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-700">Informasi Karyawan</h3>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">Nama / NIK</label>
                                    <input type="text" name="employee_name" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" placeholder="Masukkan nama dan NIK">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">Dept / Jabatan</label>
                                    <input type="text" name="department" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" placeholder="Masukkan departemen dan jabatan">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">Tgl Mulai Bekerja</label>
                                    <input type="text" name="join_date" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" placeholder="DD/MM/YYYY">
                                </div>
                                <div class="pt-2">
                                    <span class="text-sm font-medium text-slate-600">Periode Gaji: </span>
                                    <span class="text-sm font-semibold text-purple-600">{{ $period }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Salary Table -->
                    <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-2xl p-6 border border-slate-200">
                        <div class="flex items-center mb-6">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl p-2 mr-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 0v8a2 2 0 01-2 2H9m6-10a2 2 0 00-2-2H7a2 2 0 00-2 2v6a2 2 0 002 2h2m6-10V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-800">Detail Penggajian</h3>
                        </div>

                        <div class="overflow-hidden rounded-xl shadow-lg">
                            <table class="w-full bg-white"> 
                                <thead>
                                    <tr class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                                        <th class="px-4 py-4 text-left font-semibold">Pendapatan</th>
                                        <th class="px-4 py-4 text-left font-semibold">Jumlah (Rp)</th>
                                        <th class="px-4 py-4 text-left font-semibold">Potongan</th>
                                        <th class="px-4 py-4 text-left font-semibold">Jumlah (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                        <td class="px-4 py-4 font-medium text-slate-700">Gaji Pokok</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent bg-green-50" data-label="gaji_pokok">
                                        </td>
                                        <td class="px-4 py-4 font-medium text-slate-700">Potongan Absen</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount deduction px-3 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-transparent bg-red-50" data-label="potongan_absen">
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                        <td class="px-4 py-4 font-medium text-slate-700">Tunjangan Jabatan</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent bg-green-50" data-label="tunjangan_jabatan">
                                        </td>
                                        <td class="px-4 py-4 font-medium text-slate-700">Potongan Datang Terlambat</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount deduction px-3 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-transparent bg-red-50" data-label="potongan_terlambat">
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                        <td class="px-4 py-4 font-medium text-slate-700">Tunjangan Fungsional</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent bg-green-50" data-label="tunjangan_fungsional">
                                        </td>
                                        <td class="px-4 py-4 font-medium text-slate-700">Potongan BPJS</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount deduction px-3 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-transparent bg-red-50" data-label="potongan_bpjs">
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                        <td class="px-4 py-4 font-medium text-slate-700">Tunjangan Transport</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent bg-green-50" data-label="tunjangan_transport">
                                        </td>
                                        <td class="px-4 py-4 font-medium text-slate-700">Potongan Pajak PPh21</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount deduction px-3 py-2 border border-red-200 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-transparent bg-red-50" data-label="potongan_pph21">
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                        <td class="px-4 py-4 font-medium text-slate-700">Lain-lain</td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" step="0.01" class="w-full amount income px-3 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-transparent bg-green-50" data-label="dll">
                                        </td>
                                        <td class="px-4 py-4"></td>
                                        <td class="px-4 py-4"></td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gradient-to-r from-slate-100 to-blue-100">
                                    <tr>
                                        <td class="px-4 py-4 font-bold text-slate-800 text-lg">Total Pendapatan</td>
                                        <td class="px-4 py-4 font-bold text-green-600 text-lg" id="total-income">-</td>
                                        <td class="px-4 py-4 font-bold text-slate-800 text-lg">Total Potongan</td>
                                        <td class="px-4 py-4 font-bold text-red-600 text-lg" id="total-deduction">-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Total Net -->
                    <div class="mt-10 flex justify-center">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl p-8 shadow-2xl transform hover:scale-105 transition-all duration-300">
                            <div class="text-center">
                                <div class="text-blue-100 text-sm font-medium uppercase tracking-wide mb-3">Total Penerimaan Bulan Ini</div>
                                <div class="text-4xl font-bold mb-2" id="total-net">Rp 0,00</div>
                                <div class="w-20 h-1 bg-white/30 mx-auto rounded-full"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Signature -->
                    <div class="mt-12 text-right border-t border-slate-200 pt-8">
                        <div class="inline-block">
                            <div class="text-slate-500 text-sm mb-2">Mengetahui</div>
                            <div class="w-40 h-20 border-b-2 border-slate-300 mb-2"></div>
                            <div class="font-semibold text-slate-700">HRD Management</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // format number to Indonesian Rupiah style
    function formatRupiah(value){
        if (!isFinite(value)) return 'Rp 0,00';
        const abs = Math.abs(value);
        const parts = abs.toFixed(2).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        const sign = value < 0 ? '-' : '';
        return sign + 'Rp ' + parts.join(',');
    }

    function parseInputVal(el){
        const v = parseFloat(el.value);
        return isNaN(v) ? 0 : v;
    }

    function recalcTotals(){
        const incomes = document.querySelectorAll('.amount.income');
        const deductions = document.querySelectorAll('.amount.deduction');
        let sumIncome = 0; let sumDeduction = 0;
        incomes.forEach(i => sumIncome += parseInputVal(i));
        deductions.forEach(d => sumDeduction += parseInputVal(d));

        const totalIncomeEl = document.getElementById('total-income');
        const totalDedEl = document.getElementById('total-deduction');
        const totalNetEl = document.getElementById('total-net');

        totalIncomeEl.textContent = formatRupiah(sumIncome);
        totalDedEl.textContent = formatRupiah(sumDeduction);

        const net = sumIncome - sumDeduction;
        totalNetEl.textContent = formatRupiah(net);
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
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add data
        const data = {
            brand: '{{ $brand ?? "indosmart" }}',
            employee_name: document.querySelector('[name="employee_name"]')?.value || '',
            department: document.querySelector('[name="department"]')?.value || '',
            join_date: document.querySelector('[name="join_date"]')?.value || '',
            income: getIncomeData(),
            deduction: getDeductionData(),
            total_income: parseFloat(document.getElementById('total-income').textContent.replace(/[^\d,-]/g, '').replace(',', '.')),
            total_deduction: parseFloat(document.getElementById('total-deduction').textContent.replace(/[^\d,-]/g, '').replace(',', '.')),
            total_net: parseFloat(document.getElementById('total-net').textContent.replace(/[^\d,-]/g, '').replace(',', '.'))
        };

        // Add all data as hidden fields
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

    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.amount').forEach(el => {
            el.addEventListener('input', recalcTotals);
        });
        // initial calc
        recalcTotals();
    });
</script>
@endpush