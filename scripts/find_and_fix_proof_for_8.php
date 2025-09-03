<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Storage;
$r = App\Models\DataChangeRequest::find(8);
if (! $r) { echo "NO RECORD\n"; exit; }
$created = strtotime($r->created_at);
$files = Storage::disk('public')->files('proofs/data-changes');
$best = null; $bestDiff = PHP_INT_MAX;
foreach ($files as $f) {
    $lm = Storage::disk('public')->lastModified($f);
    $diff = abs($lm - $created);
    if ($diff < $bestDiff) { $bestDiff = $diff; $best = $f; }
}
if (! $best) { echo "NO FILES\n"; exit; }
echo "Best match: $best (diff sec: $bestDiff)\n";
// Update record to point to this file
$r->proof_document_path = 'public/' . $best;
$r->save();
echo "Record updated. New proof_document_path: {$r->proof_document_path}\n";
