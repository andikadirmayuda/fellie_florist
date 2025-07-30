<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicCheckoutController extends Controller
{
    // Tampilkan form checkout
    public function show(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }
        
        // Format cart data untuk view
        $cartData = [];
        foreach ($cart as $cartKey => $item) {
            $cartData[] = [
                'product_name' => $item['name'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'price_type' => $item['price_type'] ?? 'default',
                'type' => $item['type'] ?? 'product',
                'greeting_card' => $item['greeting_card'] ?? null
            ];
        }
        
        return view('public.checkout', compact('cartData'));
    }

    // Proses checkout
    public function process(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('public.flowers')->with('error', 'Keranjang kosong.');
        }
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'wa_number' => 'required|string|max:20',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'delivery_method' => 'required|string',
            'destination' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $publicCode = bin2hex(random_bytes(8));
            
            // Debug: Log cart contents
            Log::info('Checkout Cart Contents:', $cart);
            
            $order = \App\Models\PublicOrder::create([
                'public_code' => $publicCode,
                'customer_name' => $validated['customer_name'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'delivery_method' => $validated['delivery_method'],
                'destination' => $validated['destination'],
                'wa_number' => $validated['wa_number'],
                'status' => 'pending',
                'payment_status' => 'waiting_confirmation',
            ]);

            foreach ($cart as $cartKey => $item) {
                // Handle different product types
                $productId = $item['id'];
                
                // For bouquet items, extract the numeric ID
                if (isset($item['type']) && $item['type'] === 'bouquet') {
                    // product_id for bouquet is like 'bouquet_8', extract the number
                    if (preg_match('/bouquet_(\d+)/', $productId, $matches)) {
                        $productId = $matches[1]; // Get the numeric bouquet ID
                    }
                }
                
                $orderItemData = [
                    'product_id' => $productId,
                    'product_name' => $item['name'],
                    'price_type' => $item['price_type'] ?? 'default',
                    'unit_equivalent' => 1,
                    'quantity' => $item['qty'],
                    'price' => $item['price'] ?? 0,
                ];
                
                // Add greeting card to product_name if it's a bouquet with greeting card
                if (isset($item['type']) && $item['type'] === 'bouquet' && !empty($item['greeting_card'])) {
                    $orderItemData['product_name'] .= ' (Kartu Ucapan: "' . substr($item['greeting_card'], 0, 50) . 
                        (strlen($item['greeting_card']) > 50 ? '...")' : '")');
                }
                
                // Debug: Log order item data
                Log::info('Creating Order Item:', $orderItemData);
                
                $order->items()->create($orderItemData);
                
                // Handle inventory deduction for bouquet components
                if (isset($item['type']) && $item['type'] === 'bouquet') {
                    $bouquet = \App\Models\Bouquet::find($productId);
                    if ($bouquet) {
                        // Get size_id from cart item to filter components correctly
                        $sizeId = $item['size_id'] ?? null;
                        
                        // Get components for this bouquet filtered by size_id
                        $componentsQuery = $bouquet->components()->with('product');
                        
                        if ($sizeId) {
                            $componentsQuery->where('size_id', $sizeId);
                        }
                        
                        $components = $componentsQuery->get();
                        
                        Log::info('Processing bouquet components:', [
                            'bouquet_id' => $productId,
                            'size_id' => $sizeId,
                            'bouquet_quantity' => $item['qty'],
                            'components_count' => $components->count(),
                            'components' => $components->map(function($c) {
                                return [
                                    'product_name' => $c->product->name ?? 'Unknown',
                                    'quantity' => $c->quantity,
                                    'size_id' => $c->size_id
                                ];
                            })
                        ]);
                        
                        foreach ($components as $component) {
                            if ($component->product) {
                                $requiredAmount = $component->quantity * $item['qty'];
                                
                                Log::info('Reducing component stock:', [
                                    'product_id' => $component->product->id,
                                    'product_name' => $component->product->name,
                                    'component_quantity' => $component->quantity,
                                    'bouquet_quantity' => $item['qty'],
                                    'total_required' => $requiredAmount,
                                    'current_stock' => $component->product->current_stock,
                                    'size_id' => $component->size_id
                                ]);
                                
                                // Check if there's enough stock
                                if (!$component->product->hasEnoughStock($requiredAmount)) {
                                    throw new \Exception("Stok tidak mencukupi untuk {$component->product->name}. Dibutuhkan: {$requiredAmount}, Tersedia: {$component->product->current_stock}");
                                }
                                
                                // Reduce the stock with proper inventory logging
                                $component->product->reduceStock(
                                    $requiredAmount,
                                    'Public_order',
                                    "order-{$order->id}",
                                    "Pesanan publik diproses - Bouquet: {$item['name']} (Qty: {$item['qty']}) - Size: {$item['price_type']}"
                                );
                                
                                Log::info('Stock reduced successfully:', [
                                    'product_id' => $component->product->id,
                                    'reduced_amount' => $requiredAmount,
                                    'new_stock' => $component->product->fresh()->current_stock
                                ]);
                            }
                        }
                    }
                } else {
                    // Handle regular product inventory (if needed)
                    $product = \App\Models\Product::find($productId);
                    if ($product) {
                        Log::info('Processing regular product:', [
                            'product_id' => $productId,
                            'quantity' => $item['qty'],
                            'current_stock' => $product->current_stock
                        ]);
                        
                        // Check if there's enough stock
                        if (!$product->hasEnoughStock($item['qty'])) {
                            throw new \Exception("Stok tidak mencukupi untuk {$product->name}. Dibutuhkan: {$item['qty']}, Tersedia: {$product->current_stock}");
                        }
                        
                        // Reduce the stock with proper inventory logging
                        $product->reduceStock(
                            $item['qty'],
                            'Public_order',
                            "order-{$order->id}",
                            "Pesanan publik diproses - Produk: {$item['name']} (Qty: {$item['qty']})"
                        );
                        
                        Log::info('Regular product stock reduced:', [
                            'product_id' => $productId,
                            'reduced_amount' => $item['qty'],
                            'new_stock' => $product->fresh()->current_stock
                        ]);
                    }
                }
            }

            DB::commit();
            
            // Debug: Log successful order creation
            Log::info('Order created successfully:', ['public_code' => $publicCode, 'order_id' => $order->id]);
            
            session()->forget('cart'); // Clear the cart
            
            // Simpan kode pesanan ke session untuk menampilkan icon di top bar
            session(['last_public_order_code' => $publicCode]);
            
            // Redirect ke halaman detail pesanan
            return redirect()->route('public.order.detail', ['public_code' => $publicCode])
                ->with('success', 'Pesanan berhasil dikirim! Anda dapat memantau status pesanan di halaman ini.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('public.flowers')->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }
}