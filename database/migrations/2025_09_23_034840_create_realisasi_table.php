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
        Schema::create('realisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_id')->constrained('kontrak')->onDelete('cascade');
            $table->date('tanggal_realisasi');
            $table->string('kompensasi'); // Pemeliharaan, Pembangunan
            $table->text('deskripsi');
            $table->decimal('rp_kompensasi', 15, 2);
            $table->string('lokasi_kantor')->nullable(); // UIW, UID, UIP, UIT
            $table->text('alamat')->nullable();
            $table->string('upload_berita_acara')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi');
    }
};
