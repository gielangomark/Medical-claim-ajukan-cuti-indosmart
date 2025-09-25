<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard HRD' }} - Medical Claim</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100" :class="{ 'overflow-hidden': sidebarOpen }">

    <div x-data="{ sidebarOpen: false }">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-30">
            <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center h-auto md:h-16 gap-3 md:gap-0">
                    <!-- Logo dan Navigasi Desktop -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('hrd.users.index') }}" class="flex-shrink-0">
                            <img src="{{ asset('indosmart-update.png') }}"
                                 alt="Logo Indosmart"
                                 class="h-10 md:h-20 lg:h-28 w-auto max-w-full"
                                 style="object-fit:contain;"
                                 onerror="this.onerror=null;this.src='https://placehold.co/150x40/003366/FFFFFF?text=INDOSMART';">
                        </a>
                        <nav class="hidden lg:flex lg:space-x-8">
                            <a href="{{ route('hrd.claims.index') }}" 
                               class="nav-item text-sm font-medium text-gray-500 @if(request()->routeIs('hrd.claims.*')) active @endif">
                                Persetujuan Klaim
                            </a>
                            <a href="{{ route('hrd.cuti.index') }}" 
                               class="nav-item text-sm font-medium text-gray-500 @if(request()->routeIs('hrd.cuti.*')) active @endif">
                                Persetujuan Cuti
                            </a>
                            <a href="{{ route('hrd.data-changes.index') }}" 
                               class="nav-item text-sm font-medium text-gray-500 @if(request()->routeIs('hrd.data-changes.*')) active @endif">
                                Perubahan Data
                            </a>
                            <a href="{{ route('hrd.users.index') }}" 
                               class="nav-item text-sm font-medium text-gray-500 @if(request()->routeIs('hrd.users.*')) active @endif">
                                Manajemen Karyawan
                            </a>
                            <div class="flex items-center">
                                <a href="{{ route('hrd.slip.index') }}" class="nav-item text-sm font-medium text-gray-500 @if(request()->routeIs('hrd.slip.*') || request()->routeIs('hrd.slip')) active @endif">Slip Gaji</a>
                            </div>
                        </nav>
                    </div>
                    
                    <!-- Menu Desktop -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" 
                           class="text-sm font-medium text-white bg-green-600 px-3 py-1.5 rounded-lg hover:bg-green-700 transition-colors hidden sm:block" 
                           title="Lihat sebagai Karyawan">
                            Tampilan Karyawan
                        </a>
                        <div class="hidden sm:flex items-center space-x-2 bg-slate-100 p-2 rounded-lg">
                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-50 text-red-600 font-semibold p-2.5 rounded-lg text-sm flex items-center hover:bg-red-100 hover:text-red-700 transition" 
                                    title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                        
                        <!-- Tombol Menu Mobile -->
                        <div class="md:hidden">
                            <button @click="sidebarOpen = true" 
                                    class="text-slate-700 hover:text-blue-600 p-2 rounded-md">
                                <span class="sr-only">Buka menu</span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar Mobile -->
        <div x-show="sidebarOpen" 
             x-cloak 
             class="fixed inset-0 z-50 md:hidden" 
             role="dialog" 
             aria-modal="true">
            <!-- Overlay -->
            <div x-show="sidebarOpen" 
                 x-transition.opacity 
                 @click="sidebarOpen = false" 
                 class="fixed inset-0 bg-black bg-opacity-25 backdrop-blur-sm"></div>
            
            <!-- Sidebar Panel -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in-out duration-300 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="translate-x-full" 
                 class="fixed top-0 right-0 h-full w-72 bg-white shadow-lg flex flex-col" 
                 x-trap.noscroll="sidebarOpen">
                
                <!-- Header Sidebar -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h2 class="font-bold text-lg text-slate-800">Menu</h2>
                    <button @click="sidebarOpen = false" 
                            class="text-slate-500 hover:text-slate-800 p-2 rounded-full">
                        <span class="sr-only">Tutup menu</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Menu Items -->
                <div class="flex-grow p-4 overflow-y-auto">
                    <nav class="space-y-2 mb-6">
                        <a href="{{ route('hrd.claims.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium @if(request()->routeIs('hrd.claims.*')) bg-blue-50 text-blue-700 @else text-gray-700 hover:bg-gray-100 @endif">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Persetujuan Klaim</span>
                        </a>
                        <a href="{{ route('hrd.cuti.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium @if(request()->routeIs('hrd.cuti.*')) bg-blue-50 text-blue-700 @else text-gray-700 hover:bg-gray-100 @endif">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Persetujuan Cuti</span>
                        </a>
                        <a href="{{ route('hrd.data-changes.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium @if(request()->routeIs('hrd.data-changes.*')) bg-blue-50 text-blue-700 @else text-gray-700 hover:bg-gray-100 @endif">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Perubahan Data</span>
                        </a>
                        <a href="{{ route('hrd.users.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium @if(request()->routeIs('hrd.users.*')) bg-blue-50 text-blue-700 @else text-gray-700 hover:bg-gray-100 @endif">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span>Manajemen Karyawan</span>
                        </a>
                        <a href="{{ route('hrd.slip.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium @if(request()->routeIs('hrd.slip.*') || request()->routeIs('hrd.slip')) bg-blue-50 text-blue-700 @else text-gray-700 hover:bg-gray-100 @endif">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Slip Gaji</span>
                        </a>
                    </nav>
                    
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-lg font-medium text-green-800 bg-green-100 hover:bg-green-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Tampilan Karyawan</span>
                    </a>
                </div>
                
                <!-- User Profile Section -->
                <div class="p-4 border-t bg-slate-50">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">HRD Dashboard</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-red-50 text-red-600 font-semibold p-2.5 rounded-lg text-sm flex items-center justify-center space-x-2 hover:bg-red-100 hover:text-red-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="relative z-10">
            <div class="container mx-auto max-w-7xl py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>