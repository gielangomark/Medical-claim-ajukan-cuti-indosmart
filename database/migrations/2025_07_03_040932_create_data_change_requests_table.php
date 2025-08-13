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
    Schema::create('data_change_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('request_type'); // e.g., 'marital_status'
        $table->json('new_data'); // Menyimpan data baru dalam format JSON
        $table->string('proof_document_path')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->text('rejection_reason')->nullable();
        $table->foreignId('processed_by')->nullable()->constrained('users'); // ID HRD yang memproses
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_change_requests');
    }
};
