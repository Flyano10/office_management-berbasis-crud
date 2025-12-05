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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('kode_inventaris', 50)->unique();
            $table->foreignId('kategori_id');
            $table->integer('jumlah')->default(1);
            $table->enum('kondisi', ['Baru', 'Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->foreignId('lokasi_kantor_id')->nullable();
            $table->foreignId('lokasi_gedung_id')->nullable();
            $table->foreignId('lokasi_lantai_id')->nullable();
            $table->foreignId('lokasi_ruang_id')->nullable();
            $table->foreignId('bidang_id')->nullable();
            $table->foreignId('sub_bidang_id')->nullable();
            $table->timestamp('tanggal_input')->useCurrent();
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
