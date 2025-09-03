<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $u = App\Models\User::first();
    $c = App\Models\Cuti::first();
    if (! $u || ! $c) {
        echo "missing user or cuti\n";
        exit(0);
    }

    $m = App\Models\Message::create([
        'user_id' => $u->id,
        'cuti_id' => $c->id,
        'title' => 'script-test',
        'body' => 'body from script',
    ]);

    echo "created: " . $m->id . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
