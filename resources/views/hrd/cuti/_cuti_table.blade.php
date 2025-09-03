<div class="bg-white rounded-xl shadow-md overflow-hidden">

    {{-- Mobile cards --}}
    <div class="md:hidden">
        <div class="p-4 space-y-4">
        @forelse ($cutiList as $cuti)
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <span class="text-sm font-semibold text-slate-500">Karyawan</span>
                        <div class="text-right">
                        @if(optional($cuti->user))
                            <p class="font-bold text-slate-800">{{ optional($cuti->user)->name }}</p>
                            <p class="text-xs text-slate-500">NIK: {{ optional($cuti->user)->employee_id }}</p>
                            @php
                                $mobileRemaining = $remainingByUser[$cuti->user_id] ?? (optional($cuti->user)->cuti_quota ?? 12);
                            @endphp
                            <p class="text-xs text-slate-500">Sisa Cuti: {{ $mobileRemaining }} hari</p>
                        @else
                            <p class="font-bold text-slate-800">User deleted</p>
                            <p class="text-xs text-slate-500">NIK: -</p>
                            <p class="text-xs text-slate-500">Sisa Cuti: -</p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-sm font-semibold text-slate-500">Tgl. Diajukan</span>
                    <span class="text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($cuti->created_at)->format('d F Y') }}</span>
                </div>

                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-sm font-semibold text-slate-500">Total Hari</span>
                    <span class="text-sm font-bold text-slate-800">{{ $cuti->duration }} hari</span>
                </div>

                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-sm font-semibold text-slate-500">Status</span>
                    <div>
                        @if($cuti->status === 'approved')
                            <span class="status-badge bg-green-50 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">APPROVED</span>
                        @elseif($cuti->status === 'rejected')
                            <span class="status-badge bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs font-semibold">REJECTED</span>
                        @else
                            <span class="status-badge bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs font-semibold">PENDING</span>
                        @endif
                    </div>
                </div>

                <div class="flex justify-between items-center border-t pt-3">
                    <span class="text-sm font-semibold text-slate-500">Aksi</span>
                    <a href="{{ route('hrd.cuti.show', $cuti) }}" class="font-bold text-blue-600 hover:text-blue-800 text-sm">Lihat</a>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-slate-500">
                Tidak ada data pengajuan cuti.
            </div>
        @endforelse
        </div>
    </div>

    {{-- Desktop table --}}
    <div class="overflow-x-auto hidden md:block">
        <table class="min-w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="py-3 px-6 text-left text-xs font-semibold text-slate-600 uppercase">Karyawan</th>
                    <th class="py-3 px-6 text-left text-xs font-semibold text-slate-600 uppercase">Tgl. Diajukan</th>
                    <th class="py-3 px-6 text-right text-xs font-semibold text-slate-600 uppercase">Total Hari</th>
                    <th class="py-3 px-6 text-right text-xs font-semibold text-slate-600 uppercase">Sisa Cuti</th>
                    <th class="py-3 px-6 text-center text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="py-3 px-6 text-center text-xs font-semibold text-slate-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($cutiList as $cuti)
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-6">
                            <div>
                                @if(optional($cuti->user))
                                    <p class="font-semibold text-slate-800">{{ optional($cuti->user)->name }}</p>
                                    <p class="text-sm text-slate-500">NIK: {{ optional($cuti->user)->employee_id }}</p>
                                @else
                                    <p class="font-semibold text-slate-800">User deleted</p>
                                    <p class="text-sm text-slate-500">NIK: -</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($cuti->created_at)->format('d F Y') }}
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-800 text-right">
                            {{ $cuti->duration }} hari
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-800 text-right">
                            @php
                                $remaining = $remainingByUser[$cuti->user_id] ?? (optional($cuti->user)->cuti_quota ?? '-');
                            @endphp
                            <span class="text-sm text-slate-600">{{ $remaining }} hari</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($cuti->status === 'approved')
                                <span class="px-2 py-0.5 text-xs bg-[#dcfce7] text-[#15803d] rounded">APPROVED</span>
                            @elseif($cuti->status === 'rejected')
                                <span class="px-2 py-0.5 text-xs bg-[#fee2e2] text-[#b91c1c] rounded">REJECTED</span>
                            @else
                                <span class="px-2 py-0.5 text-xs bg-[#fef9c3] text-[#854d0e] rounded">PENDING</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('hrd.cuti.show', $cuti) }}" class="font-semibold text-blue-600 hover:text-blue-800">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-500">Tidak ada data pengajuan cuti yang cocok dengan filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="p-6 border-t border-slate-200">
        {{ $cutiList->appends(request()->query())->links() }}
    </div>
</div>
