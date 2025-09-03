<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('messages')) {
            return;
        }
        // Only run the information_schema check on MySQL connections
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Cek apakah kolom 'id' ada dan apakah sudah AUTO_INCREMENT (MySQL only)
        $row = DB::selectOne("SELECT EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'messages' AND COLUMN_NAME = 'id'");

        if (! $row) {
            // id column tidak ada — tambahkan sebagai AUTO_INCREMENT di posisi pertama
            DB::statement("ALTER TABLE `messages` ADD COLUMN `id` BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
            return;
        }

        if (strpos($row->EXTRA, 'auto_increment') === false) {
            // Ubah kolom id menjadi BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            DB::statement("ALTER TABLE `messages` MODIFY `id` BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT");
        }
    }

    public function down()
    {
        // Tidak otomatis revert karena perubahan pada kolom primary key sensitif
    }
};
