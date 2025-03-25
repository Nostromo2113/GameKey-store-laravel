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
        Schema::create('activation_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();

            //  Product relation
            $table->unsignedBigInteger('product_id');
            $table->index('product_id', 'activation_keys_product_idx');
            $table->foreign('product_id', 'activation_keys_product_fk')
                ->on('products')
                ->references('id')
                ->onDelete('cascade');

            //  OrderProduct relation
            $table->unsignedBigInteger('order_product_id')->nullable();
            $table->index('order_product_id', 'activation_keys_order_product_idx');
            $table->foreign('order_product_id', 'activation_keys_order_product_fk')
                ->on('order_products')
                ->references('id')
                ->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activation_keys');
    }
};
