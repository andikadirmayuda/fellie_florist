<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockHold;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockAdjustmentRequest;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Dashboard inventaris
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter berdasarkan produk
        if ($request->product_id) {
            $query->where('id', $request->product_id);
        }

        // Filter berdasarkan kategori
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Ambil produk dengan stok kritis
        $criticalStocks = $query->clone()
            ->needsRestock()
            ->with('category')
            ->get();

        // Ambil history transaksi
        $history = [];
        if ($request->product_id) {
            $history = $this->inventoryService->getStockHistory(
                $request->product_id,
                $request->get('days', 30)
            );
        }

        return view('inventory.index', [
            'criticalStocks' => $criticalStocks,
            'history' => $history,
            'products' => Product::all(['id', 'name']), // untuk dropdown filter
            'filters' => $request->only(['product_id', 'category_id', 'days'])
        ]);
    }

    /**
     * Form tambah stok
     */
    public function stockInForm()
    {
        return view('inventory.stock-in', [
            'products' => Product::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'current_stock', 'base_unit'])
        ]);
    }

    /**
     * Proses tambah stok
     */
    public function stockIn(StockInRequest $request)
    {
        $result = $this->inventoryService->addStockIn(
            $request->product_id,
            $request->quantity,
            $request->notes,
            auth()->id()
        );

        if ($result['success']) {
            return redirect()
                ->route('inventory.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /**
     * Form penyesuaian stok
     */
    public function adjustmentForm()
    {
        return view('inventory.adjustment', [
            'products' => Product::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'current_stock', 'base_unit']),
            'adjustmentTypes' => [
                'correction' => 'Koreksi Stok',
                'damage' => 'Kerusakan',
                'sample' => 'Sampel',
                'other' => 'Lainnya'
            ]
        ]);
    }

    /**
     * Proses penyesuaian stok
     */
    public function adjustStock(StockAdjustmentRequest $request)
    {
        $result = $this->inventoryService->adjustStock(
            $request->product_id,
            $request->new_quantity,
            $request->reason,
            $request->adjustment_type,
            auth()->id()
        );

        if ($result['success']) {
            return redirect()
                ->route('inventory.index')
                ->with('success', $result['message']);
        }

        return back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /**
     * Daftar stok yang di-hold
     */
    public function stockHolds(Request $request)
    {
        $query = StockHold::with(['product:id,name,base_unit'])
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->product_id, function ($q) use ($request) {
                return $q->where('product_id', $request->product_id);
            });

        $holds = $query->latest()
            ->paginate(20)
            ->withQueryString();

        return view('inventory.holds', [
            'holds' => $holds,
            'products' => Product::pluck('name', 'id'),
            'filters' => $request->only(['status', 'product_id'])
        ]);
    }

    /**
     * Release hold stok
     */
    public function releaseHold(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:released,cancelled',
            'notes' => 'nullable|string|max:255'
        ]);

        $result = $this->inventoryService->releaseStockHold(
            $id,
            $request->status,
            auth()->id(),
            $request->notes
        );

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
