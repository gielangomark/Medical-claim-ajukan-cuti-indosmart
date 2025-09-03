@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Portal Indosmart</h2>
            <p class="text-lg text-gray-600">Selamat datang, {{ auth()->user()->name }}</p>
        </div>

        @if(auth()->user()->department === 'hrd')
            {{-- Tampilan untuk HRD --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white/70 backdrop-blur-lg rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Medical
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('hrd.claims.index') }}" 
                           class="block px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 text-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                           Approval Medical Claim
                        </a>
                        <a href="{{ route('pengajuan.medical.index') }}" 
                           class="block px-6 py-3 bg-white text-blue-700 rounded-lg hover:bg-blue-50 border border-blue-200 transition-all duration-300 text-center shadow-sm hover:shadow">
                           Ajukan Medical Claim
                        </a>
                    </div>
                </div>

                <div class="bg-white/70 backdrop-blur-lg rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Cuti
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('hrd.cuti.index') }}" 
                           class="block px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 text-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                           Approval Cuti
                        </a>
                        <a href="{{ route('pengajuan.cuti.create') }}" 
                           class="block px-6 py-3 bg-white text-green-700 rounded-lg hover:bg-green-50 border border-green-200 transition-all duration-300 text-center shadow-sm hover:shadow">
                           Ajukan Cuti
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('hrd.users.index') }}" 
                   class="block w-full md:w-1/2 mx-auto px-6 py-4 bg-gradient-to-r from-gray-800 to-gray-900 text-white rounded-xl hover:from-gray-900 hover:to-black transition-all duration-300 text-center shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Manajemen User
                    </span>
                </a>
            </div>
        @else
            {{-- Tampilan untuk User biasa --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <a href="{{ route('pengajuan.medical.index') }}" 
                   class="group relative bg-white/70 backdrop-blur-lg rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 text-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-blue-700/10 rounded-2xl transform group-hover:scale-[1.02] transition-transform duration-300"></div>
                    <div class="relative">
                        <svg class="w-12 h-12 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-xl font-semibold text-gray-900">Medical Claim</span>
                    </div>
                </a>

                <a href="{{ route('pengajuan.cuti.create') }}" 
                   class="group relative bg-white/70 backdrop-blur-lg rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 text-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-600/10 to-green-700/10 rounded-2xl transform group-hover:scale-[1.02] transition-transform duration-300"></div>
                    <div class="relative">
                        <svg class="w-12 h-12 mx-auto mb-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xl font-semibold text-gray-900">Pengajuan Cuti</span>
                    </div>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
