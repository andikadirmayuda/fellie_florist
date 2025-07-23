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
        $cart = session('public_cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }
        return view('public.checkout', compact('cart'));
    }

    // Proses checkout
    public function process(Request $request)
    {
        $cart = session('public_cart', []);
        if (empty($cart)) {
            return redirect()->route('public.cart.index')->with('error', 'Keranjang kosong.');
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
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'price_type' => $item['price_type'] ?? '-',
                    'unit_equivalent' => $item['unit_equivalent'] ?? 1,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] ?? 0,
                ]);
            }

            DB::commit();
            session()->forget('public_cart');
            // Simpan kode pesanan terakhir ke session untuk tombol "Lihat Detail Pemesanan"
            session(['last_public_order_code' => $publicCode]);
            // Redirect ke halaman detail pemesanan publik (tracking)
            return redirect()->route('public.order.detail', ['public_code' => $publicCode])->with('success', 'Pesanan berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('public.cart.index')->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }
}