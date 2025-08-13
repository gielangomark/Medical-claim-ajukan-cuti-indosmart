<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema; // <-- Import Schema

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key check
        Schema::disableForeignKeyConstraints();

        // Hapus user lama jika ada untuk menghindari duplikat
        User::truncate();

        // Aktifkan kembali foreign key check
        Schema::enableForeignKeyConstraints();

        // Buat user baru
        User::create([
            'nik' => '123456',
            'name' => 'Giela (Admin)',
            'email' => 'admin@indosmart.com',
            'department' => 'it',
            'password' => Hash::make('password'),
            // Set status default
            'gender' => 'pria',
            'marital_status' => 'lajang',
        ]);
    }
}