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
        Schema::table('okupansi', function (Blueprint $table) {
            // Tambah kolom jml_pegawai_kontrak jika belum ada
            if (!Schema::hasColumn('okupansi', 'jml_pegawai_kontrak')) {
                $table->integer('jml_pegawai_kontrak')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('okupansi', function (Blueprint $table) {
            // Hapus kolom jml_pegawai_kontrak
            if (Schema::hasColumn('okupansi', 'jml_pegawai_kontrak')) {
                $table->dropColumn('jml_pegawai_kontrak');
            }
        });
    }
};