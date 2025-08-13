<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;


class PushSubscriptionController extends Controller
{
    /**
     * Menyimpan endpoint subscription dari pengguna ke database.
     */
    public function store(Request $request)
    {
        // Ambil data subscription dari request frontend
        $subscriptionData = $request->getContent();

        // Simpan ke kolom 'push_subscription' pada user yang sedang login
        $request->user()->update(['push_subscription' => $subscriptionData]);

        return response()->json(['success' => true]);
    }

    /**
     * Mengirim notifikasi ke pengguna tertentu.
     * Method ini bisa dipanggil dari Controller lain.
     */
    public function sendNotification($user, array $payload)
    {
        // Cek jika pengguna memiliki data subscription
        if (!$user->push_subscription) {
            return; // Tidak melakukan apa-apa jika tidak ada subscription
        }

        // Atur otentikasi VAPID dari file .env
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:admin@aplikasianda.com', // Ganti dengan email Anda
                'publicKey' => env('VITE_VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        // Buat objek WebPush
        $webPush = new WebPush($auth);

        // Ambil data subscription dari database
        $subscription = Subscription::create(json_decode($user->push_subscription, true));

        // Kirim notifikasi dengan payload yang sudah disiapkan
        $webPush->queueNotification($subscription, json_encode($payload));

        // Flush (kirim semua) notifikasi dalam antrian
        foreach ($webPush->flush() as $report) {
            if (!$report->isSuccess()) {
                // Jika gagal (misal: subscription kedaluwarsa), hapus dari database
                // Log::error("Gagal mengirim ke {$report->getRequest()->getUri()->__toString()}: {$report->getReason()}");
                // $user->update(['push_subscription' => null]);
            }
        }
    }
}