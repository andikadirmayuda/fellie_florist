<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderWhatsAppController extends Controller
{
    // Tampilkan form order WhatsApp dengan produk ready stock
    public function form()
    {
        $products = Product::where('current_stock', '>', 0)->orderBy('name')->get();
        return view('orders.order-whatsapp', compact('products'));
    }

    // Simpan order dari form WhatsApp
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'product' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'address' => 'required|string|max:500',
            ]);

            $product = Product::where('name', $validated['product'])->first();
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 422);
            }

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'status' => 'pending',
                'total' => $product->prices()->first()?->price * $validated['quantity'] ?? 0,
                'delivery_address' => $validated['address'],
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $product->prices()->first()?->price ?? 0,
                'qty' => $validated['quantity'],
                'price_type' => $product->prices()->first()?->type ?? 'default',
            ]);

            return response()->json([
                'success' => true,
                'order' => [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'product' => $product->name,
                    'quantity' => $validated['quantity'],
                    'address' => $validated['address'],
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

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
}
