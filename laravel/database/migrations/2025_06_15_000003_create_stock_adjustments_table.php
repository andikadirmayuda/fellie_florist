<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('adjustment_type', ['correction', 'damage', 'sample', 'other']);
            $table->integer('quantity_before')->unsigned();
            $table->integer('quantity_after')->unsigned();
            $table->text('reason');
            $table->foreignId('adjusted_by')->constrained('users');
            $table->dateTime('adjustment_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
