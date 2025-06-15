<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockHold;
use App\Models\StockAdjustment;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryService
{
    /**
     * Menambah stok produk dan mencatat transaksi masuk
     */
    public function addStockIn(int $productId, int $quantity, string $notes, int $userId): array
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);

            $transaction = InventoryTransaction::create([
                'product_id' => $productId,
                'transaction_type' => 'stock_in',
                'quantity' => $quantity,
                'source' => 'manual',
                'notes' => $notes,
                'created_by' => $userId
            ]);

            $product->increment('current_stock', $quantity);

            DB::commit();

            return [
                'success' => true,
                'message' => "Berhasil menambah {$quantity} {$product->base_unit} ke stok {$product->name}"
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => "Gagal menambah stok: " . $e->getMessage()
            ];
        }
    }

    /**
     * Menahan stok untuk pesanan
     */
    public function holdStockForOrder(int $orderId, int $productId, int $quantity): array
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            
            // Cek stok tersedia
            $availableStock = $product->current_stock - $product->stockHolds()->active()->sum('quantity');
            
            if ($availableStock < $quantity) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$availableStock} {$product->base_unit}");
            }

            $stockHold = StockHold::create([
                'order_id' => $orderId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'status' => 'hold'
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Berhasil menahan {$quantity} {$product->base_unit} untuk pesanan #{$orderId}",
                'hold_id' => $stockHold->id
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => "Gagal menahan stok: " . $e->getMessage()
            ];
        }
    }

    /**
     * Melepas hold stok
     */    public function releaseStockHold(int $holdId, string $status, int $userId, ?string $notes = null): array
    {
        try {
            DB::beginTransaction();

            $stockHold = StockHold::findOrFail($holdId);
            $product = $stockHold->product;

            if ($stockHold->status !== 'hold') {
                throw new \Exception("Status hold sudah dilepas sebelumnya");
            }

            $stockHold->update([
                'status' => 'released',
                'released_at' => now()
            ]);

            // Jika status cancelled, kembalikan stok
            if ($status === 'cancelled') {
                // Catat penyesuaian
                $adjustment = StockAdjustment::create([
                    'product_id' => $product->id,
                    'adjustment_type' => 'other',
                    'quantity_before' => $product->current_stock,
                    'quantity_after' => $product->current_stock,
                    'reason' => $notes ?? "Pembatalan hold stok dari pesanan #{$stockHold->order_id}",
                    'adjusted_by' => $userId,
                    'adjustment_date' => now()
                ]);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Hold stok berhasil dilepas dengan status: {$status}"
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => "Gagal melepas hold: " . $e->getMessage()
            ];
        }
    }

    /**
     * Menyesuaikan stok produk
     */
    public function adjustStock(int $productId, int $newQuantity, string $reason, string $type, int $userId): array
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            $oldQuantity = $product->current_stock;

            // Buat record penyesuaian
            $adjustment = StockAdjustment::create([
                'product_id' => $productId,
                'adjustment_type' => $type,
                'quantity_before' => $oldQuantity,
                'quantity_after' => $newQuantity,
                'reason' => $reason,
                'adjusted_by' => $userId,
                'adjustment_date' => now()
            ]);

            DB::commit();

            $difference = $newQuantity - $oldQuantity;
            $action = $difference > 0 ? "Penambahan" : "Pengurangan";
            
            return [
                'success' => true,
                'message' => "{$action} stok sebanyak " . abs($difference) . " {$product->base_unit} berhasil dicatat"
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => "Gagal menyesuaikan stok: " . $e->getMessage()
            ];
        }
    }

    /**
     * Mengambil riwayat stok untuk ditampilkan dalam grafik
     */
    public function getStockHistory(int $productId, int $days = 30): array
    {
        try {
            $product = Product::findOrFail($productId);
            $startDate = Carbon::now()->subDays($days);

            // Ambil semua transaksi
            $transactions = InventoryTransaction::where('product_id', $productId)
                ->where('created_at', '>=', $startDate)
                ->with(['creator:id,name'])
                ->orderBy('created_at')
                ->get();

            // Format data untuk chart
            $chartData = [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Stok Masuk',
                        'data' => [],
                        'backgroundColor' => '#4CAF50'
                    ],
                    [
                        'label' => 'Stok Keluar',
                        'data' => [],
                        'backgroundColor' => '#F44336'
                    ],
                    [
                        'label' => 'Penyesuaian',
                        'data' => [],
                        'backgroundColor' => '#2196F3'
                    ]
                ]
            ];

            // Grup transaksi per tanggal
            $groupedData = $transactions->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

            foreach ($groupedData as $date => $dailyTransactions) {
                $chartData['labels'][] = Carbon::parse($date)->format('d/m');
                
                // Hitung total per tipe
                $stockIn = $dailyTransactions->where('transaction_type', 'stock_in')->sum('quantity');
                $stockOut = $dailyTransactions->where('transaction_type', 'stock_out')->sum('quantity');
                $adjustment = $dailyTransactions->where('transaction_type', 'adjustment')->sum('quantity');

                $chartData['datasets'][0]['data'][] = $stockIn;
                $chartData['datasets'][1]['data'][] = $stockOut;
                $chartData['datasets'][2]['data'][] = $adjustment;
            }

            return [
                'success' => true,
                'data' => $chartData,
                'current_stock' => $product->currentStock()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Gagal mengambil data: " . $e->getMessage()
            ];
        }
    }
}
