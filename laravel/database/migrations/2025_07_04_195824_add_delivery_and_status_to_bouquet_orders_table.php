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
        Schema::table('bouquet_orders', function (Blueprint $table) {
            $table->string('status')->default('diproses')->after('total_price');
            $table->string('delivery_method')->nullable()->after('status');
            $table->string('delivery_note')->nullable()->after('delivery_method');
            $table->dateTime('delivery_at')->nullable()->after('delivery_note');
            $table->dateTime('pickup_at')->nullable()->after('delivery_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bouquet_orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'delivery_method', 'delivery_note', 'delivery_at', 'pickup_at']);
        });
    }
};
