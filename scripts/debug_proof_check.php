<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Models\DataChangeRequest;
use Illuminate\Support\Facades\Storage;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = $argv[1] ?? 12;
$r = DataChangeRequest::find($id);
if (! $r) {
    echo "NOT FOUND ROW\n";
    exit(0);
}
$raw = $r->proof_document_path;
echo "id: {$r->id}\n";
echo "user_id: {$r->user_id}\n";
echo "raw: ".($raw ?? 'NULL')."\n";
$san = trim(str_replace(["\r","\n"], '', $raw ?? ''));
$path = preg_replace('/^public\//', '', $san);
echo "sanitized: {$path}\n";
$disk = Storage::disk('public');
if ($disk->exists($path)) {
    echo "exists: YES\n";
    echo "full: ".$disk->path($path)."\n";
} else {
    echo "exists: NO\n";
}
