<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            {{-- BAGIAN INI BERUBAH: Header disembunyikan di layar kecil (di bawah breakpoint 'md') --}}
            <thead class="bg-gray-800 hidden md:table-header-group">
                <tr>
                    <th class="py-4 px-6 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Nama Karyawan
                        </div>
                    </th>
                    <th class="py-4 px-6 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Departemen
                        </div>
                    </th>
                    <th class="py-4 px-6 text-left text-xs font-semibold text-white uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status
                        </div>
                    </th>
                    <th class="py-4 px-6 text-center text-xs font-semibold text-white uppercase tracking-wider">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            Aksi
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($employees as $employee)
                    {{-- BAGIAN INI BERUBAH: Setiap <tr> menjadi block di layar kecil --}}
                    <tr class="block md:table-row hover:bg-gray-50 transition-colors duration-150">
                        
                        {{-- BAGIAN INI BERUBAH: Setiap <td> diubah menjadi block dengan label --}}
                        <td class="px-6 py-3 block md:table-cell md:py-5 whitespace-nowrap" data-label="Nama Karyawan">
                            {{-- Label yang hanya muncul di mobile --}}
                            <span class="md:hidden text-xs font-semibold text-gray-500 uppercase">Nama Karyawan</span>
                            <div class="flex items-center pt-2 md:pt-0">
                                <div class="flex-shrink-0 h-11 w-11">
                                    <div class="h-11 w-11 rounded-full bg-gradient-to-br from-gray-600 to-gray-800 flex items-center justify-center shadow-sm">
                                        <span class="text-white font-semibold text-base">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $employee->name }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 012-2h2a2 2 0 012 2v2m-6 0a2 2 0 002 2h2a2 2 0 002-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        NIK: {{ $employee->nik }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-3 block md:table-cell md:py-5 whitespace-nowrap" data-label="Departemen">
                            <span class="md:hidden text-xs font-semibold text-gray-500 uppercase">Departemen</span>
                            <div class="flex items-center pt-2 md:pt-0">
                                <div class="h-8 w-8 rounded-md bg-gray-100 flex items-center justify-center mr-3 md:hidden">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst($employee->department) }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-3 block md:table-cell md:py-5 whitespace-nowrap" data-label="Status">
                            <span class="md:hidden text-xs font-semibold text-gray-500 uppercase">Status</span>
                            <div class="pt-2 md:pt-0">
                                @if($employee->marital_status == 'menikah')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        Menikah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Lajang
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-3 block md:table-cell md:py-5 md:text-center" data-label="Aksi">
                            <span class="md:hidden text-xs font-semibold text-gray-500 uppercase">Aksi</span>
                            <div class="flex justify-start md:justify-center items-center space-x-2 pt-2 md:pt-0">
                                <a href="{{ route('hrd.users.edit', $employee->id) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                                
                                <form action="{{ route('hrd.users.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini? Tindakan ini tidak bisa dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- colspan diubah menjadi 1 karena di mobile hanya ada 1 kolom --}}
                        <td colspan="1" class="py-16 px-6 text-center bg-gray-50 md:colspan-4">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada data karyawan</h3>
                                <p class="text-sm text-gray-600">Tidak ada data karyawan yang cocok dengan filter yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white px-6 py-4 border-t border-gray-200 rounded-b-lg">
    {{-- Kode pagination Anda sudah responsif, jadi tidak perlu diubah. --}}
    <div class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($employees->hasPages())
                <div class="flex items-center space-x-2">
                    @if ($employees->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-default rounded-md">Previous</span>
                    @else
                        <a href="{{ $employees->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200">Previous</a>
                    @endif

                    @if ($employees->hasMorePages())
                        <a href="{{ $employees->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200">Next</a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-default rounded-md">Next</span>
                    @endif
                </div>
            @endif
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-600">
                    Menampilkan
                    <span class="font-semibold text-gray-900">{{ $employees->firstItem() ?? 0 }}</span>
                    hingga
                    <span class="font-semibold text-gray-900">{{ $employees->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-gray-900">{{ $employees->total() }}</span>
                    hasil
                </p>
            </div>
            <div>
                {{ $employees->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>