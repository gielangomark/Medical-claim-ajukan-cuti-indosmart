@component('mail::message')
# Status Pengajuan Cuti

Halo {{ $cuti->user->name }},

Pengajuan cuti Anda telah {{ $cuti->status }}.

**Detail Pengajuan:**
- Tanggal Mulai: {{ $cuti->tanggal_mulai->format('d F Y') }}
- Tanggal Selesai: {{ $cuti->tanggal_selesai->format('d F Y') }}
- Jenis Cuti: {{ ucfirst($cuti->jenis_cuti) }}

@if(isset($remainingDays))
- Sisa Cuti: {{ $remainingDays }} hari
@endif

@if($cuti->status === 'rejected')
**Catatan:**
{{ $cuti->catatan }}
@endif

@component('mail::button', ['url' => route('pengajuan.cuti.show', $cuti)])
Lihat Detail
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
