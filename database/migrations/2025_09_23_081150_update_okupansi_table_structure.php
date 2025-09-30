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
        Schema::table('okupansi', function (Blueprint $table) {
            // Drop existing columns that don't match form
            if (Schema::hasColumn('okupansi', 'kapasitas')) {
                $table->dropColumn('kapasitas');
            }
            if (Schema::hasColumn('okupansi', 'jumlah_pegawai_juli_2023')) {
                $table->dropColumn('jumlah_pegawai_juli_2023');
            }
            if (Schema::hasColumn('okupansi', 'jumlah_pegawai_januari_2024')) {
                $table->dropColumn('jumlah_pegawai_januari_2024');
            }
            if (Schema::hasColumn('okupansi', 'okupansi_persen')) {
                $table->dropColumn('okupansi_persen');
            }
            if (Schema::hasColumn('okupansi', 'tanggal_survei')) {
                $table->dropColumn('tanggal_survei');
            }
            
            // Add new columns that match form (cek dulu apakah sudah ada)
            if (!Schema::hasColumn('okupansi', 'sub_bidang_id')) {
                $table->foreignId('sub_bidang_id')->nullable()->constrained('sub_bidang')->onDelete('set null');
            }
            if (!Schema::hasColumn('okupansi', 'tanggal_okupansi')) {
                $table->date('tanggal_okupansi');
            }
            if (!Schema::hasColumn('okupansi', 'jml_pegawai_organik')) {
                $table->integer('jml_pegawai_organik')->default(0);
            }
            if (!Schema::hasColumn('okupansi', 'jml_pegawai_tad')) {
                $table->integer('jml_pegawai_tad')->default(0);
            }
            if (!Schema::hasColumn('okupansi', 'total_pegawai')) {
                $table->integer('total_pegawai')->default(0);
            }
            if (!Schema::hasColumn('okupansi', 'persentase_okupansi')) {
                $table->decimal('persentase_okupansi', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('okupansi', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('okupansi', function (Blueprint $table) {
            // Restore original columns
            $table->integer('kapasitas');
            $table->integer('jumlah_pegawai_juli_2023')->nullable();
            $table->integer('jumlah_pegawai_januari_2024')->nullable();
            $table->decimal('okupansi_persen', 5, 2)->nullable();
            $table->date('tanggal_survei');
            
            // Drop new columns
            $table->dropColumn([
                'sub_bidang_id', 'tanggal_okupansi', 'jml_pegawai_organik', 
                'jml_pegawai_tad', 'total_pegawai', 'persentase_okupansi', 'keterangan'
            ]);
        });
    }
};
