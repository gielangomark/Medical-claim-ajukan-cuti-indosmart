<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Medical Claim</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">

    <header class="bg-white shadow-sm">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                {{-- PERBAIKAN: Menambahkan tombol Kembali --}}
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="hidden sm:inline">Kembali ke Dasbor</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <x-notification-bell />
                    <span class="text-sm font-medium text-slate-600 hidden sm:block">{{ Auth::user()->name }}</span>
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

    <main class="container mx-auto max-w-3xl p-4 sm:p-6 lg:p-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Notifikasi</h1>
            <p class="text-slate-500 mt-1">Semua pembaruan terkait pengajuan Anda akan muncul di sini.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg">
            <ul class="divide-y divide-slate-200">
                @forelse ($notifications as $notification)
                    {{-- PERBAIKAN: Menambahkan indikator notifikasi belum dibaca --}}
                    <li class="relative p-4 hover:bg-slate-50 transition {{ !$notification->read_at ? 'bg-blue-50' : '' }}">
                        <div class="block">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800">{{ $notification->data['title'] }}</p>
                                    <p class="text-sm text-slate-600">{{ $notification->data['message'] }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            {{-- If this notification is about pengganti for cuti, show accept/decline buttons for assigned user --}}
                            @php
                                $meta = $notification->data['meta'] ?? [];
                                $isPengganti = isset($meta['type']) && $meta['type'] === 'pengganti';
                                $cutiId = $meta['cuti_id'] ?? null;
                                $toUser = $meta['to_user_id'] ?? null;
                            @endphp

                            @if($isPengganti && $toUser && auth()->id() == $toUser)
                                <div class="mt-3 flex items-center space-x-2">
                                    <form method="POST" action="{{ route('cuti.respondPengganti', $cutiId ?? 0) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white rounded-md text-sm">Terima</button>
                                    </form>
                                    <form method="POST" action="{{ route('cuti.respondPengganti', $cutiId ?? 0) }}">
                                        @csrf
                                        <input type="hidden" name="action" value="decline">
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white rounded-md text-sm">Tolak</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        {{-- Indikator titik biru untuk notifikasi yang belum dibaca --}}
                        @if (!$notification->read_at)
                            <div class="absolute top-4 right-4 h-3 w-3 rounded-full bg-blue-500" title="Belum dibaca"></div>
                        @endif
                    </li>
                @empty
                    <li class="p-10 text-center text-slate-500">
                        Tidak ada notifikasi.
                    </li>
                @endforelse
            </ul>
            
            @if($notifications->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </main>

</body>
</html>