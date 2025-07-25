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
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_reseller')->default(false)->after('city');
            $table->decimal('reseller_discount', 5, 2)->nullable()->after('is_reseller');
            $table->decimal('promo_discount', 5, 2)->nullable()->after('reseller_discount');
            $table->text('notes')->nullable()->after('promo_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['is_reseller', 'reseller_discount', 'promo_discount', 'notes']);
        });
    }
};
