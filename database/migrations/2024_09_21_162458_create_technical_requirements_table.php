<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('technical_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('os');
            $table->string('cpu');
            $table->string('gpu');
            $table->integer('ram');
            $table->integer('storage');

            $table->unsignedBigInteger('product_id');
            $table->index('product_id', 'technical_requirements_product_idx');
            $table->foreign('product_id', 'technical_requirements_product_fk')->on('products')->references('id')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_requirements');
    }
};
