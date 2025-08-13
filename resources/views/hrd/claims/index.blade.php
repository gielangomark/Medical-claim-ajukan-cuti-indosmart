@extends('layouts.hrd')

@section('title', 'Dasbor Persetujuan Klaim')

@push('styles')
<style>
    .status-badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; }
    .status-pending_approval { background-color: #FFFBEB; color: #B45309; }
    .status-approved { background-color: #F0FDF4; color: #166534; }
    .status-rejected { background-color: #FEF2F2; color: #991B1B; }
    /* {{-- Gaya untuk efek loading --}} */
    #claims-data-wrapper.loading {
        opacity: 0.5;
        transition: opacity 0.3s ease-in-out;
    }
</style>
@endpush

@section('content')

    <div class="mb-8">
    <div class="p-6 md:p-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg text-white">
        <h1 class="text-2xl md:text-3xl font-bold">
            Dasbor Persetujuan Klaim
        </h1>
        <p class="mt-2 opacity-80">
            Tinjau dan proses pengajuan klaim dari karyawan. Gunakan filter untuk mencari klaim tertentu.
        </p>
    </div>
</div>

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

    <div class="bg-white rounded-2xl shadow-lg">
        <div class="p-4 md:p-6 border-b border-slate-200">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="relative w-full md:w-1/3">
                     <input id="search-input" type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan..." class="w-full p-3 pl-10 bg-slate-100 border border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                     <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
                <div class="w-full md:w-auto">
                     <select id="status-select" name="status" class="w-full md:w-auto p-3 bg-slate-100 border border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                         <option value="">Semua Status</option>
                         <option value="pending_approval" @selected(request('status') == 'pending_approval')>Menunggu Persetujuan</option>
                         <option value="approved" @selected(request('status') == 'approved')>Disetujui</option>
                         <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
                     </select>
                </div>
            </div>
        </div>

        <div id="claims-data-wrapper">
            @include('hrd.claims._claims_table', ['claims' => $claims])
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const statusSelect = document.getElementById('status-select');
        const dataWrapper = document.getElementById('claims-data-wrapper');
        let debounceTimer;

        // Fungsi utama untuk mengambil data. Kini menerima URL sebagai argumen.
        async function fetchClaimsData(url) {
            dataWrapper.classList.add('loading'); // Efek loading dimulai

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                
                dataWrapper.innerHTML = data.table_html;
                window.history.pushState({}, '', url); // Update URL di browser
            } catch (error) {
                console.error('Error fetching claims data:', error);
                dataWrapper.innerHTML = `<div class="text-center py-10 text-red-600">Gagal memuat data.</div>`;
            } finally {
                dataWrapper.classList.remove('loading'); // Efek loading selesai
            }
        }
        
        // Fungsi untuk menangani perubahan pada filter
        function handleFilterChange() {
            const url = new URL("{{ route('hrd.claims.index') }}");
            if (searchInput.value) url.searchParams.set('search', searchInput.value);
            if (statusSelect.value) url.searchParams.set('status', statusSelect.value);
            
            fetchClaimsData(url.toString());
        }

        // Event listener untuk filter dengan debounce
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(handleFilterChange, 400);
        });

        statusSelect.addEventListener('change', handleFilterChange);

        // BAGIAN BARU: Event listener untuk menangani klik pada pagination
        dataWrapper.addEventListener('click', function(event) {
            // Cek jika yang diklik adalah link pagination (memiliki class .page-link bawaan Laravel)
            const target = event.target.closest('a.page-link');
            if (target) {
                event.preventDefault(); // Mencegah reload halaman
                fetchClaimsData(target.href); // Ambil data dari URL pagination
            }
        });
    });
</script>
@endpush