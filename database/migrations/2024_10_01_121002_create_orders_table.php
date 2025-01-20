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

            // Связь с пользователем
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('order_number')->unique()->nullable()->after('id');
            $table->index('user_id', 'orders_user_idx');
            //при добавлении софт нужно будет оставить заказы.
            $table->foreign('user_id', 'orders_user_fk')->references('id')->on('users')->onDelete('cascade');


            // Общая стоимость заказа
            $table->decimal('total_price', 10, 2);

            // Статус заказа
            $table->string('status')->default('pending');

            // Время создания и обновления заказа
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
