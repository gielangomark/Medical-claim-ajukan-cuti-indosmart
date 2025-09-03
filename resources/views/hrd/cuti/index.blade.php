@extends('layouts.hrd')

@section('content')
    {{-- 2. Mengisi judul halaman spesifik untuk halaman ini --}}
    <div>
        <!-- Header dengan Background Biru (disesuaikan dengan klaim) -->
        <div class="mb-8">
            <div class="p-6 md:p-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white">
                <h1 class="text-2xl md:text-3xl font-bold">Dasbor Persetujuan Cuti</h1>
                <p class="mt-2 opacity-80">Tinjau dan proses pengajuan cuti dari karyawan. Gunakan filter untuk mencari cuti tertentu.</p>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 transition-all hover:shadow-md">
                <h2 class="text-sm font-medium text-slate-500">Menunggu Persetujuan</h2>
                <p class="text-3xl font-bold text-amber-600 mt-2">{{ $pendingCount }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 transition-all hover:shadow-md">
                <h2 class="text-sm font-medium text-slate-500">Disetujui (Bulan Ini)</h2>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $approvedCount }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 transition-all hover:shadow-md">
                <h2 class="text-sm font-medium text-slate-500">Ditolak (Bulan Ini)</h2>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejectedCount }}</p>
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <form id="filterForm" method="GET" class="mb-6">
            <div class="bg-white rounded-2xl shadow-lg">
                <div class="p-4 md:p-6 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="relative w-full md:w-1/3">
                             <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan..." class="w-full p-3 pl-10 bg-slate-100 border border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                             <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        <div class="w-full md:w-auto">
                             <select id="status" name="status" class="w-full md:w-auto p-3 bg-slate-100 border border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                                 <option value="">Semua Status</option>
                                 <option value="pending" @selected(request('status') == 'pending')>Menunggu</option>
                                 <option value="approved" @selected(request('status') == 'approved')>Disetujui</option>
                                 <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
                             </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Tabel Data (gunakan partial agar konsisten dengan AJAX) -->
        <div class="bg-white rounded-2xl shadow-lg">
            @include('hrd.cuti._cuti_table', ['cutiList' => $cutiList, 'remainingByUser' => $remainingByUser ?? []])
        </div>
    </div>

    @push('scripts')
    <script>
        (function(){
            const form = document.getElementById('filterForm');
            const statusSelect = document.getElementById('status');
            const searchInput = document.getElementById('search');

            if (!form) return;

            // Submit form when status changes
            if (statusSelect) {
                statusSelect.addEventListener('change', () => form.submit());
            }

            // Debounce search input
            if (searchInput) {
                let timeout = null;
                searchInput.addEventListener('input', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        form.submit();
                    }, 500);
                });
            }
        })();
    </script>
    @endpush

@endsection
