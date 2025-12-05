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
        if (!Schema::hasTable('inventaris')) {
            return;
        }

        Schema::table('inventaris', function (Blueprint $table) {
            $table->foreign('kategori_id', 'inventaris_kategori_id_foreign')
                ->references('id')
                ->on('kategori_inventaris')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('lokasi_kantor_id', 'inventaris_lokasi_kantor_id_foreign')
                ->references('id')
                ->on('kantor')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('lokasi_gedung_id', 'inventaris_lokasi_gedung_id_foreign')
                ->references('id')
                ->on('gedung')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('lokasi_lantai_id', 'inventaris_lokasi_lantai_id_foreign')
                ->references('id')
                ->on('lantai')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('lokasi_ruang_id', 'inventaris_lokasi_ruang_id_foreign')
                ->references('id')
                ->on('ruang')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('bidang_id', 'inventaris_bidang_id_foreign')
                ->references('id')
                ->on('bidang')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('sub_bidang_id', 'inventaris_sub_bidang_id_foreign')
                ->references('id')
                ->on('sub_bidang')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('inventaris')) {
            return;
        }

        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropForeign('inventaris_kategori_id_foreign');
            $table->dropForeign('inventaris_lokasi_kantor_id_foreign');
            $table->dropForeign('inventaris_lokasi_gedung_id_foreign');
            $table->dropForeign('inventaris_lokasi_lantai_id_foreign');
            $table->dropForeign('inventaris_lokasi_ruang_id_foreign');
            $table->dropForeign('inventaris_bidang_id_foreign');
            $table->dropForeign('inventaris_sub_bidang_id_foreign');
        });
    }
};
