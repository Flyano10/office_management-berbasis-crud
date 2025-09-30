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
        Schema::create('kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perjanjian');
            $table->string('no_perjanjian_pihak_1');
            $table->string('no_perjanjian_pihak_2');
            $table->string('asset_owner');
            $table->text('ruang_lingkup')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('sbu')->nullable();
            $table->string('peruntukan_kantor')->nullable(); // Kantor SBU | Kantor KP | Gudang
            $table->text('alamat');
            $table->foreignId('kantor_id')->constrained('kantor')->onDelete('cascade');
            $table->string('status_perjanjian')->default('baru'); // baru, amandemen, selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak');
    }
};
