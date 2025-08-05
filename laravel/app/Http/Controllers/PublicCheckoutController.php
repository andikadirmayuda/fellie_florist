<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PushNotificationService;

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
            'notes' => 'nullable|string|max:1000',
            'custom_instructions' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $publicCode = bin2hex(random_bytes(8));

            // Debug: Log cart contents
            Log::info('Checkout Cart Contents:', $cart);

            Log::info('Creating public order with data:', [
                'public_code' => $publicCode,
                'customer_name' => $validated['customer_name'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'delivery_method' => $validated['delivery_method'],
                'destination' => $validated['destination'],
                'notes' => $validated['notes'],
                'wa_number' => $validated['wa_number'],
                'status' => 'pending',
                'payment_status' => 'waiting_confirmation',
            ]);

            $order = \App\Models\PublicOrder::create([
                'public_code' => $publicCode,
                'customer_name' => $validated['customer_name'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'delivery_method' => $validated['delivery_method'],
                'destination' => $validated['destination'],
                'notes' => $validated['notes'],
                'wa_number' => $validated['wa_number'],
                'status' => 'pending',
                'payment_status' => 'waiting_confirmation',
            ]);

            Log::info('Public order created successfully:', ['order_id' => $order->id, 'public_code' => $publicCode]);

            foreach ($cart as $cartKey => $item) {
                // Handle different product types
                $productId = null; // Default to null

                // For bouquet items, extract the numeric ID
                if (isset($item['type']) && $item['type'] === 'bouquet') {
                    // product_id for bouquet is like 'bouquet_8', extract the number
                    if (preg_match('/bouquet_(\d+)/', $item['id'], $matches)) {
                        $productId = $matches[1]; // Get the numeric bouquet ID
                    }
                } elseif (isset($item['type']) && $item['type'] === 'custom_bouquet') {
                    // For custom bouquet, set product_id to null since we use custom_bouquet_id
                    $productId = null;
                } else {
                    // For regular products, use the id directly
                    $productId = $item['id'];
                }

                $orderItemData = [
                    'product_id' => $productId,
                    'product_name' => $item['name'],
                    'price_type' => $item['price_type'] ?? 'default',
                    'unit_equivalent' => 1,
                    'quantity' => $item['qty'],
                    'price' => $item['price'] ?? 0,
                    'item_type' => $item['type'] ?? 'product', // Store the item type
                ];

                // For custom bouquet, add the custom_bouquet_id
                if (isset($item['type']) && $item['type'] === 'custom_bouquet') {
                    $orderItemData['custom_bouquet_id'] = $item['custom_bouquet_id'] ?? null;
                    // Add custom instructions if provided
                    if (!empty($validated['custom_instructions'])) {
                        $orderItemData['custom_instructions'] = $validated['custom_instructions'];
                    }
                    // Add reference image if exists
                    if (!empty($item['image'])) {
                        $orderItemData['reference_image'] = $item['image'];
                    }
                }

                // Add greeting card to product_name if it's a bouquet with greeting card
                if (isset($item['type']) && $item['type'] === 'bouquet' && !empty($item['greeting_card'])) {
                    $orderItemData['product_name'] .= ' (Kartu Ucapan: "' . substr($item['greeting_card'], 0, 50) .
                        (strlen($item['greeting_card']) > 50 ? '...")' : '")');
                }

                // Add components info for custom bouquet
                if (isset($item['type']) && $item['type'] === 'custom_bouquet' && !empty($item['components_summary'])) {
                    $components = is_array($item['components_summary']) ?
                        implode(', ', array_slice($item['components_summary'], 0, 3)) :
                        $item['components_summary'];
                    $orderItemData['product_name'] .= ' (Komponen: ' . $components . ')';
                }

                // Debug: Log order item data
                Log::info('Creating Order Item:', $orderItemData);

                try {
                    $orderItem = $order->items()->create($orderItemData);
                    Log::info('Order Item Created Successfully:', ['id' => $orderItem->id]);
                } catch (\Exception $e) {
                    Log::error('Error creating order item:', [
                        'error' => $e->getMessage(),
                        'order_item_data' => $orderItemData
                    ]);
                    throw $e;
                }

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
                            'components' => $components->map(function ($c) {
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

            Log::info('About to commit transaction for order:', ['order_id' => $order->id]);
            DB::commit();
            Log::info('Transaction committed successfully for order:', ['order_id' => $order->id]);

            // Debug: Log successful order creation
            Log::info('Order created successfully:', ['public_code' => $publicCode, 'order_id' => $order->id]);

            // Trigger push notification untuk pesanan baru
            try {
                PushNotificationService::sendNewOrderNotification($order);
                Log::info('Push notification sent for new order:', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                Log::warning('Failed to send push notification for new order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

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
