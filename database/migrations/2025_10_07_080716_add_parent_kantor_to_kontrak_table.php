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
        Schema::table('kontrak', function (Blueprint $table) {
            $table->enum('parent_kantor', ['Pusat', 'SBU', 'Perwakilan', 'Gudang'])->nullable()->after('kantor_id');
            $table->string('parent_kantor_nama')->nullable()->after('parent_kantor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kontrak', function (Blueprint $table) {
            $table->dropColumn(['parent_kantor', 'parent_kantor_nama']);
        });
    }
};