<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
$uid = $argv[1] ?? 2;
$user = User::find($uid);
if (! $user) { echo "User not found\n"; exit(1); }
$notes = $user->notifications()->limit(20)->get()->toArray();
echo json_encode($notes, JSON_PRETTY_PRINT);
