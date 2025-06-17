<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
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
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_id' => $validated['customer_id'],
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;
            // Process each item
            foreach ($validated['items'] as $index => $item) {
                $product = Product::findOrFail($item['product_id']);
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

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items.product']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,completed,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
