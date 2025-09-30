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
        Schema::create('kantor', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kantor')->unique(); // IC-0001, IC-0002, dll
            $table->string('nama_kantor');
            $table->text('alamat');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignId('kota_id')->constrained('kota')->onDelete('cascade');
            $table->foreignId('jenis_kantor_id')->constrained('jenis_kantor')->onDelete('cascade');
            $table->foreignId('parent_kantor_id')->nullable()->constrained('kantor')->onDelete('set null');
            $table->string('status_kantor')->default('aktif'); // aktif, tidak_aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kantor');
    }
};
