@extends('layouts.app')

@section('title', 'Dashboard User')

@push('styles')
<style>
    .status-badge { 
        padding: 0.375rem 0.875rem; 
        border-radius: 0.375rem; 
        font-size: 0.75rem; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 0.025em; 
        display: inline-block; 
    }
    .status-pending_approval { 
        background-color: #FEF3C7; 
        color: #92400E; 
        border: 1px solid #F59E0B; 
    }
    .status-approved { 
        background-color: #D1FAE5; 
        color: #065F46; 
        border: 1px solid #10B981; 
    }
    .status-rejected { 
        background-color: #FEE2E2; 
        color: #991B1B; 
        border: 1px solid #EF4444; 
    }
    .card-shadow {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .stats-gradient {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    }
</style>
@endpush

@section('content')
    
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 mb-6 rounded-lg flex items-center" role="alert">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-semibold">Sukses</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard User</h1>
        <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::user()->name }}. Kelola klaim dan data pribadi Anda dengan mudah.</p>
    </div>

    <div class="stats-gradient text-white rounded-xl card-shadow p-6 mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="text-lg font-semibold text-white/90">Sisa Jatah Klaim Tahun Ini</h2>
                <p class="text-4xl font-bold mt-1">Rp {{ number_format($remainingAllotment, 0, ',', '.') }}</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:text-right">
                <h2 class="text-sm font-semibold text-white/90">Status Klaim Terakhir</h2>
                @if($recentClaims->first())
                    <p class="font-semibold mt-1 status-badge status-{{ $recentClaims->first()->status }} bg-white/20 text-white">
                        {{ str_replace('_', ' ', $recentClaims->first()->status) }}
                    </p>
                @else
                    <p class="font-semibold mt-1 status-badge bg-white/20 text-white">Belum Ada</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('pengajuan.medical.create') }}" class="group bg-white rounded-xl card-shadow border border-gray-200 p-6 card-hover transition-all duration-200">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Klaim Medis</h3>
                    <p class="text-gray-600 mt-1">Buat pengajuan klaim baru untuk biaya medis</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('request-change.create') }}" class="group bg-white rounded-xl card-shadow border border-gray-200 p-6 card-hover transition-all duration-200">
            <div class="flex items-center space-x-4">
                <div class="bg-gray-100 p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Ubah Data Pribadi</h3>
                    <p class="text-gray-600 mt-1">Ajukan perubahan status perkawinan atau data lainnya</p>
                </div>
            </div>
        </a>
    </div>

    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Riwayat Klaim Terbaru</h2>
        <div class="bg-white rounded-xl card-shadow border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl. Diajukan</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode</th>
                            <th class="py-3 px-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                            <th class="py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="py-3 px-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($recentClaims as $claim)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4 text-sm text-gray-900">{{ $claim->submitted_at->format('d M Y') }}</td>
                                <td class="py-4 px-4 text-sm text-gray-900">{{ $claim->period_month }} {{ $claim->period_year }}</td>
                                <td class="py-4 px-4 text-right text-sm font-medium text-gray-900">Rp {{ number_format($claim->total_amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="status-badge status-{{ $claim->status }}">
                                        {{ str_replace('_', ' ', $claim->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('pengajuan.medical.show', $claim) }}" class="text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">Lihat Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-4 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="font-medium">Belum ada riwayat klaim</p>
                                        <p class="text-gray-400">Klaim yang Anda ajukan akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection