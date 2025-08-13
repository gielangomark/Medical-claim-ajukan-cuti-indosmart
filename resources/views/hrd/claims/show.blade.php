@extends('layouts.hrd')

@section('title', 'Detail Pengajuan Klaim')

@section('content')
<div x-data="{ rejectionModalOpen: false }" @keydown.escape.window="rejectionModalOpen = false">

    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row justify-between items-start mb-6 gap-4">
        <div>
            <a href="{{ route('hrd.claims.index') }}" class="flex items-center gap-2 text-sm text-blue-600 hover:underline font-medium mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Klaim
            </a>
            <h1 class="text-3xl font-bold text-slate-800">Detail Pengajuan Klaim</h1>
        </div>

        {{-- Status Badge Dinamis --}}
        <div>
            @if ($claim->status == 'pending_approval')
                <div class="flex items-center gap-2 bg-amber-100 text-amber-800 font-semibold text-sm py-2 px-4 rounded-full">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>
                    <span>Menunggu Persetujuan</span>
                </div>
            @elseif ($claim->status == 'approved')
                <div class="flex items-center gap-2 bg-green-100 text-green-800 font-semibold text-sm py-2 px-4 rounded-full">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                    <span>Disetujui</span>
                </div>
            @elseif ($claim->status == 'rejected')
                <div class="flex items-center gap-2 bg-red-100 text-red-800 font-semibold text-sm py-2 px-4 rounded-full">
                     <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                    <span>Ditolak</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Layout Utama (2 Kolom) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Kolom Kiri: Info & Aksi --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-3">Informasi Karyawan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Nama Lengkap:</span>
                        <span class="font-semibold text-slate-700 text-right">{{ $claim->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">NIK:</span>
                        <span class="font-semibold text-slate-700">{{ $claim->user->nik }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg">
                 <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-3">Ringkasan Klaim</h3>
                 <div class="space-y-4">
                     <div>
                        <p class="text-sm text-slate-500">Periode Klaim</p>
                        <p class="text-lg font-semibold text-slate-700">{{ $claim->period_month }} {{ $claim->period_year }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Diajukan</p>
                        <p class="font-bold text-3xl text-blue-600">Rp {{ number_format($claim->total_amount, 0, ',', '.') }}</p>
                    </div>
                 </div>
            </div>

            {{-- Tombol Aksi (Hanya tampil jika status 'pending_approval') --}}
            @if ($claim->status == 'pending_approval')
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Tindakan Persetujuan</h3>
                    <div class="flex flex-col gap-3">
                        {{-- Form untuk menyetujui --}}
                        <form action="{{ route('hrd.claims.update', $claim) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI klaim ini?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-green-700 transition">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg>
                                Setujui
                            </button>
                        </form>
                        {{-- Tombol Tolak (membuka modal) --}}
                        <button @click="rejectionModalOpen = true" type="button" class="w-full flex items-center justify-center gap-2 bg-red-100 text-red-700 font-semibold py-3 px-6 rounded-lg hover:bg-red-200 transition">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                            Tolak
                        </button>
                    </div>
                </div>
            @endif

        </div>

        {{-- Kolom Kanan: Rincian Transaksi --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-lg h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Rincian Transaksi</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Keterangan</th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Jumlah</th>
                            <th class="py-3 px-4 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($claim->details as $detail)
                            <tr>
                                <td class="py-4 px-4 text-sm text-slate-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($detail->transaction_date)->translatedFormat('d F Y') }}</td>
                                <td class="py-4 px-4 text-sm text-slate-600">{{ $detail->description }}</td>
                                <td class="py-4 px-4 text-sm font-medium text-slate-800 text-right whitespace-nowrap">Rp {{ number_format($detail->amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 text-center">
                                    @if($detail->proof_file_path)
                                        <a href="{{ Storage::url($detail->proof_file_path) }}" target="_blank" class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 hover:bg-blue-100 text-sm font-semibold py-1 px-3 rounded-full">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm.5 4.5a.5.5 0 000 1h11a.5.5 0 000-1h-11zM4.5 12a.5.5 0 000 1h11a.5.5 0 000-1h-11zM7 9.5a.5.5 0 01.5-.5h5a.5.5 0 010 1h-5a.5.5 0 01-.5-.5z" clip-rule="evenodd" /></svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center p-6 text-slate-500">Tidak ada rincian transaksi ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             {{-- Tampilkan Alasan Penolakan jika ada --}}
            @if ($claim->status == 'rejected' && $claim->rejection_reason)
                <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                    <p class="font-bold text-red-800">Alasan Penolakan:</p>
                    <p class="text-sm text-red-700 mt-1">{{ $claim->rejection_reason }}</p>
                </div>
            @endif
        </div>

    </div>

    {{-- Modal untuk Form Penolakan (Styling disesuaikan) --}}
    <div x-show="rejectionModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-60 backdrop-blur-sm">
        <div @click.away="rejectionModalOpen = false" x-show="rejectionModalOpen" x-transition class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-slate-800">Tolak Pengajuan Klaim</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-600 mb-5">Berikan alasan yang jelas mengapa pengajuan ini ditolak. Alasan ini akan tercatat dan dapat dilihat oleh karyawan.</p>
                
                <form action="{{ route('hrd.claims.update', $claim) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <div>
                        <label for="rejection_reason" class="sr-only">Alasan Penolakan</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition" placeholder="Contoh: Bukti pembayaran tidak valid atau tidak sesuai." required minlength="10"></textarea>
                    </div>
                    <div class="mt-6 flex justify-end gap-4">
                        <button @click="rejectionModalOpen = false" type="button" class="bg-slate-200 text-slate-800 font-semibold py-2 px-5 rounded-lg hover:bg-slate-300 transition">Batal</button>
                        <button type="submit" class="bg-red-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:bg-red-700 transition">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection