// Listener untuk event 'push' yang datang dari server
self.addEventListener('push', function (event) {
    if (!event.data) {
        console.log('Push event tanpa data.');
        return;
    }

    // Ambil data notifikasi (berupa JSON)
    const data = event.data.json();

    const title = data.title || 'Notifikasi Baru';
    const options = {
        body: data.body || 'Anda memiliki pesan baru.',
        icon: data.icon || '/images/icon.png', // Path ke ikon notifikasi
        badge: data.badge || '/images/badge.png', // Ikon kecil untuk Android
        // Menambahkan sound sesuai permintaan Anda
        sound: data.sound || '/sounds/notification.mp3',
        data: {
            url: data.url || '/' // URL yang akan dibuka saat notifikasi diklik
        }
    };

    // Tampilkan notifikasi
    event.waitUntil(self.registration.showNotification(title, options));
});

// Listener untuk event saat notifikasi diklik
self.addEventListener('notificationclick', function (event) {
    // Tutup notifikasi
    event.notification.close();

    // Buka URL yang ada di data notifikasi
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});