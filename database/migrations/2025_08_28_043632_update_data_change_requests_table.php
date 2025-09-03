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
        Schema::table('data_change_requests', function (Blueprint $table) {
            // Drop kolom lama
            $table->dropColumn(['request_type', 'new_data', 'proof_document_path']);
            
            // Tambah kolom baru
            $table->string('field_name');  // Nama field yang diubah (phone, address, dll)
            $table->text('old_value')->nullable();  // Nilai lama
            $table->text('new_value');  // Nilai baru
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_change_requests', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['field_name', 'old_value', 'new_value']);
            
            // Kembalikan kolom lama
            $table->string('request_type');
            $table->json('new_data');
            $table->string('proof_document_path')->nullable();
        });
    }
};
