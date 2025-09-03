@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Konfirmasi Permintaan Pengganti</h1>

        <p class="text-sm text-slate-600 mb-4">Anda diminta menjadi pengganti untuk cuti atas nama <strong>{{ $cuti->user->name }}</strong>.</p>

        <div class="grid grid-cols-1 gap-3 mb-4">
            <div>
                <p class="text-sm text-slate-500">Tanggal Mulai</p>
                <p class="font-semibold">{{ optional($cuti->tanggal_mulai)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Tanggal Selesai</p>
                <p class="font-semibold">{{ optional($cuti->tanggal_selesai)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500">Durasi</p>
                <p class="font-semibold">{{ $cuti->duration }} hari</p>
            </div>
        </div>

        <form method="POST" action="{{ route('cuti.pengganti.respond', ['cuti' => $cuti->id, 'signature' => request()->get('signature')]) }}">
            @csrf
            <div class="flex gap-3">
                <button name="action" value="accept" type="submit" class="w-1/2 inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white rounded">Saya Bersedia</button>
                <button name="action" value="decline" type="submit" class="w-1/2 inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded">Tidak Bersedia</button>
            </div>
        </form>

        <p class="text-xs text-slate-400 mt-4">Link ini hanya dapat digunakan dari email dan memiliki tanda tangan digital untuk keamanan.</p>
    </div>
</div>
@endsection
