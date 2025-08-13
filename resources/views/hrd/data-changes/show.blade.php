@extends('layouts.hrd')

@section('title', 'Proses Perubahan Data')

@section('content')

    <div class="mb-6">
        <a href="{{ route('hrd.data-changes.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Pengajuan</a>
    </div>

    <!-- Details Container -->
    <div class="bg-white rounded-2xl shadow-lg">
        <!-- Header -->
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-2xl font-bold text-slate-800">Detail Pengajuan Perubahan Data</h2>
            <p class="text-slate-500">Karyawan: <span class="font-semibold text-slate-700">{{ $request->user->name }}</span> (NIK: {{ $request->user->nik }})</p>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- New Data Summary -->
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">Data yang Diajukan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-lg">
                    <div>
                        <p class="text-sm text-slate-500">Jenis Pengajuan</p>
                        <p class="font-semibold text-slate-800">{{ ucwords(str_replace('_', ' ', $request->request_type)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Status Baru</p>
                        <p class="font-semibold text-slate-800">{{ ucfirst($request->new_data['marital_status']) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Nama Pasangan</p>
                        <p class="font-semibold text-slate-800">{{ $request->new_data['spouse_name'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tanggal Lahir Pasangan</p>
                        <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($request->new_data['spouse_dob'])->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Proof Document -->
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">Dokumen Pendukung</h3>
                @if ($request->proof_document_path)
                    <a href="{{ asset('storage/' . $request->proof_document_path) }}" target="_blank" class="inline-flex items-center space-x-2 text-blue-600 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                        <span>Lihat Dokumen Bukti</span>
                    </a>
                @else
                    <p class="text-sm text-slate-500 italic">Karyawan tidak mengirim foto apapun.</p>
                @endif
            </div>

            <!-- Action Section -->
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">Tindakan Persetujuan</h3>
                <div id="rejection-form" class="hidden space-y-2">
                    <form action="{{ route('hrd.data-changes.destroy', $request->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <label for="rejection_reason" class="text-sm font-medium text-slate-700">Alasan Penolakan (Wajib diisi)</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" placeholder="Contoh: Dokumen tidak valid atau tidak terbaca." class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 transition"></textarea>
                        <button type="submit" class="mt-2 bg-red-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:bg-red-700 transition">Kirim Penolakan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer with Action Buttons -->
        <div class="p-6 bg-slate-50 rounded-b-2xl flex justify-end items-center gap-4">
            <button id="reject-btn" class="bg-red-100 text-red-700 font-semibold py-2 px-5 rounded-lg hover:bg-red-200 transition">Tolak</button>
            <form action="{{ route('hrd.data-changes.update', $request->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui perubahan data ini?');">
                @csrf
                @method('PUT')
                <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:bg-green-700 transition">Setujui Perubahan</button>
            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rejectBtn = document.getElementById('reject-btn');
        const rejectionForm = document.getElementById('rejection-form');

        if (rejectBtn) {
            rejectBtn.addEventListener('click', () => {
                // Toggle tampilan form penolakan
                rejectionForm.classList.toggle('hidden');
            });
        }
    });
</script>
@endsection
