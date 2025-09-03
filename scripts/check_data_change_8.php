<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$r = App\Models\DataChangeRequest::find(8);
if (! $r) {
    echo "NOT FOUND\n";
    exit;
}
$path = preg_replace('/^public\//', '', $r->proof_document_path);
$exists = Illuminate\Support\Facades\Storage::disk('public')->exists($path) ? 'yes' : 'no';
echo "id:" . $r->id . "\n";
echo "proof:" . $r->proof_document_path . "\n";
echo "strip:" . $path . "\n";
echo "exists:" . $exists . "\n";
