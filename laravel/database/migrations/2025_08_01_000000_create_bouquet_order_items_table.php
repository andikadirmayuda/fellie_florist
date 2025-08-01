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
        Schema::create('bouquet_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bouquet_id');
            $table->unsignedBigInteger('order_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2)->nullable();
            $table->timestamps();

            $table->foreign('bouquet_id')->references('id')->on('bouquets')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bouquet_order_items');
    }
};
