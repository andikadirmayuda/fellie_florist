<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CustomBouquetItem;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update semua item di custom bouquet untuk menggunakan price_type 'custom_ikat'
        CustomBouquetItem::whereNotNull('id')->update([
            'price_type' => 'custom_ikat'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini adalah fix data
    }
};
