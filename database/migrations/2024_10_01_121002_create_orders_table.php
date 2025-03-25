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

            $table->unsignedInteger('order_number')->unique()->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->index('user_id', 'orders_user_idx');
            $table->foreign('user_id', 'orders_user_fk')->references('id')->on('users')->onDelete('set null');

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
