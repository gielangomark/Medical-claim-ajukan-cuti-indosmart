<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount;

    public function __construct()
    {
        $this->unreadCount = Auth::user() ? Auth::user()->unreadNotifications()->count() : 0;
    }

    public function render(): View|Closure|string
    {
        return view('components.notification-bell');
    }
}
