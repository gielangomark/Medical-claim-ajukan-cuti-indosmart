<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
      {
                Schema::table('claim_details', function (Blueprint $table) {
                    // Perintahkan database untuk mengubah kolom ini agar boleh kosong (nullable)
                    $table->string('proof_file_path')->nullable()->change();
                });
            }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claim_details', function (Blueprint $table) {
            //
        });
    }
};
