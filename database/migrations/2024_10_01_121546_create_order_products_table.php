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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();

            // Связь с заказом
            $table->unsignedBigInteger('order_id');
            $table->index('order_id', 'order_product_order_idx');
            $table->foreign('order_id', 'order_product_order_fk')->references('id')->on('orders')->onDelete('cascade');

            // Связь с продуктом
            $table->unsignedBigInteger('product_id');
            $table->index('product_id', 'order_product_product_idx');
            $table->foreign('product_id', 'order_product_product_fk')->references('id')->on('products')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
