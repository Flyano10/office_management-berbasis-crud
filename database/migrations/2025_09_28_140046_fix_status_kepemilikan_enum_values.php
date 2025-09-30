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
        // Update existing data first
        DB::table('kantor')->where('status_kepemilikan', 'milik')->update(['status_kepemilikan' => 'milik']);
        DB::table('kantor')->where('status_kepemilikan', 'milik_sendiri')->update(['status_kepemilikan' => 'milik']);
        DB::table('gedung')->where('status_kepemilikan', 'milik')->update(['status_kepemilikan' => 'milik']);
        DB::table('gedung')->where('status_kepemilikan', 'milik_sendiri')->update(['status_kepemilikan' => 'milik']);
        
        // Final enum with simple values
        DB::statement("ALTER TABLE kantor MODIFY COLUMN status_kepemilikan ENUM('milik', 'sewa') DEFAULT 'sewa'");
        DB::statement("ALTER TABLE gedung MODIFY COLUMN status_kepemilikan ENUM('milik', 'sewa') DEFAULT 'sewa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
