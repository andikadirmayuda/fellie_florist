<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function index()
    {
        $products = Product::with('category')
            ->when(request('search'), fn($q) => $q->search(request('search')))
            ->when(request('category'), fn($q) => $q->filterByCategory(request('category')))
            ->when(request('low_stock'), fn($q) => $q->needsRestock())
            ->paginate(10);

        return view('inventory.index', compact('products'));
    }

    public function history(Product $product)
    {
        $logs = $this->inventoryService->getProductHistory($product);
        
        return view('inventory.history', compact('product', 'logs'));
    }

    public function adjustForm(Product $product)
    {
        return view('inventory.adjust', compact('product'));
    }

    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        $this->inventoryService->processStockAdjustment(
            product: $product,
            newQuantity: $request->quantity,
            notes: $request->notes
        );

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Stock adjusted successfully');
    }
}
