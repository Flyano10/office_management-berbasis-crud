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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->default('admin'); // admin, user, system
            $table->unsignedBigInteger('user_id')->nullable(); // ID dari user yang melakukan aksi
            $table->string('user_name')->nullable(); // Nama user untuk referensi
            $table->string('action'); // create, update, delete, login, logout, view
            $table->string('model_type')->nullable(); // Model yang diubah (Kantor, Gedung, dll)
            $table->unsignedBigInteger('model_id')->nullable(); // ID dari model yang diubah
            $table->string('model_name')->nullable(); // Nama model untuk referensi
            $table->json('old_values')->nullable(); // Nilai sebelum perubahan
            $table->json('new_values')->nullable(); // Nilai setelah perubahan
            $table->json('changed_fields')->nullable(); // Field yang berubah
            $table->string('ip_address')->nullable(); // IP address
            $table->string('user_agent')->nullable(); // User agent browser
            $table->string('url')->nullable(); // URL yang diakses
            $table->text('description')->nullable(); // Deskripsi aksi
            $table->json('metadata')->nullable(); // Data tambahan
            $table->timestamps();

            // Indexes untuk performa
            $table->index(['user_type', 'user_id']);
            $table->index(['model_type', 'model_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};