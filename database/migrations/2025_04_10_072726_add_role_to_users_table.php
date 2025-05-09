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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['kiw', 'admin', 'juri', 'peserta'])->default('peserta');
            $table->boolean('is_active')->default(true);
            $table->string('phone')->nullable();
            $table->string('institution')->nullable();
            $table->string('faculty')->nullable();
            $table->string('npm')->nullable();
            $table->integer('semester')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'phone', 'institution', 'faculty', 'npm', 'semester']);
        });
    }
};