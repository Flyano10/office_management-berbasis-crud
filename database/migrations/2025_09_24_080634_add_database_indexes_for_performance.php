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
        // Add indexes only if they don't exist
        $this->addIndexIfNotExists('kantor', 'status_kantor');
        $this->addIndexIfNotExists('kantor', 'kota_id');
        $this->addIndexIfNotExists('kantor', 'jenis_kantor_id');
        $this->addIndexIfNotExists('kantor', 'parent_kantor_id');
        $this->addIndexIfNotExists('kantor', 'created_at');
        
        $this->addIndexIfNotExists('gedung', 'status_gedung');
        $this->addIndexIfNotExists('gedung', 'kantor_id');
        $this->addIndexIfNotExists('gedung', 'created_at');
        
        $this->addIndexIfNotExists('lantai', 'gedung_id');
        $this->addIndexIfNotExists('lantai', 'created_at');
        
        $this->addIndexIfNotExists('ruang', 'lantai_id');
        $this->addIndexIfNotExists('ruang', 'created_at');
        
        $this->addIndexIfNotExists('okupansi', 'ruang_id');
        $this->addIndexIfNotExists('okupansi', 'created_at');
        
        $this->addIndexIfNotExists('kontrak', 'kantor_id');
        $this->addIndexIfNotExists('kontrak', 'status_perjanjian');
        $this->addIndexIfNotExists('kontrak', 'tanggal_mulai');
        $this->addIndexIfNotExists('kontrak', 'tanggal_selesai');
        $this->addIndexIfNotExists('kontrak', 'created_at');
        
        $this->addIndexIfNotExists('realisasi', 'kontrak_id');
        $this->addIndexIfNotExists('realisasi', 'tanggal_realisasi');
        $this->addIndexIfNotExists('realisasi', 'created_at');
        
        $this->addIndexIfNotExists('bidang', 'created_at');
        
        $this->addIndexIfNotExists('sub_bidang', 'bidang_id');
        $this->addIndexIfNotExists('sub_bidang', 'created_at');
        
        $this->addIndexIfNotExists('admin', 'role');
        $this->addIndexIfNotExists('admin', 'status');
        $this->addIndexIfNotExists('admin', 'created_at');
        
        $this->addIndexIfNotExists('audit_logs', 'user_id');
        $this->addIndexIfNotExists('audit_logs', 'model_type');
        $this->addIndexIfNotExists('audit_logs', 'action');
        $this->addIndexIfNotExists('audit_logs', 'created_at');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes if they exist
        $this->dropIndexIfExists('kantor', 'status_kantor');
        $this->dropIndexIfExists('kantor', 'kota_id');
        $this->dropIndexIfExists('kantor', 'jenis_kantor_id');
        $this->dropIndexIfExists('kantor', 'parent_kantor_id');
        $this->dropIndexIfExists('kantor', 'created_at');
        
        $this->dropIndexIfExists('gedung', 'status_gedung');
        $this->dropIndexIfExists('gedung', 'kantor_id');
        $this->dropIndexIfExists('gedung', 'created_at');
        
        $this->dropIndexIfExists('lantai', 'gedung_id');
        $this->dropIndexIfExists('lantai', 'created_at');
        
        $this->dropIndexIfExists('ruang', 'lantai_id');
        $this->dropIndexIfExists('ruang', 'created_at');
        
        $this->dropIndexIfExists('okupansi', 'ruang_id');
        $this->dropIndexIfExists('okupansi', 'created_at');
        
        $this->dropIndexIfExists('kontrak', 'kantor_id');
        $this->dropIndexIfExists('kontrak', 'status_perjanjian');
        $this->dropIndexIfExists('kontrak', 'tanggal_mulai');
        $this->dropIndexIfExists('kontrak', 'tanggal_selesai');
        $this->dropIndexIfExists('kontrak', 'created_at');
        
        $this->dropIndexIfExists('realisasi', 'kontrak_id');
        $this->dropIndexIfExists('realisasi', 'tanggal_realisasi');
        $this->dropIndexIfExists('realisasi', 'created_at');
        
        $this->dropIndexIfExists('bidang', 'created_at');
        
        $this->dropIndexIfExists('sub_bidang', 'bidang_id');
        $this->dropIndexIfExists('sub_bidang', 'created_at');
        
        $this->dropIndexIfExists('admin', 'role');
        $this->dropIndexIfExists('admin', 'status');
        $this->dropIndexIfExists('admin', 'created_at');
        
        $this->dropIndexIfExists('audit_logs', 'user_id');
        $this->dropIndexIfExists('audit_logs', 'model_type');
        $this->dropIndexIfExists('audit_logs', 'action');
        $this->dropIndexIfExists('audit_logs', 'created_at');
    }

    /**
     * Add index if it doesn't exist
     */
    private function addIndexIfNotExists(string $table, string $column): void
    {
        $indexName = $table . '_' . $column . '_index';
        
        try {
            DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` (`{$column}`)");
        } catch (Exception $e) {
            // Index already exists or other error, skip
        }
    }

    /**
     * Drop index if it exists
     */
    private function dropIndexIfExists(string $table, string $column): void
    {
        $indexName = $table . '_' . $column . '_index';
        
        try {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$indexName}`");
        } catch (Exception $e) {
            // Index doesn't exist or other error, skip
        }
    }
};