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

            //  Product relation
            $table->unsignedBigInteger('product_id');
            $table->index('product_id', 'cart_product_product_idx');
            $table->foreign('product_id', 'cart_product_product_fk')->references('id')->on('products')->onDelete('cascade');

            //  Cart relation
            $table->unsignedBigInteger('cart_id');
            $table->index('cart_id', 'cart_product_cart_idx');
            $table->foreign('cart_id', 'cart_product_cart_fk')->references('id')->on('carts')->onDelete('cascade');

            $table->integer('quantity');

            $table->timestamps();
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
