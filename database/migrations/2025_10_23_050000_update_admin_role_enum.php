<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum set for admin.role to include new roles
        DB::statement("ALTER TABLE `admin` MODIFY `role` ENUM('super_admin','admin_regional','manager_bidang','staf','admin') NOT NULL DEFAULT 'staf'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum set
        DB::statement("ALTER TABLE `admin` MODIFY `role` ENUM('super_admin','admin') NOT NULL DEFAULT 'admin'");
    }
};
