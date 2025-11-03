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
        Schema::create('admin_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('session_id', 255)->unique();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->timestamp('last_activity')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('cascade');
            $table->index(['admin_id', 'is_active']);
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_sessions');
    }
};
