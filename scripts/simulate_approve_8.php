<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataChangeRequest;
use App\Http\Controllers\HRD\DataChangeApprovalController;
use Illuminate\Http\Request;

$r = DataChangeRequest::find(8);
if (! $r) { echo "NO RECORD\n"; exit; }

// Simulate HRD user
$hrdUser = \App\Models\User::where('department', 'hrd')->first();
if (! $hrdUser) { echo "NO HRD USER\n"; exit; }

// Set auth
\Illuminate\Support\Facades\Auth::login($hrdUser);

// Create fake request
$req = Request::create('/hrd/data-changes/'. $r->id, 'PUT', ['status' => 'approved']);
$controller = new DataChangeApprovalController();
$resp = $controller->update($req, $r);

echo "Done. Response type: " . (is_object($resp) ? get_class($resp) : gettype($resp)) . "\n";
