<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'work_hours')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('work_hours')->default(8)->nullable()->after('department');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'work_hours')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('work_hours');
            });
        }
    }
};
