<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Top Navigation Bar -->
        <nav class="bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('hrd.dashboard') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            Dasbor HRD
                        </a>
                        <div class="flex items-center space-x-6">
                            <a href="{{ route('hrd.claims.index') }}" class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('hrd.claims.*') ? 'text-blue-600' : '' }}">
                                Persetujuan Klaim
                            </a>
                            <a href="{{ route('hrd.cuti.index') }}" class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('hrd.cuti.*') ? 'text-blue-600' : '' }}">
                                Persetujuan Cuti
                            </a>
                            <a href="{{ route('hrd.data-changes.index') }}" class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('hrd.data-changes.*') ? 'text-blue-600' : '' }}">
                                Perubahan Data
                            </a>
                            <a href="{{ route('hrd.users.index') }}" class="text-gray-600 hover:text-gray-900 {{ request()->routeIs('hrd.users.*') ? 'text-blue-600' : '' }}">
                                Manajemen Karyawan
                            </a>
                        </div>
                    </div>

                    </div>

                    <!-- Right Navigation -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="p-2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </button>

                        <!-- User Info -->
                        <div class="flex items-center space-x-1">
                            <span class="text-gray-700">HRD Manager</span>
                            <span class="text-gray-500">(Hrd)</span>
                        </div>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded">
                                Logout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Page Content -->
                {{ $slot }}
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
