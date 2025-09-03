<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Message;

$uid = $argv[1] ?? 2;
$user = User::find($uid);
if (! $user) { echo json_encode(['error' => 'user_not_found', 'id' => $uid]) . PHP_EOL; exit(1); }

$unreadCount = $user->unreadNotifications()->count();
$notifications = $user->notifications()->limit(10)->get()->map(function($n){ return ['id' => $n->id, 'read_at' => $n->read_at, 'data' => $n->data, 'created_at' => (string)$n->created_at]; })->toArray();
$messages = Message::where('user_id', $uid)->orderBy('created_at', 'desc')->limit(10)->get()->toArray();

echo json_encode(['user_id' => $uid, 'unread_notifications' => $unreadCount, 'notifications' => $notifications, 'messages' => $messages], JSON_PRETTY_PRINT) . PHP_EOL;
