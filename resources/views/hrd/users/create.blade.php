<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan Baru - HRD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100">

    <!-- Header Navigation -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <img src="{{ asset('indosmart-update.png') }}" alt="Logo Indosmart" class="h-8" onerror="this.onerror=null;this.src='https://placehold.co/150x40/003366/FFFFFF?text=INDOSMART';">
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-slate-600 mr-4 hidden sm:block">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white font-semibold py-2 px-4 rounded-lg text-sm hover:bg-red-600 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto max-w-4xl p-4 sm:p-6 lg:p-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Tambah Karyawan Baru</h1>
            <p class="text-slate-500 mt-1">Isi detail karyawan di bawah ini.</p>
        </div>

        <!-- Form Container -->
        <form action="{{ route('hrd.users.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-6 md:p-8 space-y-6">
            @csrf

            <!-- Main Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nik" class="block text-sm font-medium text-slate-700 mb-1">NIK</label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Departemen</label>
                    <select id="department" name="department" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Pilih Departemen --</option>
                        {{-- PERBAIKAN: Menggunakan key-value pair dari controller --}}
                        {{-- $value akan berisi 'it', 'finance', dll. --}}
                        {{-- $label akan berisi 'Teknologi Informasi', 'Keuangan', dll. --}}
                        @foreach ($departments as $value => $label)
                            <option value="{{ $value }}" @selected(old('department') == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('department')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                     <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                        <select id="gender" name="gender" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="pria" @selected(old('gender') == 'pria')>Pria</option>
                            <option value="wanita" @selected(old('gender') == 'wanita')>Wanita</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t">
                <a href="{{ route('hrd.users.index') }}" class="bg-slate-200 text-slate-800 font-semibold py-2 px-5 rounded-lg hover:bg-slate-300 transition">
                    Batal
                </a>
                <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:bg-green-700 transition">
                    Simpan Karyawan
                </button>
            </div>
        </form>
    </main>

</body>
</html>
