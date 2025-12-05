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
            $table->enum('jenis_kepemilikan', ['tunai', 'non_tunai', 'non_pln'])
                ->default('tunai')
                ->after('status_kepemilikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kantor', function (Blueprint $table) {
            $table->dropColumn('jenis_kepemilikan');
        });
    }
};
