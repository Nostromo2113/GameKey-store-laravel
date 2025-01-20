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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // Связь с пользователем
            $table->unsignedBigInteger('user_id');
            $table->index('user_id', 'cart_user_idx');
            //при добавлении софт нужно будет оставить заказы.
            $table->foreign('user_id', 'cart_user_fk')->references('id')->on('users')->onDelete('cascade');


            // Общая стоимость корзины
            $table->decimal('total_price', 10, 2);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
