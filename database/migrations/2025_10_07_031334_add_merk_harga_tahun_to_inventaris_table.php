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
        Schema::table('inventaris', function (Blueprint $table) {
            $table->string('merk')->nullable()->after('kondisi');
            $table->decimal('harga', 15, 2)->nullable()->after('merk');
            $table->integer('tahun')->nullable()->after('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropColumn(['merk', 'harga', 'tahun']);
        });
    }
};
