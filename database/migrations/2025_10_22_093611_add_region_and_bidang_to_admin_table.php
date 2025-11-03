<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Increase execution time untuk migration ini
        ini_set('max_execution_time', 300);
        
        Schema::table('admin', function (Blueprint $table) {
            // Tambah kolom region_id (nullable, foreign ke provinsi)
            $table->foreignId('region_id')->nullable()->constrained('provinsi')->onDelete('set null')->after('role');

            // Tambah kolom bidang_id (nullable, foreign ke bidang)
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->onDelete('set null')->after('region_id');
        });

        // Update enum role dengan raw SQL yang lebih efisien
        DB::statement("ALTER TABLE admin MODIFY COLUMN role ENUM('super_admin', 'admin_regional', 'admin', 'staf') DEFAULT 'admin'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Increase execution time untuk rollback
        ini_set('max_execution_time', 300);
        
        Schema::table('admin', function (Blueprint $table) {
            // Drop foreign key constraints dulu
            $table->dropForeign(['region_id']);
            $table->dropForeign(['bidang_id']);

            // Drop kolom
            $table->dropColumn(['region_id', 'bidang_id']);
        });

        // Revert enum role dengan raw SQL
        DB::statement("ALTER TABLE admin MODIFY COLUMN role ENUM('super_admin', 'admin') DEFAULT 'admin'");
    }
};
