@extends('layouts.hrd')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold">Slip Gaji</h1>
                <p class="text-sm text-slate-500">Periode: --/----</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('hrd.slip.show', 'indosmart') }}" class="block p-6 border rounded hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Indosmart</h2>
                        <p class="text-sm text-slate-500">Tampilkan slip gaji dengan logo Indosmart</p>
                    </div>
                    <img src="{{ asset('Indosmart-Update.png') }}" alt="Indosmart" class="h-12 w-auto object-contain" onerror="this.onerror=null;this.src='https://placehold.co/120x40/003366/FFFFFF?text=INDOSMART'">
                </div>
            </a>

            <a href="{{ route('hrd.slip.show', ['brand' => 'smarttech']) }}" class="block p-6 border rounded hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Smarttech</h2>
                        <p class="text-sm text-slate-500">Tampilkan slip gaji dengan logo Smarttech</p>
                    </div>
                    <img src="{{ asset('smarttech_logo.png') }}" alt="Smarttech" class="h-12 w-auto object-contain" onerror="this.onerror=null;this.src='https://placehold.co/120x40/003366/FFFFFF?text=SMARTTECH'">
                </div>
            </a>
        </div>
    </div>
@endsection
