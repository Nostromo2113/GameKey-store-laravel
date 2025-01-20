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
        Schema::create('order_products_activation_keys', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_product_id');
            $table->foreign
            ('order_product_id', 'order_product_order_product_fk')->on('order_products')->references('id')->onDelete('cascade');

            $table->unsignedBigInteger('activation_key_id');
            $table->foreign
            ('activation_key_id', 'activation_key_activation_key_fk')->on('activation_keys')->references('id')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products_activation_keys');
    }
};
