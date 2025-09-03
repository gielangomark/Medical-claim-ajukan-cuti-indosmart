<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Klaim - {{ $claim->user->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending_approval { background-color: #FFFBEB; color: #B45309; }
        .status-approved { background-color: #F0FDF4; color: #166534; }
        .status-rejected { background-color: #FEF2F2; color: #991B1B; }
        .status-draft { background-color: #F1F5F9; color: #475569; }
    </style>
</head>
<body class="bg-slate-100">

    <!-- Header Navigation -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <img src="{{ asset('indosmart-update.png') }}" alt="Logo Indosmart" class="h-10 sm:h-14 w-auto" style="object-fit:contain;" onerror="this.onerror=null;this.src='https://placehold.co/150x40/003366/FFFFFF?text=INDOSMART';">
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
        
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Dashboard</a>
        </div>

        <!-- Claim Details Container -->
        <div class="bg-white rounded-2xl shadow-lg">
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Detail Pengajuan Klaim</h2>
                        <p class="text-slate-500">Diajukan pada: {{ $claim->submitted_at ? $claim->submitted_at->format('d M Y, H:i') : '-' }}</p>
                    </div>
                    <div>
                        <span class="status-badge status-{{ $claim->status }}">
                            {{ str_replace('_', ' ', $claim->status) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Summary -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-lg">
                    <div>
                        <p class="text-sm text-slate-500">Periode Klaim</p>
                        <p class="font-semibold text-slate-800">{{ $claim->period_month }} {{ $claim->period_year }}</p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-sm text-slate-500">Total Diajukan</p>
                        <p class="font-bold text-2xl text-blue-600">Rp {{ number_format($claim->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Details Table -->
                <div>
    <h3 class="text-lg font-semibold text-slate-800 mb-3">Rincian Transaksi</h3>
    
    {{-- PERBAIKAN: Menambahkan kelas 'responsive-table' untuk membuatnya menjadi kartu di mobile --}}
    <div class="overflow-x-auto rounded-lg border border-slate-200 responsive-table">
        <table class="min-w-full bg-white">
            <thead class="bg-slate-50">
                <tr>
                    <th class="py-2 px-3 text-left text-xs font-medium text-slate-500 uppercase">Tgl. Transaksi</th>
                    <th class="py-2 px-3 text-left text-xs font-medium text-slate-500 uppercase">Keterangan</th>
                    <th class="py-2 px-3 text-left text-xs font-medium text-slate-500 uppercase">Pasien</th>
                    <th class="py-2 px-3 text-right text-xs font-medium text-slate-500 uppercase">Jumlah</th>
                    <th class="py-2 px-3 text-center text-xs font-medium text-slate-500 uppercase">Bukti</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($claim->details as $detail)
                    <tr>
                        {{-- PERBAIKAN: Menambahkan data-label dan format tanggal Indonesia --}}
                        <td data-label="Tgl. Transaksi" class="py-3 px-3 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($detail->transaction_date)->locale('id')->isoFormat('D MMMM YYYY') }}
                        </td>
                        <td data-label="Keterangan" class="py-3 px-3 text-sm text-slate-600">{{ $detail->description }}</td>
                        <td data-label="Pasien" class="py-3 px-3 text-sm text-slate-600">{{ $detail->patient_name }}</td>
                        <td data-label="Jumlah" class="py-3 px-3 text-sm font-medium">Rp {{ number_format($detail->amount, 0, ',', '.') }}</td>
                        
                        {{-- PERBAIKAN: Menambahkan pengecekan untuk file bukti --}}
                        <td data-label="Bukti" class="py-3 px-3 text-center">
                            @if($detail->proof_file_path)
                                <a href="{{ asset('storage/' . $detail->proof_file_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">
                                    Lihat File
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak Ada File</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- PERBAIKAN: Tampilan data kosong yang lebih baik & colspan responsif --}}
                        <td colspan="1" class="md:colspan-5 text-center p-8 text-slate-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="font-medium">Belum ada rincian transaksi</p>
                                <p class="text-sm text-slate-400">Semua rincian yang ditambahkan akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($claim->status == 'rejected')
        <div>
            <h3 class="font-semibold text-slate-800 mb-2">Alasan Penolakan</h3>
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg">
                <p>{{ $claim->rejection_reason }}</p>
            </div>
                </div>
            @endif
            </div>
        </div>
    </main>

</body>
</html>
