<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE `admin` MODIFY `role` ENUM('super_admin','admin_regional','staf','admin') NOT NULL");
        DB::table('admin')->where('role', 'admin')->update(['role' => 'staf']);
        DB::statement("ALTER TABLE `admin` MODIFY `role` ENUM('super_admin','admin_regional','staf') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `admin` MODIFY `role` ENUM('super_admin','admin_regional','staf','admin') NOT NULL");
        DB::table('admin')->where('role', 'staf')->update(['role' => 'admin']);
    }
};
