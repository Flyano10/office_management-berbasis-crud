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
            // Tambah field yang ada di PDF tapi tidak ada di sistem
            $table->string('no_perjanjian_pihak_1')->nullable()->after('kontrak_id');
            $table->string('no_perjanjian_pihak_2')->nullable()->after('no_perjanjian_pihak_1');
            $table->date('tanggal_mulai')->nullable()->after('no_perjanjian_pihak_2');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisasi', function (Blueprint $table) {
            $table->dropColumn([
                'no_perjanjian_pihak_1',
                'no_perjanjian_pihak_2', 
                'tanggal_mulai',
                'tanggal_selesai'
            ]);
        });
    }
};
