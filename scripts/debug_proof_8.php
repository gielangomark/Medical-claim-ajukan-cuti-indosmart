<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\DataChangeRequest;

$r = DataChangeRequest::find(8);
if (! $r) { echo "NO RECORD\n"; exit; }
$raw = $r->proof_document_path;
$path = preg_replace('/^public\//', '', $raw);
$disk = Storage::disk('public');
$exists = $disk->exists($path);
$full = $disk->path($path);
$size = $exists ? filesize($full) : 0;
$mime = $exists ? (function($p){
    if (function_exists('mime_content_type')) return mime_content_type($p);
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    return $finfo->file($p);
})($full) : 'n/a';

echo "DB raw: $raw\n";
echo "Storage path: $path\n";
echo "Exists: " . ($exists ? 'yes' : 'no') . "\n";
echo "Full path: $full\n";
echo "Filesize: $size bytes\n";
echo "MIME: $mime\n";

// print first 32 bytes as hex
if ($exists) {
    $h = bin2hex(substr(file_get_contents($full), 0, 32));
    echo "First32Hex: $h\n";
}
