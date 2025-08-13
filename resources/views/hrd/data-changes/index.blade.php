@extends('layouts.hrd')

@section('title', 'Persetujuan Perubahan Data')

@push('styles')
<style>
    .status-badge { 
        padding: 0.25rem 0.75rem; 
        border-radius: 9999px; 
        font-size: 0.75rem; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        display: inline-block; 
    }
    .status-pending { 
        background-color: #FFFBEB; 
        color: #B45309; 
    }
    .status-approved { 
        background-color: #F0FDF4; 
        color: #166534; 
    }
    .status-rejected { 
        background-color: #FEF2F2; 
        color: #991B1B; 
    }
    
    /* Responsive table styles */
    .responsive-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    @media (max-width: 768px) {
        .responsive-table table,
        .responsive-table thead,
        .responsive-table tbody,
        .responsive-table th,
        .responsive-table td,
        .responsive-table tr {
            display: block;
        }
        
        .responsive-table thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        
        .responsive-table tbody tr {
            border: 1px solid #e2e8f0;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.75rem;
            background-color: #ffffff;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .responsive-table td {
            border: none;
            padding: 0.75rem 0;
            position: relative;
            padding-left: 40%;
            min-height: 2.5rem;
            display: flex;
            align-items: center;
        }
        
        .responsive-table td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 35%;
            padding-right: 1rem;
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }
        
        .responsive-table td[data-label="Karyawan"] {
            padding-left: 0;
            margin-bottom: 0.5rem;
        }
        
        .responsive-table td[data-label="Karyawan"]:before {
            display: none;
        }
        
        .responsive-table td[data-label="Status"] .status-badge,
        .responsive-table td[data-label="Aksi"] a {
            margin-left: 0;
        }
    }
</style>
@endpush

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="mb-6 p-6 md:p-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-lg text-white">
            <h1 class="text-2xl md:text-3xl font-bold">Persetujuan Perubahan Data</h1>
            <p class="mt-2 opacity-80">Tinjau dan proses pengajuan perubahan data dari karyawan.</p>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Filter/Search Section (bisa ditambahkan di masa depan) -->
        <div class="p-6 bg-slate-50 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Daftar Pengajuan</h2>
                    <p class="text-sm text-slate-600">Total: {{ $requests->total() }} pengajuan</p>
                </div>
                <!-- Filter bisa ditambahkan di sini -->
            </div>
        </div>

        <div class="responsive-table">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Karyawan
                        </th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Jenis Pengajuan
                        </th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Tgl. Diajukan
                        </th>
                        <th class="py-4 px-6 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="py-4 px-6 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($requests as $request)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td data-label="Karyawan" class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm">
                                        <span class="font-bold text-white text-sm">
                                            {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-semibold text-slate-800">{{ $request->user->name }}</p>
                                        <p class="text-sm text-slate-500">NIK: {{ $request->user->nik }}</p>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Jenis Pengajuan" class="py-4 px-6 text-sm text-slate-600">
                                <span class="font-medium">
                                    {{ ucwords(str_replace('_', ' ', $request->request_type)) }}
                                </span>
                            </td>
                            <td data-label="Tgl. Diajukan" class="py-4 px-6 text-sm text-slate-600">
                                <span class="font-medium">
                                    {{ $request->created_at->format('d M Y') }}
                                </span>
                                <span class="block text-xs text-slate-400">
                                    {{ $request->created_at->format('H:i') }}
                                </span>
                            </td>
                            <td data-label="Status" class="py-4 px-6 text-center">
                                <span class="status-badge status-{{ $request->status }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td data-label="Aksi" class="py-4 px-6 text-center">
                                <a href="{{ route('hrd.data-changes.show', $request->id) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Proses
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 px-6 text-center">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-lg text-slate-700">Tidak Ada Data Ditemukan</h3>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Belum ada pengajuan perubahan data yang masuk.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $requests->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection