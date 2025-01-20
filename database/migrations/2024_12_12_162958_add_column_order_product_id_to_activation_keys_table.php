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
        Schema::table('activation_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('order_product_id')->nullable();
            $table->foreign('order_product_id', 'order_product_id_order_products_fk')
                ->on('order_products')
                ->references('id')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activation_keys', function (Blueprint $table) {
            $table->dropForeign('order_product_id_order_products_fk');
            $table->dropColumn('order_product_id');
        });
    }
};
