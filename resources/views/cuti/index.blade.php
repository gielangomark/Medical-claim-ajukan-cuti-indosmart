@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Pengajuan Cuti Saya</h1>
            <div class="flex items-center gap-4">
                @if(isset($remaining))
                    <span class="chip-accent">Sisa Cuti: <strong>{{ $remaining }}</strong> hari</span>
                @endif
                <a href="{{ route('pengajuan.cuti.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Ajukan Cuti</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm text-slate-600">Tanggal Mulai</th>
                        <th class="py-3 px-4 text-left text-sm text-slate-600">Tanggal Selesai</th>
                        <th class="py-3 px-4 text-left text-sm text-slate-600">Jenis</th>
                        <th class="py-3 px-4 text-left text-sm text-slate-600">Status</th>
                        <th class="py-3 px-4 text-right text-sm text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($cuti as $c)
                        <tr>
                            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($c->tanggal_mulai)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($c->tanggal_selesai)->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-sm">{{ ucfirst($c->jenis_cuti) }}</td>
                            <td class="py-3 px-4 text-sm">{{ ucfirst($c->status) }}</td>
                            <td class="py-3 px-4 text-sm text-right"><a href="{{ route('pengajuan.cuti.show', $c) }}" class="text-blue-600">Lihat</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500">Belum ada pengajuan cuti.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $cuti->links() }}
        </div>
    </div>
</div>
@endsection
