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
        Schema::table('admin', function (Blueprint $table) {
            // Tambah kolom kantor_id (nullable, foreign ke kantor)
            $table->foreignId('kantor_id')->nullable()->constrained('kantor')->onDelete('set null')->after('bidang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            // Drop foreign key constraints dulu
            $table->dropForeign(['kantor_id']);

            // Drop kolom
            $table->dropColumn('kantor_id');
        });
    }
};
