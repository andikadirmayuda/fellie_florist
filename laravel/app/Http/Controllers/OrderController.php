<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Generate nomor order dengan format ORD-DDMMYYYY###
     */
    private function generateOrderNumber(): string
    {
        $date = now()->format('dmY');
        $lastOrder = Order::where('order_number', 'LIKE', "ORD-{$date}%")
            ->orderBy('order_number', 'desc')
            ->first();

        $counter = 1;
        if ($lastOrder) {
            $lastCounter = (int) substr($lastOrder->order_number, -3);
            $counter = $lastCounter + 1;
        }

        return sprintf("ORD-%s%03d", $date, $counter);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('customer')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with('prices')->get();
        return view('orders.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.price_type' => 'required|string',
            ]);

            DB::beginTransaction();

            // Create the order first
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $validated['customer_id'],
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;
            // Process each item
            foreach ($validated['items'] as $index => $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Validasi stok
                if ($product->current_stock < $item['qty']) {
                    throw new \Exception("Stok tidak cukup untuk produk {$product->name}. Stok tersedia: {$product->current_stock}");
                }

                $productPrice = $product->prices()->where('type', $item['price_type'])->first();
                
                if (!$productPrice) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->withErrors(["items.{$index}.price_type" => "Harga tidak tersedia untuk produk {$product->name} dengan tipe {$item['price_type']}"]);
                }

                $price = $productPrice->price;
                
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $price,
                    'price_type' => $item['price_type']
                ]);

                $total += $price * $item['qty'];
            }

            $order->update(['total' => $total]);

            // Hold stock untuk pesanan
            $this->inventoryService->holdStock($order);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'stockHolds']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $products = Product::with('prices')->get();
        return view('orders.edit', compact('order', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price_type' => 'required|string',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        try {
            DB::beginTransaction();

            // Update status
            $order->update(['status' => $validated['status']]);

            // Jika status masih pending, update items
            if ($order->status === 'pending') {
                // Lepaskan stock hold yang ada
                $this->inventoryService->releaseStock($order);
                
                // Hapus item yang ada
                $order->items()->delete();
                
                // Tambahkan item baru
                $total = 0;
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $productPrice = $product->prices()->where('type', $item['price_type'])->first();
                    
                    if (!$productPrice) {
                        throw new \Exception("Harga tidak tersedia untuk produk {$product->name}");
                    }

                    // Validasi stok
                    if ($product->current_stock < $item['qty']) {
                        throw new \Exception("Stok tidak cukup untuk produk {$product->name}. Stok tersedia: {$product->current_stock}");
                    }

                    $price = $productPrice->price;
                    
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'price' => $price,
                        'price_type' => $item['price_type']
                    ]);

                    $total += $price * $item['qty'];
                }

                $order->update(['total' => $total]);

                // Hold stock untuk pesanan yang diupdate
                $this->inventoryService->holdStock($order);
            }
            // Jika status berubah ke cancelled, kembalikan stok
            else if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->inventoryService->releaseStock($order);
            }
            // Jika status berubah ke completed, update inventory
            else if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                $this->inventoryService->completeOrder($order);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Status pesanan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error mengupdate pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // Jika pesanan belum selesai/batal, kembalikan stok
            if (!in_array($order->status, ['completed', 'cancelled'])) {
                $this->inventoryService->releaseStock($order);
            }

            $order->delete();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Pesanan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error menghapus pesanan: ' . $e->getMessage());
        }
    }
}
