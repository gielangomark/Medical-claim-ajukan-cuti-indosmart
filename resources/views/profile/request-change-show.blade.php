@extends('layouts.app')

@section('title', 'Detail Pengajuan Perubahan Data')

@section('content')
    <div class="mb-6">
        <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Notifikasi</a>
    </div>

    <!-- Details Container -->
    <div class="bg-white rounded-2xl shadow-lg">
        <!-- Header -->
        <div class="p-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Detail Pengajuan Anda</h2>
                    <p class="text-slate-500">Diajukan pada: {{ $request->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    @php
                        $statusClass = '';
                        if ($request->status == 'approved') $statusClass = 'bg-green-100 text-green-800';
                        elseif ($request->status == 'rejected') $statusClass = 'bg-red-100 text-red-800';
                        else $statusClass = 'bg-yellow-100 text-yellow-800';
                    @endphp
                    <span class="px-3 py-1 rounded-full font-semibold text-sm {{ $statusClass }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
            </div>
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
                <a href="{{ asset('storage/' . $request->proof_document_path) }}" target="_blank" class="inline-flex items-center space-x-2 text-blue-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    <span>Lihat Dokumen Bukti</span>
                </a>
            </div>

            {{-- Tampilkan blok ini hanya jika pengajuan ditolak --}}
            @if ($request->status == 'rejected' && $request->rejection_reason)
                <div>
                    <h3 class="font-semibold text-slate-800 mb-2">Alasan Penolakan dari HRD</h3>
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg">
                        <p>{{ $request->rejection_reason }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
