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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->string('code')->unique()->nullable(); // Kode kompetisi
            $table->date('registration_start')->nullable(); // Tanggal mulai pendaftaran
            $table->date('registration_end')->nullable(); // Tanggal akhir pendaftaran
            $table->text('requirements')->nullable(); // Persyaratan kompetisi
            $table->text('prizes')->nullable(); // Hadiah kompetisi
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};