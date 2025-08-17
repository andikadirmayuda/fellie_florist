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
        Schema::create('stock_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('public_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('status', ['held', 'completed', 'released'])->default('held');
            $table->string('price_type')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            // Index untuk mempercepat query
            $table->index(['order_id', 'status']);
            $table->index(['product_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_holds');
    }
};
