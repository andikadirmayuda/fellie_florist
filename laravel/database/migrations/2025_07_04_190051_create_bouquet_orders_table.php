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
        Schema::create('bouquet_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('wa_number');
            $table->text('notes')->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bouquet_orders');
    }
};
