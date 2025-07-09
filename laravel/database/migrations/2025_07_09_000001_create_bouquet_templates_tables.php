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
        // bouquet_categories
        if (!Schema::hasTable('bouquet_categories')) {
            Schema::create('bouquet_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        // bouquet_sizes
        if (!Schema::hasTable('bouquet_sizes')) {
            Schema::create('bouquet_sizes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        // bouquets (template)
        if (!Schema::hasTable('bouquets')) {
            Schema::create('bouquets', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreignId('category_id')->constrained('bouquet_categories');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        // bouquet_prices
        if (!Schema::hasTable('bouquet_prices')) {
            Schema::create('bouquet_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bouquet_id')->constrained('bouquets')->onDelete('cascade');
                $table->foreignId('size_id')->constrained('bouquet_sizes');
                $table->decimal('price', 12, 2);
                $table->timestamps();
            });
        }

        // bouquet_template_items
        if (!Schema::hasTable('bouquet_template_items')) {
            Schema::create('bouquet_template_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bouquet_id')->constrained('bouquets')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products');
                $table->integer('quantity');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bouquet_template_items');
        Schema::dropIfExists('bouquet_prices');
        Schema::dropIfExists('bouquets');
        Schema::dropIfExists('bouquet_sizes');
        Schema::dropIfExists('bouquet_categories');
    }
};
