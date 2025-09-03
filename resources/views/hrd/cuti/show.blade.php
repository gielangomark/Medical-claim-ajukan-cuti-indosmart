@extends('layouts.hrd')

@section('title', 'Detail Pengajuan Cuti')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row justify-between items-start mb-6 gap-4">
        <div>
            <a href="{{ route('hrd.cuti.index') }}" class="flex items-center gap-2 text-sm text-blue-600 hover:underline font-medium mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Cuti
            </a>
            <h1 class="text-3xl font-bold text-slate-800">Detail Pengajuan Cuti</h1>
        </div>

        {{-- Status Badge Dinamis --}}
        <div>
            @if ($cuti->status == 'pending')
                <div class="flex items-center gap-2 bg-amber-100 text-amber-800 font-semibold text-sm py-2 px-4 rounded-full">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>
                    <span>Menunggu Persetujuan</span>
                </div>
            @elseif ($cuti->status == 'approved')
                <div class="flex items-center gap-2 bg-green-100 text-green-800 font-semibold text-sm py-2 px-4 rounded-full">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                    <span>Disetujui</span>
                </div>
            @elseif ($cuti->status == 'rejected')
                <div class="flex items-center gap-2 bg-red-100 text-red-800 font-semibold text-sm py-2 px-4 rounded-full">
                     <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                    <span>Ditolak</span>
                </div>
            @endif
        </div>
    </div>

        <!-- Info Pengajuan -->
    <div class="bg-white p-6 rounded-2xl shadow-lg mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-3">Informasi Pengajuan</h3>
        <div class="space-y-4 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">ID Pengajuan:</span>
                <span class="font-semibold text-slate-700">CUTI-{{ str_pad($cuti->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Karyawan:</span>
                <span class="font-semibold text-slate-700">{{ optional($cuti->user)->name ?? '-' }} (ID: {{ optional($cuti->user)->employee_id ?? '-' }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tanggal Mulai:</span>
                <span class="font-semibold text-slate-700">{{ optional($cuti->tanggal_mulai)->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Durasi:</span>
                <span class="font-semibold text-slate-700">{{ $cuti->duration }} hari</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Sisa Cuti Saat Ini:</span>
                <span class="font-semibold text-slate-700">{{ $remaining ?? ($cuti->user->cuti_quota ?? 12) }} hari</span>
            </div>
            <div class="">
                <span class="text-slate-500">Alasan:</span>
                <p class="mt-1 text-slate-700">{{ $cuti->alasan }}</p>
            </div>
            @if($cuti->catatan)
            <div>
                <span class="text-slate-500">Catatan HRD:</span>
                <p class="mt-1 text-slate-700">{{ $cuti->catatan }}</p>
            </div>
            @endif
        </div>
    </div>

    @if($cuti->status === 'pending')
    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Form Persetujuan</h3>
    <form action="{{ route('hrd.cuti.update', $cuti) }}" method="POST" id="approvalForm">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="text-base font-medium text-slate-900">Status</label>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-center">
                            <input id="approved" name="status" type="radio" value="approved" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="approved" class="ml-3 block text-sm font-medium text-gray-700">Setujui</label>
                        </div>
                        <div class="flex items-center">
                            <input id="rejected" name="status" type="radio" value="rejected" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="rejected" class="ml-3 block text-sm font-medium text-gray-700">Tolak</label>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <div class="mt-1">
                        <textarea id="catatan" name="catatan" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Wajib diisi jika status ditolak.</p>
                    @error('catatan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Assign pengganti -->
    <div class="bg-white p-6 rounded-2xl shadow-lg mt-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Tugaskan Pengganti</h3>
        <p class="text-sm text-slate-500 mb-3">Pilih pengganti yang sesuai dengan pekerjaan/department. Sistem akan memberi notifikasi kepada pengganti untuk menerima atau menolak.</p>
        {{-- Current assigned substitute (if any) --}}
        @if($cuti->pengganti_id)
            <div class="mb-4 flex items-center justify-between border p-3 rounded-md bg-slate-50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-sm font-semibold text-slate-700">{{ strtoupper(substr($cuti->pengganti->name,0,1)) }}</div>
                    <div>
                        <div class="font-semibold text-slate-800">{{ $cuti->pengganti->name }} <span class="text-xs text-slate-500">{{ $cuti->pengganti->employee_id ?? '' }}</span></div>
                        <div class="text-xs text-slate-500">Pengganti ditugaskan</div>
                    </div>
                </div>
                <div>
                    @if($cuti->pengganti_status === 'accepted')
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-800 font-semibold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Bersedia
                        </span>
                    @elseif($cuti->pengganti_status === 'pending')
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-800 font-semibold text-sm">
                            Menunggu Konfirmasi
                        </span>
                    @elseif($cuti->pengganti_status === 'declined')
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-800 font-semibold text-sm">
                            Ditolak
                        </span>
                    @endif
                </div>
            </div>
        @endif
        <form action="{{ route('hrd.cuti.assignPengganti', $cuti) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="pengganti_id" class="block text-sm font-medium text-gray-700">Pilih Pengganti</label>
                    <select name="pengganti_id" id="pengganti_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">-- Cari Pengganti --</option>
                        @php
                            // Exclude the user who applied for cuti from candidate list
                            // Use optional() in case the related user record is missing
                            $dept = optional($cuti->user)->department;
                            $candidatesQuery = \App\Models\User::potentialSubstitutes($dept);
                            if (! empty($cuti->user_id)) {
                                $candidatesQuery->where('id', '<>', $cuti->user_id);
                            }
                            $candidates = $candidatesQuery->limit(50)->get();
                        @endphp
                        @foreach($candidates as $cand)
                            <option value="{{ $cand->id }}" {{ isset($cuti->pengganti_id) && $cuti->pengganti_id == $cand->id ? 'selected' : '' }}>{{ $cand->name }} ({{ $cand->employee_id ?? '-' }}) - {{ $cand->department ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 transition">Kirim Permintaan Pengganti</button>
                </div>
            </div>
        </form>
    </div>
    @endif

</div>
@endsection

@push('scripts')
        <script>
    (function(){
        const form = document.getElementById('approvalForm');
        if (!form) return;
        const remaining = {{ $remaining ?? ($cuti->user->cuti_quota ?? 12) }};
        const hasAssignedPengganti = {{ $cuti->pengganti_id ? 'true' : 'false' }};

        form.addEventListener('submit', function(e){
            const approveRadio = document.getElementById('approved');
            const penggantiSelect = document.getElementById('pengganti_id');

            // If the page has a pengganti dropdown and the user selected someone,
            // include that selection in the approval form so server can persist it
            // in the same request (submit pengganti + approve in one action).
            if (penggantiSelect && penggantiSelect.value && !form.querySelector('input[name="pengganti_id"]')) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'pengganti_id';
                hidden.value = penggantiSelect.value;
                form.appendChild(hidden);
            }

            // If approving and no pengganti selected (either via existing assignment or dropdown), ask for confirmation first
            if (approveRadio && approveRadio.checked) {
                const noSelected = !(hasAssignedPengganti || (penggantiSelect && penggantiSelect.value && penggantiSelect.value !== ''));
                if (noSelected) {
                    e.preventDefault();
                    const confirmNoPengganti = confirm('Belum ada pengganti yang dipilih. Apakah Anda yakin tidak perlu pengganti untuk pengajuan ini? Tekan OK untuk lanjut tanpa pengganti, atau Cancel untuk batalkan.');
                    if (!confirmNoPengganti) {
                        // user cancelled -> do not submit
                        return;
                    }
                    // user confirmed no pengganti -> mark form so server or logs can know (optional)
                    const note = document.createElement('input');
                    note.type = 'hidden';
                    note.name = 'no_pengganti_confirm';
                    note.value = '1';
                    form.appendChild(note);
                    // continue to next checks (e.g., remaining)
                }

                // If remaining is zero or less, ask for override confirmation
                if (remaining <= 0) {
                    e.preventDefault();
                    const confirmOverride = confirm('Sisa cuti pengguna sudah habis. Apakah Anda yakin ingin tetap menyetujui pengajuan ini (override)?');
                    if (confirmOverride) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'force_approve';
                        input.value = '1';
                        form.appendChild(input);
                        form.submit();
                    }
                    return;
                }
            }
            // If none of the special cases matched, allow submit to proceed normally
        });
    })();
</script>
@if(session('confirm_no_pengganti'))
<script>
    (function(){
        // When server requests confirmation, show the same confirmation dialog flow
        const form = document.getElementById('approvalForm');
        if (!form) return;
        const penggantiSelect = document.getElementById('pengganti_id');
        const confirmed = confirm('Belum memilih pengganti. Apakah Anda yakin tidak perlu pengganti untuk pengajuan ini? Tekan OK untuk lanjut tanpa pengganti, atau Cancel untuk batalkan.');
        if (confirmed) {
            const note = document.createElement('input');
            note.type = 'hidden';
            note.name = 'no_pengganti_confirm';
            note.value = '1';
            form.appendChild(note);
            form.submit();
        }
    })();
</script>
@endif
@endpush
