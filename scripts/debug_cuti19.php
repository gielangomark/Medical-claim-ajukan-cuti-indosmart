<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cuti;
use App\Models\Message;
use App\Models\User;

$c = Cuti::find(19);
echo "CUTI:\n";
var_export($c ? $c->toArray() : null);

echo "\nMESSAGES:\n";
$m = Message::where('cuti_id',19)->get()->toArray();
var_export($m);

echo "\nHRD USERS:\n";
$h = User::where('role','hrd')->get()->toArray();
var_export($h);
