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
        Schema::table('lantai', function (Blueprint $table) {
            // Drop existing columns that don't match form
            if (Schema::hasColumn('lantai', 'deskripsi_lantai')) {
                $table->dropColumn('deskripsi_lantai');
            }
            
            // Add new columns that match form (cek dulu apakah sudah ada)
            if (!Schema::hasColumn('lantai', 'nama_lantai')) {
                $table->string('nama_lantai');
            }
            if (!Schema::hasColumn('lantai', 'nomor_lantai')) {
                $table->integer('nomor_lantai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lantai', function (Blueprint $table) {
            // Restore original columns
            $table->text('deskripsi_lantai')->nullable();
            
            // Drop new columns
            $table->dropColumn(['nama_lantai', 'nomor_lantai']);
        });
    }
};
