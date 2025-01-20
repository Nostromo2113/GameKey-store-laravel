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
        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'cart_product_product_fk')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('cart_id');
            $table->foreign('cart_id', 'cart_product_cart_fk')->references('id')->on('carts')->onDelete('cascade');

            // Количество заказанного продукта
            $table->integer('quantity');

            // Цена продукта на момент заказа
            $table->decimal('price', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_products');
    }
};
