<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cuti', function (Blueprint $table) {
            $table->foreignId('pengganti_id')->nullable()->after('processed_by')->constrained('users')->nullOnDelete();
            $table->enum('pengganti_status', ['pending','accepted','declined'])->nullable()->default(null)->after('pengganti_id');
        });
    }

    public function down()
    {
        Schema::table('cuti', function (Blueprint $table) {
            $table->dropForeign(['pengganti_id']);
            $table->dropColumn(['pengganti_id', 'pengganti_status']);
        });
    }
};
