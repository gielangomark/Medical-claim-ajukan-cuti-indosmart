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
$basename = trim(basename($r->proof_document_path));
echo "DB basename: $basename\n";
$files = Illuminate\Support\Facades\Storage::disk('public')->files('proofs/data-changes');
$found = 0;
foreach ($files as $f) {
    if (stripos($f, substr($basename, 0, 8)) !== false) {
        echo "MATCH: $f\n";
        $found++;
    }
}
echo "Total files: " . count($files) . "\n";
echo "Matches: $found\n";
