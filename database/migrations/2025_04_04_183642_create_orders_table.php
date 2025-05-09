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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('status');
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('snap_token')->nullable();
            $table->decimal('total_price', 10, 2);
            
            // Tim data
            $table->string('team_name');
            $table->string('institution');
            
            // Ketua Tim data
            $table->string('leader_name');
            $table->string('leader_npm');
            $table->integer('leader_semester');
            $table->string('leader_faculty');
            $table->string('leader_phone');
            $table->string('leader_email');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};