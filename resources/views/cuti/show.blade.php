@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('pengajuan.cuti.index') }}" class="text-sm text-blue-600 hover:underline mb-4 inline-block">&larr; Kembali ke daftar</a>

        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-4">Detail Pengajuan Cuti</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-500">Tanggal Mulai</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Tanggal Selesai</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Jenis Cuti</p>
                    <p class="font-semibold">{{ ucfirst($cuti->jenis_cuti) }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Durasi</p>
                    <p class="font-semibold">{{ $cuti->duration }} hari</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-sm text-slate-500">Alasan</p>
                <p class="mt-1 text-slate-700">{{ $cuti->alasan }}</p>
            </div>

            @if(!empty($cuti->dokumen_pendukung))
            <div class="mt-4">
                <p class="text-sm text-slate-500">Dokumen Pendukung</p>
                <a href="{{ Storage::url($cuti->dokumen_pendukung) }}" target="_blank" class="text-blue-600 hover:underline">Lihat / Unduh</a>
            </div>
            @endif

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-500">Status</p>
                    <p class="font-semibold">{{ ucfirst($cuti->status) }}</p>
                </div>
                @if($cuti->catatan)
                <div>
                    <p class="text-sm text-slate-500">Catatan HRD</p>
                    <p class="font-semibold">{{ $cuti->catatan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
