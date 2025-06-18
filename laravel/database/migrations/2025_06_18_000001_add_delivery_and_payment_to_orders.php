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
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('pickup_date')->nullable()->after('total');
            $table->enum('delivery_method', ['pickup', 'gosend', 'gocar'])->default('pickup')->after('pickup_date');
            $table->decimal('down_payment', 12, 2)->default(0)->after('delivery_method');
            $table->string('delivery_address')->nullable()->after('down_payment');
            $table->decimal('delivery_fee', 12, 2)->default(0)->after('delivery_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_date',
                'delivery_method',
                'down_payment',
                'delivery_address',
                'delivery_fee'
            ]);
        });
    }
};
