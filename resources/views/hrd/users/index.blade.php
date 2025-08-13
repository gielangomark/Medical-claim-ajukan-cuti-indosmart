{{-- 1. Memberitahu Blade untuk menggunakan layout HRD --}}
@extends('layouts.hrd')

{{-- 2. Mengisi judul halaman spesifik untuk halaman ini --}}
@section('title', 'Manajemen Karyawan')

{{-- 3. Mengisi bagian konten utama --}}
@section('content')
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-8 text-white shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Manajemen Karyawan</h1>
                    <p class="text-blue-100 text-lg">Kelola data karyawan dengan mudah dan efisien</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <div>
                                <div class="text-sm text-blue-100">Total Karyawan</div>
                                <div class="text-2xl font-bold">{{ $employees->total() ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="bg-white rounded-lg px-4 py-2 shadow-sm border border-gray-200">
                <span class="text-sm font-medium text-gray-600">Status:</span>
                <span class="text-sm font-semibold text-green-600 ml-2">Aktif</span>
            </div>
        </div>
        <div>
            <a href="{{ route('hrd.users.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Karyawan
            </a>
        </div>
    </div>

    <!-- Employee Table Container -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Filters Form -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="relative w-full md:w-1/3">
                     <input id="search-input" type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIK..." class="w-full p-4 pl-12 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                     <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <select id="department-select" name="department" class="w-full md:w-auto p-4 bg-white border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                        <option value="">Semua Departemen</option>
                        @foreach ($departments as $value => $label)
                            <option value="{{ $value }}" @selected(request('department') == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Wrapper untuk tabel dan pagination yang akan di-refresh -->
        <div id="employee-data-wrapper" class="min-h-[400px]">
            @include('hrd.users._employee_table', ['employees' => $employees])
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 flex items-center gap-4 shadow-2xl">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
            <span class="text-lg font-medium text-gray-700">Please Wait...</span>
        </div>
    </div>

@endsection

{{-- 4. Mendorong skrip JavaScript ke dalam stack 'scripts' di layout --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const departmentSelect = document.getElementById('department-select');
        const dataWrapper = document.getElementById('employee-data-wrapper');
        const loadingOverlay = document.getElementById('loading-overlay');
        let debounceTimer;

        function showLoading() {
            loadingOverlay.classList.remove('hidden');
        }

        function hideLoading() {
            loadingOverlay.classList.add('hidden');
        }

        async function fetchEmployeeData() {
            const searchValue = searchInput.value;
            const departmentValue = departmentSelect.value;
            
            const url = new URL("{{ route('hrd.users.index') }}");
            if (searchValue) url.searchParams.set('search', searchValue);
            if (departmentValue) url.searchParams.set('department', departmentValue);

            try {
                showLoading();
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                
                dataWrapper.innerHTML = data.table_html;
                window.history.pushState({}, '', url);
            } catch (error) {
                console.error('Error fetching employee data:', error);
                // Show error message to user
                dataWrapper.innerHTML = `
                    <div class="p-8 text-center">
                        <div class="text-red-500 text-lg font-semibold">Terjadi kesalahan saat memuat data</div>
                        <div class="text-gray-500 mt-2">Silakan coba lagi atau hubungi administrator</div>
                    </div>
                `;
            } finally {
                hideLoading();
            }
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchEmployeeData, 400);
        });

        departmentSelect.addEventListener('change', fetchEmployeeData);
    });
</script>
@endpush

{{-- 5. Tambahan CSS untuk styling yang lebih profesional --}}
@push('styles')
<style>
    /* Professional enhancements */
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }
    
    /* Custom scrollbar untuk tabel */
    #employee-data-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    #employee-data-wrapper::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    #employee-data-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    #employee-data-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Smooth transitions */
    * {
        transition: all 0.2s ease-in-out;
    }
    
    /* Input focus effects */
    input:focus, select:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Card hover effects */
    .bg-white:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endpush