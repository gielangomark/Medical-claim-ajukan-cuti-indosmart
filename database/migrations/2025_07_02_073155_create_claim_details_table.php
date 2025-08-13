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
    Schema::create('claim_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('claim_id')->constrained()->onDelete('cascade');
        $table->date('transaction_date');
        $table->string('description');
        $table->string('patient_name');
        $table->decimal('amount', 15, 2);
        $table->string('proof_file_path'); // Path ke file bukti yang diupload
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_details');
    }
};
