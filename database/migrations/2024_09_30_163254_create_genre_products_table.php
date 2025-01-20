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
        Schema::create('genre_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');
            $table->index('product_id', 'genre_product_product_idx');
            $table->foreign('product_id', 'genre_product_product_fk')->on('products')->references('id')->onDelete('cascade');

            $table->unsignedBigInteger('genre_id');
            $table->index('genre_id', 'genre_product_genre_idx');
            $table->foreign('genre_id', 'genre_product_genre_fk')->on('genres')->references('id')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_products');
    }
};
