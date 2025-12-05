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
        Schema::table('kantor', function (Blueprint $table) {
            $table->decimal('luas_tanah', 12, 2)->nullable()->after('jenis_kepemilikan');
            $table->decimal('luas_bangunan', 12, 2)->nullable()->after('luas_tanah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kantor', function (Blueprint $table) {
            $table->dropColumn(['luas_tanah', 'luas_bangunan']);
        });
    }
};
