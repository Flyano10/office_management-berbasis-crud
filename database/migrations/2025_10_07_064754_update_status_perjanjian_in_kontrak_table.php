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
        Schema::table('kontrak', function (Blueprint $table) {
            // Update status_perjanjian column to use enum with new values
            $table->dropColumn('status_perjanjian');
        });
        
        Schema::table('kontrak', function (Blueprint $table) {
            $table->enum('status_perjanjian', ['Baru', 'Amandemen'])->default('Baru')->after('tanggal_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kontrak', function (Blueprint $table) {
            $table->dropColumn('status_perjanjian');
        });
        
        Schema::table('kontrak', function (Blueprint $table) {
            $table->enum('status_perjanjian', ['Aktif', 'Tidak Aktif'])->default('Aktif')->after('tanggal_selesai');
        });
    }
};