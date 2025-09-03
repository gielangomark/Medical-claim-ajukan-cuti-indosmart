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
            if (!Schema::hasColumn('data_change_requests', 'request_type')) {
                $table->string('request_type')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('data_change_requests', 'new_data')) {
                $table->json('new_data')->nullable()->after('request_type');
            }
            if (!Schema::hasColumn('data_change_requests', 'proof_document_path')) {
                $table->string('proof_document_path')->nullable()->after('new_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_change_requests', function (Blueprint $table) {
            if (Schema::hasColumn('data_change_requests', 'request_type')) {
                $table->dropColumn('request_type');
            }
            if (Schema::hasColumn('data_change_requests', 'new_data')) {
                $table->dropColumn('new_data');
            }
            if (Schema::hasColumn('data_change_requests', 'proof_document_path')) {
                $table->dropColumn('proof_document_path');
            }
        });
    }
};
