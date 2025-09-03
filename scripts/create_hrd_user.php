<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$nik = 'HRD001';
$u = User::where('nik', $nik)->first();
if ($u) {
    $u->name = 'HRD User';
    $u->email = 'hrd@example.com';
    $u->department = 'hrd';
    $u->role = 'hrd';
    $u->password = Hash::make('password');
    $u->work_hours = 9;
    $u->save();
    echo "updated\n";
} else {
    User::create([
        'nik' => $nik,
        'name' => 'HRD User',
        'email' => 'hrd@example.com',
        'department' => 'hrd',
        'role' => 'hrd',
        'password' => Hash::make('password'),
        'work_hours' => 9,
    ]);
    echo "created\n";
}

echo 'total users: ' . User::count() . "\n";
