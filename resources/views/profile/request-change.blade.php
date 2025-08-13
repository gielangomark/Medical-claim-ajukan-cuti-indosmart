<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Perubahan Data - Medical Claim</title>
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

    <div class="container mx-auto max-w-3xl p-4 sm:p-8">
        
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                    <ul class="list-disc list-inside mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
             
            <div class="mb-8 text-center">
                <img src="{{ asset('indosmart-Update.png') }}" alt="Logo Indosmart" class="mx-auto mb-4 h-10" onerror="this.onerror=null;this.src='https://placehold.co/200x60/003366/FFFFFF?text=INDOSMART';">
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Pengajuan Perubahan Data Pribadi</h1>
                <p class="text-slate-500 mt-1">Perubahan data memerlukan verifikasi dari tim HRD.</p>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-8">
                <h2 class="font-semibold text-slate-700 mb-2">Data Anda Saat Ini</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-slate-500">Nama Lengkap:</span>
                        <p class="font-medium text-slate-800">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500">NIK:</span>
                        <p class="font-medium text-slate-800">{{ Auth::user()->nik }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500">Status Perkawinan:</span>
                        <p class="font-medium text-slate-800">{{ ucfirst(Auth::user()->marital_status) }}</p>
                    </div>
                </div>
            </div>

            {{-- PERUBAHAN DIMULAI DI SINI --}}
            @if (strtolower(Auth::user()->marital_status) === 'menikah')

                <div class="text-center bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-6">
                    <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-semibold">Status Anda Sudah Menikah</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        Anda tidak dapat mengajukan perubahan status perkawinan karena data Anda sudah tercatat sebagai 'Menikah'. Jika ada kesalahan data, silakan hubungi HRD.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-700 transition">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>

            @else

                <form action="{{ route('request-change.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="change_type" class="block text-sm font-medium text-slate-700 mb-1">Jenis Pengajuan</label>
                        <input type="text" id="change_type" name="request_type" value="Perubahan Status Perkawinan" readonly class="w-full p-3 bg-slate-200 border border-slate-300 rounded-md shadow-sm text-slate-600 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="new_status" class="block text-sm font-medium text-slate-700 mb-1">Ubah Status Menjadi</label>
                        <select id="new_status" name="new_marital_status" required class="w-full p-3 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">-- Pilih Status Baru --</option>
                            <option value="menikah">Menikah</option>
                        </select>
                    </div>

                    <div id="spouse-info-section" class="hidden space-y-4 border-t border-slate-200 pt-6">
                        <h3 class="font-semibold text-slate-800">Data Pasangan</h3>
                        <div>
                            <label for="spouse_name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap Pasangan</p>
                            <input type="text" id="spouse_name" name="spouse_name" placeholder="Nama sesuai dokumen resmi" class="w-full p-3 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                         <div>
                            <label for="spouse_dob" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir Pasangan</p>
                            <input type="date" id="spouse_dob" name="spouse_dob" class="w-full p-3 bg-white border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>

                    <div>
                        <label for="proof_document" class="block text-sm font-medium text-slate-700 mb-1">Upload Dokumen Pendukung</label>
                        <input type="file" id="proof_document" name="proof_document" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-slate-500 mt-1">Wajib: Scan/foto Buku Nikah atau Kartu Keluarga. (PDF, JPG, PNG)</p>
                    </div>

                    <hr class="pt-2">

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('dashboard') }}" class="bg-slate-200 text-slate-800 font-semibold py-2 px-5 rounded-lg hover:bg-slate-300 transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:bg-blue-700 transition">
                            Ajukan Perubahan
                        </button>
                    </div>
                </form>

            @endif
            {{-- PERUBAHAN SELESAI DI SINI --}}

        </div>
    </div>

    <script>
        // Memastikan elemen ada sebelum menambahkan event listener
        const newStatusSelect = document.getElementById('new_status');
        const spouseInfoSection = document.getElementById('spouse-info-section');

        // Hanya jalankan script jika form-nya ada (jika pengguna belum menikah)
        if (newStatusSelect) {
            newStatusSelect.addEventListener('change', function(event) {
                // Check if the selected value is 'menikah'
                if (event.target.value === 'menikah') {
                    // Jika ya, tampilkan dan wajibkan isian data pasangan
                    spouseInfoSection.classList.remove('hidden');
                    document.getElementById('spouse_name').required = true;
                    document.getElementById('spouse_dob').required = true;
                } else {
                    // Jika tidak, sembunyikan dan hapus kewajiban isian
                    spouseInfoSection.classList.add('hidden');
                    document.getElementById('spouse_name').required = false;
                    document.getElementById('spouse_dob').required = false;
                }
            });
        }
    </script>

</body>
</html>