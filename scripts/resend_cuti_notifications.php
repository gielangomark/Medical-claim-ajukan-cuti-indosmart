<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cuti;
use App\Models\User;

$cuti = Cuti::find($argv[1] ?? 19);
if (! $cuti) {
    echo "Cuti not found\n";
    exit(1);
}

$hrUsers = User::where('role', 'hrd')->get();
foreach ($hrUsers as $hr) {
    try {
        $hr->notify(new App\Notifications\GeneralNotification(
            'Pengajuan Cuti Baru',
            "Ada pengajuan cuti baru dari {$cuti->user->name} untuk tanggal {$cuti->tanggal_mulai} sampai {$cuti->tanggal_selesai}.",
            url("/hrd/cuti/{$cuti->id}")
        ));
        echo "Notified HRD {$hr->id}\n";
    } catch (\Throwable $e) {
        echo "Failed to notify HRD {$hr->id}: {$e->getMessage()}\n";
    }
}

try {
    $cuti->user->notify(new App\Notifications\GeneralNotification(
        'Status Pengajuan Cuti',
        "Pengajuan cuti Anda (ID {$cuti->id}) saat ini: {$cuti->status}.",
        url("/pengajuan/cuti/{$cuti->id}")
    ));
    echo "Notified applicant {$cuti->user_id}\n";
} catch (\Throwable $e) {
    echo "Failed to notify applicant: {$e->getMessage()}\n";
}
