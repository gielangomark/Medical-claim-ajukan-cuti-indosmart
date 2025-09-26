<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Slip Gaji' }} - Medical Claim</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center py-2 md:h-16 space-y-2 md:space-y-0">
                <div class="flex items-center">
                    <a href="{{ route('hrd.slip.index') }}" class="flex-shrink-0">
                        <img src="{{ asset('indosmart-update.png') }}"
                             alt="Logo Indosmart"
                             class="h-12 md:h-20 w-auto"
                             style="object-fit:contain;"
                             onerror="this.onerror=null;this.src='https://placehold.co/150x40/003366/FFFFFF?text=INDOSMART';">
                    </a>
                </div>
                
                <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
                    <div class="flex items-center bg-slate-100 p-2 rounded-lg">
                        <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                    </div>
                    <a href="{{ route('hrd.users.index') }}" 
                       class="w-full md:w-auto text-center text-sm font-medium text-white bg-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-700 transition-colors">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container mx-auto max-w-7xl py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>