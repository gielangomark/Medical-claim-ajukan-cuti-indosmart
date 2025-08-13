<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Karyawan - HRD</title>
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
                    <img src="{{ asset('image_faab59.png') }}" alt="Logo Indosmart" class="h-8" onerror="this.onerror=null;this.src='https://placehold.co/150x40/003366/FFFFFF?text=INDOSMART';">
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
            <h1 class="text-3xl font-bold text-slate-800">Ubah Data Karyawan</h1>
            <p class="text-slate-500 mt-1">Perbarui detail untuk <span class="font-semibold">{{ $user->name }}</span>.</p>
        </div>

        <!-- Form Container -->
        <form action="{{ route('hrd.users.update', $user) }}" method="POST" class="bg-white rounded-2xl shadow-lg p-6 md:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Main Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nik" class="block text-sm font-medium text-slate-700 mb-1">NIK</label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik', $user->nik) }}" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                    <select id="gender" name="gender" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="pria" @selected(old('gender', $user->gender) == 'pria')>Pria</option>
                        <option value="wanita" @selected(old('gender', $user->gender) == 'wanita')>Wanita</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Departemen</label>
                    {{-- PERBAIKAN: Mengubah input teks menjadi dropdown --}}
                    <select id="department" name="department" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach ($departments as $value => $label)
                            <option value="{{ $value }}" @selected(old('department', $user->department) == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('department')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="marital_status" class="block text-sm font-medium text-slate-700 mb-1">Status Perkawinan</label>
                    <select id="marital_status" name="marital_status" required class="w-full p-3 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="lajang" @selected(old('marital_status', $user->marital_status) == 'lajang')>Lajang</option>
                        <option value="menikah" @selected(old('marital_status', $user->marital_status) == 'menikah')>Menikah</option>
                    </select>
                </div>
            </div>

            <hr>

            <!-- Family Members Section -->
            <div id="family-section" class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-800">Anggota Keluarga</h3>
                    <button type="button" id="add-family-member" class="text-sm bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                        + Tambah Anggota
                    </button>
                </div>
                
                <div id="family-members-container" class="space-y-4">
                    {{-- Loop untuk menampilkan anggota keluarga yang sudah ada --}}
                    @foreach ($user->familyMembers as $index => $member)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-lg border family-member-row">
                            <input type="hidden" name="family[{{ $index }}][id]" value="{{ $member->id }}">
                            <input type="text" name="family[{{ $index }}][name]" placeholder="Nama Lengkap" value="{{ $member->name }}" class="w-full p-2 border border-slate-300 rounded-md">
                            <select name="family[{{ $index }}][relationship]" class="w-full p-2 border border-slate-300 rounded-md">
                                <option value="suami" @selected($member->relationship == 'suami')>Suami</option>
                                <option value="istri" @selected($member->relationship == 'istri')>Istri</option>
                                <option value="anak" @selected($member->relationship == 'anak')>Anak</option>
                            </select>
                            <input type="date" name="family[{{ $index }}][date_of_birth]" value="{{ $member->date_of_birth }}" class="w-full p-2 border border-slate-300 rounded-md">
                            <button type="button" class="remove-family-member text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 pt-4 border-t">
                <a href="{{ route('hrd.users.index') }}" class="bg-slate-200 text-slate-800 font-semibold py-2 px-5 rounded-lg hover:bg-slate-300 transition">
                    Batal
                </a>
                <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:bg-green-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </main>

    <!-- Template for new family member (hidden) -->
    <div id="family-member-template" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-lg border family-member-row">
            <input type="hidden" name="family[__INDEX__][id]" value="">
            <input type="text" name="family[__INDEX__][name]" placeholder="Nama Lengkap" class="w-full p-2 border border-slate-300 rounded-md">
            <select name="family[__INDEX__][relationship]" class="w-full p-2 border border-slate-300 rounded-md">
                <option value="suami">Suami</option>
                <option value="istri">Istri</option>
                <option value="anak" selected>Anak</option>
            </select>
            <input type="date" name="family[__INDEX__][date_of_birth]" class="w-full p-2 border border-slate-300 rounded-md">
            <button type="button" class="remove-family-member text-red-500 hover:text-red-700 font-semibold">Hapus</button>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const maritalStatusSelect = document.getElementById('marital_status');
            const familySection = document.getElementById('family-section');
            const addFamilyBtn = document.getElementById('add-family-member');
            const familyContainer = document.getElementById('family-members-container');
            const familyTemplate = document.getElementById('family-member-template');
            let familyIndex = {{ $user->familyMembers->count() }};

            function toggleFamilySection() {
                if (maritalStatusSelect.value === 'menikah') {
                    familySection.style.display = 'block';
                } else {
                    familySection.style.display = 'none';
                }
            }

            addFamilyBtn.addEventListener('click', function () {
                const newMemberHTML = familyTemplate.innerHTML.replace(/__INDEX__/g, `new_${familyIndex}`);
                const newMemberDiv = document.createElement('div');
                newMemberDiv.innerHTML = newMemberHTML;
                familyContainer.appendChild(newMemberDiv.firstElementChild);
                familyIndex++;
            });

            familyContainer.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-family-member')) {
                    e.target.closest('.family-member-row').remove();
                }
            });

            // Initial check on page load
            toggleFamilySection();
            maritalStatusSelect.addEventListener('change', toggleFamilySection);
        });
    </script>

</body>
</html>
