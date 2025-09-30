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
        // Drop foreign key constraints first
        Schema::table('okupansi', function (Blueprint $table) {
            if (Schema::hasColumn('okupansi', 'pegawai_id')) {
                $table->dropForeign(['pegawai_id']);
                $table->dropColumn('pegawai_id');
            }
        });

        // Drop the pegawai table
        Schema::dropIfExists('pegawai');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate pegawai table if needed
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            $table->string('nip')->unique();
            $table->string('email')->unique();
            $table->string('no_telepon');
            $table->string('jabatan');
            $table->string('jenis_pegawai');
            $table->string('status_pegawai');
            $table->foreignId('bidang_id')->constrained('bidang')->onDelete('cascade');
            $table->foreignId('sub_bidang_id')->nullable()->constrained('sub_bidang')->onDelete('set null');
            $table->timestamps();
        });
    }
};
