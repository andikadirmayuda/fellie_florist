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
        Schema::table('custom_bouquets', function (Blueprint $table) {
            $table->foreignId('public_order_id')->nullable()->constrained('public_orders')->onDelete('set null');
            $table->index('public_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_bouquets', function (Blueprint $table) {
            $table->dropForeign(['public_order_id']);
            $table->dropIndex(['public_order_id']);
            $table->dropColumn('public_order_id');
        });
    }
};
