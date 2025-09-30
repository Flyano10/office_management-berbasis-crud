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
            // Tambah kolom yang hilang di tabel kontrak
            if (!Schema::hasColumn('kontrak', 'nilai_kontrak')) {
                $table->decimal('nilai_kontrak', 15, 2)->nullable()->after('tanggal_selesai');
            }
            if (!Schema::hasColumn('kontrak', 'berita_acara')) {
                $table->string('berita_acara')->nullable()->after('status_perjanjian');
            }
            if (!Schema::hasColumn('kontrak', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('berita_acara');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kontrak', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            if (Schema::hasColumn('kontrak', 'nilai_kontrak')) {
                $table->dropColumn('nilai_kontrak');
            }
            if (Schema::hasColumn('kontrak', 'berita_acara')) {
                $table->dropColumn('berita_acara');
            }
            if (Schema::hasColumn('kontrak', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });
    }
};