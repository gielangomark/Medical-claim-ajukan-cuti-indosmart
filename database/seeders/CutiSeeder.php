<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuti;
use App\Models\User;
use Carbon\Carbon;

class CutiSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada user dengan role HRD
        $hrdUser = User::where('role', 'hrd')->first();
        if (!$hrdUser) {
            $hrdUser = User::create([
                'name' => 'HRD Test',
                'email' => 'hrd@test.com',
                'employee_id' => '12345678',
                'password' => bcrypt('password'),
                'role' => 'hrd'
            ]);
        }

        // Buat beberapa data cuti test
        Cuti::create([
            'user_id' => $hrdUser->id,
            'tanggal_mulai' => Carbon::now()->addDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(3),
            'jenis_cuti' => 'tahunan',
            'alasan' => 'Test cuti 1',
            'status' => 'pending'
        ]);

        Cuti::create([
            'user_id' => $hrdUser->id,
            'tanggal_mulai' => Carbon::now()->addDays(5),
            'tanggal_selesai' => Carbon::now()->addDays(7),
            'jenis_cuti' => 'tahunan',
            'alasan' => 'Test cuti 2',
            'status' => 'pending'
        ]);
    }
}
