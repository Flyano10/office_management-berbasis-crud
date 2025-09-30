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
        Schema::table('gedung', function (Blueprint $table) {
            // Hapus kolom kota_id jika ada
            if (Schema::hasColumn('gedung', 'kota_id')) {
                $table->dropForeign(['kota_id']);
                $table->dropColumn('kota_id');
            }
            
            // Tambah kolom kantor_id jika belum ada
            if (!Schema::hasColumn('gedung', 'kantor_id')) {
                $table->foreignId('kantor_id')->constrained('kantor')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gedung', function (Blueprint $table) {
            // Kembalikan struktur asli jika diperlukan
            if (Schema::hasColumn('gedung', 'kantor_id')) {
                $table->dropForeign(['kantor_id']);
                $table->dropColumn('kantor_id');
            }
            
            if (!Schema::hasColumn('gedung', 'kota_id')) {
                $table->foreignId('kota_id')->constrained('kota')->onDelete('cascade');
            }
        });
    }
};
