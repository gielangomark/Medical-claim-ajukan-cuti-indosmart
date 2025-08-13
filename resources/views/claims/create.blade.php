@extends('layouts.app')

@section('title', 'Formulir Pengajuan Klaim Medis')

@section('content')
<div class="container mx-auto max-w-5xl">
    
    {{-- Inisialisasi state Alpine.js untuk modal --}}
    <div x-data="{ isModalOpen: false }" @keydown.escape.window="isModalOpen = false">
        
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
            <div class="p-6 md:p-8">

                {{-- Notifikasi Sukses/Gagal --}}
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-6 flex gap-3" role="alert">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <div><strong class="font-semibold">Sukses!</strong> {{ session('success') }}</div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex gap-3" role="alert">
                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <div><strong class="font-semibold">Gagal!</strong> {{ session('error') }}</div>
                    </div>
                @endif

                {{-- Header Halaman --}}
                <div class="pb-6 mb-8 border-b border-slate-200">
                    <h1 class="text-3xl font-bold text-slate-800">Formulir Pengajuan Klaim</h1>
                    <p class="mt-2 text-slate-500">Isi semua detail yang diperlukan untuk mengajukan klaim biaya medis.</p>
                </div>

                {{-- Info Jatah Klaim --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-500 p-3 rounded-lg text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-700">Sisa Jatah Klaim Tahun Ini</h3>
                            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($remainingAllotment, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Periode Klaim --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="period_month_input" class="block text-sm font-medium text-slate-700 mb-2">Bulan Periode</label>
                        <select id="period_month_input" name="period_month" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            {{-- Opsi bulan bisa di-generate dari Controller --}}
                            <option>Desember</option>
                            <option>November</option>   
                            <option>Oktober</option>
                            <option>September</option>
                            <option>Agustus</option>
                            <option>Juli</option>
                            <option>Juni</option>
                            <option>Mei</option>
                            <option>April</option>
                            <option>Maret</option> 
                            <option>Februari</option>
                            <option>Januari</option>
                        </select>
                    </div>
                    <div>
                        <label for="period_year_input" class="block text-sm font-medium text-slate-700 mb-2">Tahun Periode</label>
                        <input type="number" id="period_year_input" name="period_year" value="{{ $claim->period_year }}" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                {{-- Rincian Klaim --}}
                <div>
                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-4">
                        <h2 class="text-xl font-bold text-slate-800">Rincian Transaksi</h2>
                        <button @click="isModalOpen = true" type="button" class="flex items-center justify-center gap-2 w-full md:w-auto px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Tambah Rincian
                        </button>
                    </div>

                    {{-- Tabel Rincian --}}
                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="min-w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="p-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                    <th class="p-3 text-left text-xs font-semibold text-slate-500 uppercase">Keterangan</th>
                                    <th class="p-3 text-right text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                                    <th class="p-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($claim->details as $detail)
                                    <tr class="hover:bg-slate-50">
                                        <td class="p-3 text-sm text-slate-600">{{ \Carbon\Carbon::parse($detail->transaction_date)->format('d/m/Y') }}</td>
                                        <td class="p-3 text-sm text-slate-600">{{ $detail->description }}</td>
                                        <td class="p-3 text-right text-sm font-semibold text-slate-800">Rp {{ number_format($detail->amount, 0, ',', '.') }}</td>
                                        <td class="p-3 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if($detail->proof_file_path)
                                                <a href="{{ asset('storage/' . $detail->proof_file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Lihat Bukti">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                @endif
                                                <form action="{{ route('claims.details.destroy', $detail) }}" method="POST" onsubmit="return confirm('Hapus rincian ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus Rincian">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-slate-500">
                                            <p>Belum ada rincian klaim yang ditambahkan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Footer Kartu (Total & Aksi Final) --}}
            <div class="p-6 md:p-8 bg-slate-50 border-t border-slate-200 rounded-b-2xl">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="w-full md:w-auto text-center md:text-left">
                        <p class="text-sm text-slate-500">Total Klaim Diajukan</p>
                        <p id="total-claim-display" class="text-3xl font-bold text-slate-800">Rp {{ number_format($claim->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-full md:w-auto flex flex-col md:flex-row items-center gap-4">
                        <a href="{{ route('dashboard') }}" class="w-full md:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 transition">Simpan & Tutup</a>
                        <button type="button" id="submit-main-form-btn" class="w-full md:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Ajukan Sekarang
                        </button>
                    </div>
                </div>
            </div>

            {{-- Form Tersembunyi untuk Aksi Final --}}
            <form id="main-claim-form" action="{{ route('claims.update', $claim) }}" method="POST" class="hidden">
                @csrf
                @method('PUT')
            </form>
        </div>
        
        {{-- Modal untuk Tambah Rincian --}}
        <div x-show="isModalOpen" x-cloak class="fixed inset-0 bg-black/60 z-50 flex justify-center items-center p-4 backdrop-blur-sm"
             x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="isModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-2xl"
                 x-show="isModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800">Tambah Rincian Klaim</h2>
                </div>

                <form action="{{ route('claims.details.store', $claim) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_transaksi" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Transaksi</label>
                                <input type="date" id="tanggal_transaksi" name="transaction_date" required class="w-full p-2.5 bg-slate-100 border border-slate-300 rounded-md">
                            </div>
                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-slate-700 mb-1">Jumlah</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">Rp</span>
                                    <input type="number" id="jumlah" name="amount" required placeholder="50000" class="w-full p-2.5 pl-9 bg-slate-100 border border-slate-300 rounded-md">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                            <input type="text" id="keterangan" name="description" required placeholder="Contoh: Konsultasi dokter" class="w-full p-2.5 bg-slate-100 border border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label for="nama_pasien" class="block text-sm font-medium text-slate-700 mb-1">Pasien</label>
                            @if(count($patientOptions) <= 1)
                                <input type="text" id="nama_pasien" name="patient_name" value="{{ Auth::user()->name }}" readonly class="w-full p-2.5 bg-slate-200 text-slate-500 border border-slate-300 rounded-md cursor-not-allowed">
                            @else
                                <select id="nama_pasien" name="patient_name" required class="w-full p-2.5 bg-slate-100 border border-slate-300 rounded-md">
                                    <option value="">-- Pilih Pasien --</option>
                                    @foreach ($patientOptions as $option)
                                        <option value="{{ $option['name'] }}">{{ $option['name'] }} ({{ $option['relationship'] }})</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div>
                            <label for="file_bukti" class="block text-sm font-medium text-slate-700 mb-1">Upload Bukti (Opsional) (PDF, JPG, PNG)</label>
                            <input type="file" id="file_bukti" name="proof_file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-200 rounded-b-2xl flex justify-end gap-4">
                        <button @click="isModalOpen = false" type="button" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-100">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Simpan Rincian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        function updateTotalDisplay() {
            let total = 0;
            document.querySelectorAll('tbody tr:not(.empty-state)').forEach(row => {
                const amountCell = row.querySelector('td:nth-child(3)'); // Kolom jumlah
                if (amountCell) {
                    const amount = parseInt(amountCell.textContent.replace(/[^\d]/g, '')) || 0;
                    total += amount;
                }
            });
            const totalDisplay = document.getElementById('total-claim-display');
            if(totalDisplay) {
                totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }
        
        // --- Event Listener untuk tombol "Ajukan Sekarang" ---
        const submitMainFormBtn = document.getElementById('submit-main-form-btn');
        if (submitMainFormBtn) {
            submitMainFormBtn.addEventListener('click', function() {
                const mainForm = document.getElementById('main-claim-form');
                if (!mainForm) return;

                const periodMonth = document.getElementById('period_month_input').value;
                const periodYear = document.getElementById('period_year_input').value;

                // Hapus input sementara jika ada
                mainForm.querySelectorAll('.temp-input').forEach(e => e.remove());

                // Tambahkan input sementara ke form utama
                mainForm.insertAdjacentHTML('beforeend', `<input type="hidden" class="temp-input" name="period_month" value="${periodMonth}">`);
                mainForm.insertAdjacentHTML('beforeend', `<input type="hidden" class="temp-input" name="period_year" value="${periodYear}">`);
                
                if (confirm('Apakah Anda yakin ingin mengajukan klaim ini? Pastikan semua rincian sudah benar.')) {
                    mainForm.submit();
                } else {
                    mainForm.querySelectorAll('.temp-input').forEach(e => e.remove());
                }
            });
        }
    });
</script>
@endpush