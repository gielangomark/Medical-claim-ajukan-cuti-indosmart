<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Models\DataChangeRequest;
use Illuminate\Support\Facades\Storage;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = $argv[1] ?? null;
$filename = $argv[2] ?? null;
if (! $id || ! $filename) {
    echo "Usage: php scripts/relink_proof.php <id> <filename>\n";
    echo "Example: php scripts/relink_proof.php 12 bk12jQ5cYC26G4yCATDXR6gipG11Oua677wgNWyF.jpg\n";
    exit(1);
}

$r = DataChangeRequest::find($id);
if (! $r) {
    echo "NOT FOUND ROW id={$id}\n";
    exit(1);
}

$old = $r->proof_document_path;
$backupDir = __DIR__ . '/backups';
if (! is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}
$ts = date('Ymd_His');
$backupFile = $backupDir . "/proof_backup_{$id}_{$ts}.json";
file_put_contents($backupFile, json_encode([
    'id' => $r->id,
    'user_id' => $r->user_id,
    'old_proof_document_path' => $old,
    'updated_to' => "public/proofs/data-changes/{$filename}",
    'timestamp' => $ts,
], JSON_PRETTY_PRINT));

// Update DB
$r->proof_document_path = "public/proofs/data-changes/{$filename}";
$r->save();

echo "Backed up old value to: {$backupFile}\n";
echo "Updated id={$id} to public/proofs/data-changes/{$filename}\n";

// Verify existence on public disk (controller strips leading public/)
$path = preg_replace('/^public\//', '', $r->proof_document_path);
$disk = Storage::disk('public');
if ($disk->exists($path)) {
    echo "Disk check: EXISTS (public disk)\n";
    echo "Full path: " . $disk->path($path) . "\n";
    exit(0);
} else {
    echo "Disk check: NOT FOUND on public disk for path: {$path}\n";
    echo "You may need to upload the file to storage/app/public/proofs/data-changes/ or choose a different file.\n";
    exit(2);
}
