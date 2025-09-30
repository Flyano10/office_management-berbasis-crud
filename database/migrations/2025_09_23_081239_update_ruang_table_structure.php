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
        Schema::table('ruang', function (Blueprint $table) {
            // Drop existing columns that don't match form
            if (Schema::hasColumn('ruang', 'wing')) {
                $table->dropColumn('wing');
            }
            if (Schema::hasColumn('ruang', 'deskripsi_ruang')) {
                $table->dropColumn('deskripsi_ruang');
            }
            
            // Add new columns that match form (cek dulu apakah sudah ada)
            if (!Schema::hasColumn('ruang', 'kapasitas')) {
                $table->integer('kapasitas');
            }
            if (!Schema::hasColumn('ruang', 'status_ruang')) {
                $table->enum('status_ruang', ['tersedia', 'terisi', 'perbaikan'])->default('tersedia');
            }
            if (!Schema::hasColumn('ruang', 'bidang_id')) {
                $table->foreignId('bidang_id')->constrained('bidang')->onDelete('cascade');
            }
            if (!Schema::hasColumn('ruang', 'sub_bidang_id')) {
                $table->foreignId('sub_bidang_id')->nullable()->constrained('sub_bidang')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruang', function (Blueprint $table) {
            // Restore original columns
            $table->string('wing')->nullable();
            $table->text('deskripsi_ruang')->nullable();
            
            // Drop new columns
            $table->dropColumn(['kapasitas', 'status_ruang', 'bidang_id', 'sub_bidang_id']);
        });
    }
};
