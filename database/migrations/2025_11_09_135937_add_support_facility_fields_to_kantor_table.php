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
            $table->unsignedInteger('daya_listrik_va')->nullable()->after('luas_bangunan');
            $table->unsignedInteger('kapasitas_genset_kva')->nullable()->after('daya_listrik_va');
            $table->unsignedTinyInteger('jumlah_sumur')->nullable()->after('kapasitas_genset_kva');
            $table->unsignedTinyInteger('jumlah_septictank')->nullable()->after('jumlah_sumur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kantor', function (Blueprint $table) {
            $table->dropColumn([
                'daya_listrik_va',
                'kapasitas_genset_kva',
                'jumlah_sumur',
                'jumlah_septictank',
            ]);
        });
    }
};
