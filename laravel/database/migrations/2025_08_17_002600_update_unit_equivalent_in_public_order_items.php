<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PublicOrderItem;
use App\Models\Product;
use App\Models\ProductPrice;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update semua item pesanan yang unit_equivalent nya masih null
        $items = PublicOrderItem::whereNull('unit_equivalent')->get();

        foreach ($items as $item) {
            if ($item->product_id) {
                $price = ProductPrice::where('product_id', $item->product_id)
                    ->where('type', $item->price_type)
                    ->first();
            } else {
                // Jika product_id null, cari produk berdasarkan nama
                $product = Product::where('name', $item->product_name)->first();
                if ($product) {
                    $price = $product->prices()
                        ->where('type', $item->price_type)
                        ->first();
                }
            }

            if ($price) {
                $item->unit_equivalent = $price->unit_equivalent;
                $item->save();

                // Jika status pesanan sudah processed dan stock belum dikurangi
                $order = $item->order;
                if ($order && $order->status === 'processed' && !$order->stock_holded) {
                    $stockReduction = $item->quantity * $item->unit_equivalent;

                    if ($product = ($item->product_id ? Product::find($item->product_id) : Product::where('name', $item->product_name)->first())) {
                        // Kurangi stok
                        $product->decrement('current_stock', $stockReduction);

                        // Catat di log inventory
                        \App\Models\InventoryLog::create([
                            'product_id' => $product->id,
                            'qty' => -$stockReduction,
                            'source' => 'public_order_product',
                            'reference_id' => $order->id,
                            'notes' => "Pengurangan stok (migration fix) - Pesanan #{$order->id}: {$item->quantity} {$item->price_type}",
                            'current_stock' => $product->current_stock
                        ]);
                    }
                }
            }
        }

        // Set stock_holded = true untuk semua pesanan yang statusnya processed
        \App\Models\PublicOrder::where('status', 'processed')
            ->where('stock_holded', 0)
            ->update(['stock_holded' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini adalah fix data
    }
};
