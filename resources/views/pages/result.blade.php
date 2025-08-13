<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ session('result_data.title', 'Sukses') }}</title>
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
<body class="bg-slate-200">

    {{-- PERBAIKAN: Menggunakan struktur modal dengan backdrop --}}
    <div class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center p-4 z-50">
        @if (session('result_data'))
            @php
                $result = session('result_data');
                $isSuccess = $result['status'] === 'success';
            @endphp

            <div class="w-full max-w-md bg-gray-200 rounded-3xl shadow-2xl p-8 text-center">
                
                <!-- Ikon (disesuaikan dengan referensi) -->
                <div class="mb-6">
                    <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center {{ $isSuccess ? 'bg-blue-500' : 'bg-red-500' }}">
                        @if ($isSuccess)
                            <!-- Ikon Ceklis -->
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        @else
                            <!-- Ikon Silang -->
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        @endif
                    </div>
                </div>

                <!-- Message (tanpa judul, sesuai referensi) -->
                <p class="text-slate-800 text-lg mb-8">{{ $result['message'] }}</p>

                <!-- Action Button (disesuaikan dengan referensi) -->
                <a href="{{ $result['button_url'] }}" class="w-full inline-block bg-white text-slate-800 font-semibold py-3 px-6 rounded-xl shadow-md transition hover:bg-slate-100">
                    {{ $result['button_text'] }}
                </a>

            </div>
        @endif
    </div>

</body>
</html>
