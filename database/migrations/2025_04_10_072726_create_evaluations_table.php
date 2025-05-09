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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->comment('Juri ID');
            $table->foreignId('product_id')->constrained()->comment('Kompetisi ID');
            $table->integer('score')->comment('Nilai 1-100');
            $table->text('feedback')->nullable();
            $table->string('status')->default('submitted')->comment('submitted, approved, rejected');
            $table->timestamps();
            
            // Satu juri hanya bisa memberi satu penilaian untuk satu tim lomba
            $table->unique(['order_id', 'user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};