<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_order_items', function (Blueprint $table) {
            $table->string('price_type')->nullable()->after('product_name');
            $table->integer('unit_equivalent')->nullable()->after('price_type');
        });
    }

    public function down(): void
    {
        Schema::table('public_order_items', function (Blueprint $table) {
            $table->dropColumn(['price_type', 'unit_equivalent']);
        });
    }
};
