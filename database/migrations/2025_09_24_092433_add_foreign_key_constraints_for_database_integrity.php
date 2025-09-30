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
        // Add foreign key constraints for audit_logs table
        $this->addForeignKeyIfNotExists('audit_logs', 'user_id', 'admin', 'id', 'CASCADE', 'SET NULL');
        
        // Add additional foreign key constraints for better data integrity
        $this->addForeignKeyIfNotExists('kantor', 'kota_id', 'kota', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKeyIfNotExists('kantor', 'jenis_kantor_id', 'jenis_kantor', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKeyIfNotExists('kantor', 'parent_kantor_id', 'kantor', 'id', 'CASCADE', 'SET NULL');
        
        $this->addForeignKeyIfNotExists('gedung', 'kantor_id', 'kantor', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKeyIfNotExists('lantai', 'gedung_id', 'gedung', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKeyIfNotExists('ruang', 'lantai_id', 'lantai', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKeyIfNotExists('ruang', 'bidang_id', 'bidang', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKeyIfNotExists('ruang', 'sub_bidang_id', 'sub_bidang', 'id', 'CASCADE', 'SET NULL');
        
        $this->addForeignKeyIfNotExists('okupansi', 'ruang_id', 'ruang', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKeyIfNotExists('okupansi', 'bidang_id', 'bidang', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKeyIfNotExists('okupansi', 'sub_bidang_id', 'sub_bidang', 'id', 'CASCADE', 'SET NULL');
        
        $this->addForeignKeyIfNotExists('kontrak', 'kantor_id', 'kantor', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKeyIfNotExists('realisasi', 'kontrak_id', 'kontrak', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKeyIfNotExists('sub_bidang', 'bidang_id', 'bidang', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKeyIfNotExists('kota', 'provinsi_id', 'provinsi', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints
        $this->dropForeignKeyIfExists('audit_logs', 'user_id');
        
        $this->dropForeignKeyIfExists('kantor', 'kota_id');
        $this->dropForeignKeyIfExists('kantor', 'jenis_kantor_id');
        $this->dropForeignKeyIfExists('kantor', 'parent_kantor_id');
        
        $this->dropForeignKeyIfExists('gedung', 'kantor_id');
        
        $this->dropForeignKeyIfExists('lantai', 'gedung_id');
        
        $this->dropForeignKeyIfExists('ruang', 'lantai_id');
        $this->dropForeignKeyIfExists('ruang', 'bidang_id');
        $this->dropForeignKeyIfExists('ruang', 'sub_bidang_id');
        
        $this->dropForeignKeyIfExists('okupansi', 'ruang_id');
        $this->dropForeignKeyIfExists('okupansi', 'bidang_id');
        $this->dropForeignKeyIfExists('okupansi', 'sub_bidang_id');
        
        $this->dropForeignKeyIfExists('kontrak', 'kantor_id');
        
        $this->dropForeignKeyIfExists('realisasi', 'kontrak_id');
        
        $this->dropForeignKeyIfExists('sub_bidang', 'bidang_id');
        
        $this->dropForeignKeyIfExists('kota', 'provinsi_id');
    }

    /**
     * Add foreign key constraint if it doesn't exist
     */
    private function addForeignKeyIfNotExists(
        string $table, 
        string $column, 
        string $referencedTable, 
        string $referencedColumn, 
        string $onUpdate = 'CASCADE', 
        string $onDelete = 'CASCADE'
    ): void {
        $constraintName = "fk_{$table}_{$column}";
        
        try {
            DB::statement("
                ALTER TABLE `{$table}` 
                ADD CONSTRAINT `{$constraintName}` 
                FOREIGN KEY (`{$column}`) 
                REFERENCES `{$referencedTable}` (`{$referencedColumn}`) 
                ON UPDATE {$onUpdate} 
                ON DELETE {$onDelete}
            ");
        } catch (Exception $e) {
            // Foreign key already exists or other error, skip
        }
    }

    /**
     * Drop foreign key constraint if it exists
     */
    private function dropForeignKeyIfExists(string $table, string $column): void
    {
        $constraintName = "fk_{$table}_{$column}";
        
        try {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraintName}`");
        } catch (Exception $e) {
            // Foreign key doesn't exist or other error, skip
        }
    }
};