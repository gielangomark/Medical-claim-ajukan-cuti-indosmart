<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$uid = $argv[1] ?? 2;
$user = User::find($uid);
if (! $user) { echo "user_not_found\n"; exit(1); }

$cnt = DB::table('notifications')->where('notifiable_type', 'App\\Models\\User')->where('notifiable_id', $uid)->update(['read_at' => null]);
$unread = $user->unreadNotifications()->count();
echo "updated_rows={$cnt}, unread_count={$unread}\n";
$notes = $user->notifications()->limit(10)->get()->map(function($n){ return ['id'=>$n->id,'read_at'=>$n->read_at,'data'=>$n->data,'created_at'=> (string)$n->created_at]; });
echo json_encode($notes, JSON_PRETTY_PRINT) . "\n";
