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
        Schema::table('realisasi', function (Blueprint $table) {
            // Add auto-fill fields from kontrak (cek dulu apakah sudah ada)
            if (!Schema::hasColumn('realisasi', 'no_perjanjian_pihak_1')) {
                $table->string('no_perjanjian_pihak_1')->nullable();
            }
            if (!Schema::hasColumn('realisasi', 'no_perjanjian_pihak_2')) {
                $table->string('no_perjanjian_pihak_2')->nullable();
            }
            if (!Schema::hasColumn('realisasi', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable();
            }
            if (!Schema::hasColumn('realisasi', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisasi', function (Blueprint $table) {
            // Drop auto-fill fields
            $table->dropColumn([
                'no_perjanjian_pihak_1', 'no_perjanjian_pihak_2', 
                'tanggal_mulai', 'tanggal_selesai'
            ]);
        });
    }
};
