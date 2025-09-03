<div class="bg-white rounded-xl shadow-md overflow-hidden">

    {{-- =================================================== --}}
    {{-- ========= TAMPILAN KARTU UNTUK MOBILE ======== --}}
    {{-- =================================================== --}}
    <div class="md:hidden">
        <div class="p-4 space-y-4">
        @forelse ($claims as $claim)
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 space-y-3">
                
                {{-- Baris Karyawan --}}
                <div class="flex justify-between items-start">
                    <span class="text-sm font-semibold text-slate-500">Karyawan</span>
                        <div class="text-right">
                        <p class="font-bold text-slate-800">{{ optional($claim->user)->name ?? '-' }}</p>
                        <p class="text-xs text-slate-500">NIK: {{ optional($claim->user)->nik ?? '-' }}</p>
                    </div>
                </div>

                {{-- Baris Tanggal Diajukan --}}
                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-sm font-semibold text-slate-500">Tgl. Diajukan</span>
                    <span class="text-sm font-medium text-slate-700">{{ $claim->submitted_at ? \Carbon\Carbon::parse($claim->submitted_at)->locale('id')->isoFormat('D MMMM YYYY') : '-' }}</span>
                </div>

                {{-- Baris Total Klaim --}}
                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-sm font-semibold text-slate-500">Total Klaim</span>
                    <span class="text-sm font-bold text-slate-800">Rp {{ number_format($claim->total_amount, 0, ',', '.') }}</span>
                </div>

                {{-- DITAMBAHKAN KEMBALI: Baris Status --}}
                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-sm font-semibold text-slate-500">Status</span>
                    <div>
                        <span class="status-badge status-{{ $claim->status }}">{{ str_replace('_', ' ', $claim->status) }}</span>
                    </div>
                </div>

                {{-- DITAMBAHKAN KEMBALI: Baris Aksi --}}
                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-sm font-semibold text-slate-500">Aksi</span>
                    <a href="{{ route('hrd.claims.show', $claim) }}" class="font-bold text-blue-600 hover:text-blue-800 text-sm">
                        {{ $claim->status == 'pending_approval' ? 'Proses Sekarang' : 'Lihat Detail' }}
                    </a>
                </div>

            </div>
        @empty
            <div class="text-center py-10 text-slate-500">
                Tidak ada data pengajuan klaim.
            </div>
        @endforelse
        </div>
    </div>

    {{-- =================================================== --}}
    {{-- ========= TAMPILAN TABEL UNTUK DESKTOP ======== --}}
    {{-- =================================================== --}}
    <div class="overflow-x-auto hidden md:block">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-semibold text-slate-600 uppercase">Karyawan</th>
                    <th class="py-3 px-6 text-left text-xs font-semibold text-slate-600 uppercase">Tgl. Diajukan</th>
                    <th class="py-3 px-6 text-right text-xs font-semibold text-slate-600 uppercase">Total Klaim</th>
                    <th class="py-3 px-6 text-center text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="py-3 px-6 text-center text-xs font-semibold text-slate-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($claims as $claim)
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-6">
                            <div>
                                <p class="font-semibold text-slate-800">{{ optional($claim->user)->name ?? '-' }}</p>
                                <p class="text-sm text-slate-500">NIK: {{ optional($claim->user)->nik ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-slate-600">
                            {{ $claim->submitted_at ? \Carbon\Carbon::parse($claim->submitted_at)->locale('id')->isoFormat('D MMMM YYYY') : '-' }}
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-800 text-right">
                            Rp {{ number_format($claim->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="status-badge status-{{ $claim->status }}">{{ str_replace('_', ' ', $claim->status) }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('hrd.claims.show', $claim) }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                {{ $claim->status == 'pending_approval' ? 'Proses' : 'Lihat' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-500">
                            Tidak ada data pengajuan klaim yang cocok dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    <div class="p-6 border-t border-slate-200">
        {{ $claims->appends(request()->query())->links() }}
    </div>
</div>