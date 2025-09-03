<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run MySQL-specific ALTER ... MODIFY on MySQL connections
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        // Use raw SQL to avoid requiring doctrine/dbal for column changes on local dev
        DB::statement("ALTER TABLE `data_change_requests` MODIFY `field_name` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `data_change_requests` MODIFY `old_value` TEXT NULL;");
        DB::statement("ALTER TABLE `data_change_requests` MODIFY `new_value` TEXT NULL;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        // Revert to NOT NULL with empty default to avoid data loss
        DB::statement("ALTER TABLE `data_change_requests` MODIFY `field_name` VARCHAR(255) NOT NULL DEFAULT '';");
        DB::statement("ALTER TABLE `data_change_requests` MODIFY `old_value` TEXT NOT NULL;");
        DB::statement("ALTER TABLE `data_change_requests` MODIFY `new_value` TEXT NOT NULL;");
    }
};
