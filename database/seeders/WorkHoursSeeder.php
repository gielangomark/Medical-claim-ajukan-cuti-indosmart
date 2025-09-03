<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class WorkHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set a sensible default for users without work_hours
        User::whereNull('work_hours')->orWhere('work_hours', 0)->chunkById(100, function($users){
            foreach ($users as $u) {
                // HRD users get slightly longer default hours
                if (isset($u->role) && $u->role === 'hrd') {
                    $u->work_hours = 9;
                } else {
                    $u->work_hours = 8;
                }
                $u->save();
            }
        });
    }
}
