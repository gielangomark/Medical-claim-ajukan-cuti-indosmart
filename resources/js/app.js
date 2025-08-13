import './bootstrap';

// 1. Impor Alpine dan plugin Focus
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

// 2. Jadikan Alpine bisa diakses secara global
window.Alpine = Alpine;

// 3. Daftarkan plugin Focus ke Alpine
Alpine.plugin(focus);

// 4. Jalankan Alpine
Alpine.start();

document.addEventListener("DOMContentLoaded", function() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.register('/sw.js').then(function (registration) {
            console.log('Service Worker berhasil didaftarkan.');
            askForNotificationPermission(registration);
        }).catch(error => console.error('Pendaftaran Service Worker gagal:', error));
    }
});

function askForNotificationPermission(registration) {
    Notification.requestPermission().then(function (permission) {
        if (permission === 'granted') {
            console.log('Izin notifikasi diberikan.');
            subscribeUserToPush(registration);
        }
    });
}

function subscribeUserToPush(registration) {
    // PERBAIKAN DI SINI: Gunakan import.meta.env untuk Vite
    const vapidPublicKey = import.meta.env.VITE_VAPID_PUBLIC_KEY;

    if (!vapidPublicKey) {
        console.error('VAPID Public Key tidak ditemukan. Pastikan sudah di-set di .env dengan prefix VITE_ dan restart server Vite.');
        return;
    }

    registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
    })
    .then(function (subscription) {
        sendSubscriptionToServer(subscription);
    })
    .catch(err => console.error('Gagal subscribe:', err));
}

function sendSubscriptionToServer(subscription) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/store-subscription', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(subscription)
    })
    .then(response => {
        if (!response.ok) {
            response.json().then(data => console.error('Gagal mengirim subscription:', data));
            throw new Error('Gagal mengirim subscription ke server.');
        }
        console.log('Subscription berhasil disimpan di server.');
    })
    .catch(error => console.error(error));
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}