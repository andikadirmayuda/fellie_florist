<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

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
                'price_type' => $item['price_type'] ?? 'default'
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
                'info' => 'Pesanan sedang menunggu konfirmasi stok dari admin. Anda akan dihubungi melalui WhatsApp untuk konfirmasi dan proses pembayaran.',
            ]);

            foreach ($cart as $cartKey => $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price_type' => $item['price_type'] ?? 'default',
                    'unit_equivalent' => 1,
                    'quantity' => $item['qty'],
                    'price' => $item['price'] ?? 0,
                ]);
            }

            DB::commit();
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