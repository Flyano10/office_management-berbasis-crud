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
        if (!Schema::hasTable('kantor')) {
            return;
        }

        $columnsToDrop = [];

        if (Schema::hasColumn('kantor', 'parent_kantor')) {
            $columnsToDrop[] = 'parent_kantor';
        }

        if (Schema::hasColumn('kantor', 'parent_kantor_nama')) {
            $columnsToDrop[] = 'parent_kantor_nama';
        }

        if (!empty($columnsToDrop)) {
            Schema::table('kantor', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('kantor')) {
            return;
        }

        Schema::table('kantor', function (Blueprint $table) {
            if (!Schema::hasColumn('kantor', 'parent_kantor')) {
            $table->enum('parent_kantor', ['PUSAT', 'SBU', 'PERWAKILAN', 'GUDANG'])->nullable();
            }

            if (!Schema::hasColumn('kantor', 'parent_kantor_nama')) {
            $table->string('parent_kantor_nama')->nullable();
            }
        });
    }
};
