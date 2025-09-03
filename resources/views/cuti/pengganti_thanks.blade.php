@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6 text-center">
        <h1 class="text-2xl font-bold mb-4">Terima Kasih</h1>
        <p class="mb-4">Tanggapan Anda telah tercatat. HRD dan pemohon telah diberi tahu.</p>
        <a href="{{ url('/') }}" class="inline-block mt-2 px-4 py-2 bg-slate-700 text-white rounded">Kembali ke Beranda</a>
    </div>
</div>
@endsection
