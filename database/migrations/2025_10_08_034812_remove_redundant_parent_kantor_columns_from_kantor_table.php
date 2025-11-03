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
        Schema::table('kantor', function (Blueprint $table) {
            // Hapus kolom redundant parent_kantor dan parent_kantor_nama
            $table->dropColumn(['parent_kantor', 'parent_kantor_nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kantor', function (Blueprint $table) {
            // Kembalikan kolom parent_kantor dan parent_kantor_nama
            $table->enum('parent_kantor', ['PUSAT', 'SBU', 'PERWAKILAN', 'GUDANG'])->nullable();
            $table->string('parent_kantor_nama')->nullable();
        });
    }
};
