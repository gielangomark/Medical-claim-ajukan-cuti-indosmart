@extends('layouts.slip')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Slip Gaji</h1>
        <p class="text-sm text-gray-600">Pilih jenis slip gaji yang ingin ditampilkan</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Indosmart Card -->
        <div class="bg-white border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <img src="{{ asset('indosmart_logo.png') }}" alt="Indosmart" class="h-12 w-auto mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Indosmart</h3>
                <p class="text-sm text-gray-600 mt-2">Generate slip gaji untuk karyawan Indosmart</p>
                <div class="mt-4">
                    <a href="{{ route('hrd.slip.show', 'indosmart') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Pilih Format Ini
                    </a>
                </div>
            </div>
        </div>

        <!-- Smarttech Card -->
        <div class="bg-white border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <img src="{{ asset('smarttech_logo.png') }}" alt="Smarttech" class="h-12 w-auto mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Smarttech</h3>
                <p class="text-sm text-gray-600 mt-2">Generate slip gaji untuk karyawan Smarttech</p>
                <div class="mt-4">
                    <a href="{{ route('hrd.slip.show', 'smarttech') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Pilih Format Ini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection