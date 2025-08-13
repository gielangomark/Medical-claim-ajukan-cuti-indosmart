<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Medical Claim</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lora', serif; background-color: #fdfbf8; }
        .top-bar { background-color: #5d4037; }
        .form-container { background-color: #fff5f5; }
        .input-field { background-color: #e5e7eb; font-family: 'Lora', serif; }
        .action-button { background-color: #6366f1; }
        .action-button:hover { background-color: #4f46e5; }
    </style>
</head>
<body>

    <div class="h-2 w-full top-bar"></div>

    <div class="min-h-screen flex flex-col justify-center items-center p-4">
        <div class="w-full max-w-md">
            
            <div class="text-center mb-6">
                <a href="{{ route('login') }}">
                    <img src="{{ asset('indosmart-removebg-preview.png') }}" alt="Logo Indosmart" class="h-12 mx-auto mb-4">
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Lupa Password Anda?</h1>
                <p class="text-slate-600 mt-2 text-sm px-4">
                    Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan link untuk membuat password baru.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4 rounded-lg text-center">
                    {{ session('status') }}
                </div>
            @endif

            <div class="form-container rounded-2xl shadow-lg p-8">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Alamat Email</label>
                        <input id="email" class="w-full p-3 input-field border-transparent rounded-lg" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda" />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-bold text-white action-button transition">
                            Kirim Link Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
