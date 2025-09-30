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
        Schema::create('okupansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruang_id')->constrained('ruang')->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained('bidang')->onDelete('cascade');
            $table->integer('kapasitas');
            $table->integer('jumlah_pegawai_juli_2023')->nullable();
            $table->integer('jumlah_pegawai_januari_2024')->nullable();
            $table->decimal('okupansi_persen', 5, 2)->nullable();
            $table->date('tanggal_survei');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('okupansi');
    }
};
