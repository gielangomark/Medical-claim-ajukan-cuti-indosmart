<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dasbor') - Medical Claim</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100">

    {{-- Header Navigation dengan Alpine.js untuk state management --}}
    <header x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center h-auto sm:h-16 gap-3 sm:gap-0">
                
                {{-- Logo --}}
        <div class="flex-shrink-0">
                    <a href="{{ route('dashboard') }}">
            <img src="{{ asset('indosmart-update.png') }}" alt="Logo Indosmart" class="h-10 sm:h-14 md:h-20 lg:h-28 w-auto max-w-full" style="object-fit:contain;" onerror="this.onerror=null;this.src='https://placehold.co/200x60/003366/FFFFFF?text=INDOSMART';">
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <nav class="hidden md:flex items-center space-x-4">
                    @if(optional(Auth::user())->department === 'hrd')
                        <a href="{{ route('hrd.claims.index') }}" class="text-sm font-medium text-white bg-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors" title="Kembali ke Dasbor HRD">
                            Dasbor HRD
                        </a>
                    @endif
                    <x-notification-bell />
                    <div class="flex items-center space-x-2 bg-slate-100 p-2 rounded-lg">
                        <p class="text-sm font-semibold text-slate-800">{{ optional(Auth::user())->name ?? 'Tamu' }}</p>
                        <p class="text-xs text-slate-500 font-semibold">({{ ucfirst(optional(Auth::user())->department ?? 'N/A') }})</p>
                    </div>
                    @if(Auth::check())
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white font-semibold py-2 px-4 rounded-lg text-sm flex items-center space-x-2 hover:bg-red-700 transition" title="Logout">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    @endif
                </nav>

                {{-- BAGIAN YANG DIPERBARUI: Ikon di sisi kanan untuk mobile --}}
                <div class="flex items-center space-x-2 sm:hidden">
                    {{-- Notifikasi sekarang ada di sini untuk mobile --}}
                    <x-notification-bell />
                    
                    {{-- Tombol Hamburger --}}
                    <button @click="open = !open" 
                            class="text-slate-700 hover:text-blue-600 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            aria-controls="mobile-menu" 
                            :aria-expanded="open.toString()">
                        <span class="sr-only">Buka menu utama</span>
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu (dikontrol oleh Alpine.js) --}}
        <div id="mobile-menu" 
             x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="sm:hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                {{-- BAGIAN YANG DIPERBARUI: Info user & logout dipindahkan ke bawah --}}
                @if(optional(Auth::user())->department === 'hrd')
                    <a href="{{ route('hrd.claims.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">Dasbor HRD</a>
                @endif
                
                {{-- Info user dan tombol logout di bagian bawah menu dropdown --}}
                <div class="border-t border-gray-100 pt-3 mt-3">
                    <div class="flex items-center justify-between px-2 py-2">
                        <span class="font-medium text-slate-700">{{ optional(Auth::user())->name ?? 'Tamu' }}</span>
                        @if(Auth::check())
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm bg-red-100 text-red-700 font-semibold py-1 px-3 rounded-md">Logout</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="bg-slate-100">
        <div class="container mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
    
</body>
</html>