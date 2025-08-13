<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Ambil semua notifikasi milik user yang sedang login
        $notifications = Auth::user()->notifications()->paginate(15);

        // Tandai semua notifikasi yang belum dibaca sebagai sudah dibaca
        Auth::user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}