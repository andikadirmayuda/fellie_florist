<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_holds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->unsigned();
            $table->enum('status', ['hold', 'released']);
            $table->timestamp('released_at')->nullable();            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_holds');
    }
};
