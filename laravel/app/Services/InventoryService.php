<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Support\Collection;

class InventoryService
{
    /**
     * Get inventory movement history for a product
     */
    public function getProductHistory(Product $product): Collection
    {
        return $product->inventoryLogs()
            ->with('product')
            ->latest()
            ->get();
    }

    /**
     * Get products that need restocking
     */
    public function getProductsNeedingRestock(): Collection
    {
        return Product::needsRestock()->get();
    }

    /**
     * Process a stock adjustment
     */
    public function processStockAdjustment(Product $product, int $newQuantity, string $notes = null): InventoryLog
    {
        return $product->adjustStock(
            newQuantity: $newQuantity,
            referenceId: 'ADJ-' . time(),
            notes: $notes
        );
    }

    /**
     * Process stock addition (e.g., from purchase)
     */
    public function processStockAddition(Product $product, int $quantity, string $source, string $referenceId, ?string $notes = null): InventoryLog
    {
        return $product->addStock(
            quantity: $quantity,
            source: $source,
            referenceId: $referenceId,
            notes: $notes
        );
    }

    /**
     * Process stock reduction (e.g., from sale)
     */
    public function processStockReduction(Product $product, int $quantity, string $source, string $referenceId, ?string $notes = null): InventoryLog
    {
        if ($product->current_stock < $quantity) {
            throw new \Exception("Insufficient stock for product {$product->name}");
        }

        return $product->reduceStock(
            quantity: $quantity,
            source: $source,
            referenceId: $referenceId,
            notes: $notes
        );
    }
}
