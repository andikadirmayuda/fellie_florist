<?php

namespace App\Http\Controllers;

use App\Models\PublicOrder;
use App\Models\PublicOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicOrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'pickup_date' => 'required|date',
            'pickup_time' => 'nullable',
            'delivery_method' => 'required|string',
            'destination' => 'nullable|string',
            'wa_number' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.price_type' => 'required|string',
            'items.*.unit_equivalent' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Generate kode unik invoice publik
            $publicCode = bin2hex(random_bytes(8));
            $order = PublicOrder::create([
                'public_code' => $publicCode,
                'customer_name' => $validated['customer_name'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'] ?? null,
                'delivery_method' => $validated['delivery_method'],
                'destination' => $validated['destination'] ?? null,
                'wa_number' => $validated['wa_number'] ?? null,
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);
                // Ambil harga sesuai tipe harga yang dipilih user
                $selectedPrice = $product->prices()->where('type', $item['price_type'])->first();
                $price = $selectedPrice ? $selectedPrice->price : 0;
                $unitEquivalent = $item['unit_equivalent'] ?? ($selectedPrice ? $selectedPrice->unit_equivalent : 1);

                // Hitung total pengurangan stok (quantity x unit_equivalent)
                $totalQty = $item['quantity'] * $unitEquivalent;
                if (!$product->hasEnoughStock($totalQty)) {
                    throw new \Exception('Stok produk ' . $product->name . ' tidak cukup!');
                }
                $product->decrementStock($totalQty);

                // Catat log inventaris
                $product->inventoryLogs()->create([
                    'qty' => -$totalQty,
                    'source' => 'sale',
                    'reference_id' => 'public_order:' . $order->id,
                    'notes' => 'Pesanan publik',
                ]);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price_type' => $item['price_type'],
                    'unit_equivalent' => $unitEquivalent,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ]);
            }
            DB::commit();
            // Kirim link invoice publik ke frontend
            $invoiceUrl = url('/invoice/' . $order->public_code);
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'invoice_url' => $invoiceUrl,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tampilkan form edit pesanan publik
     */
    public function edit($public_code)
    {
        if (!config('public_order.enable_public_order_edit')) {
            abort(403, 'Fitur edit pesanan publik sedang dinonaktifkan.');
        }
        $order = PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        // Batasi edit hanya jika status masih pending
        if ($order->status !== 'pending') {
            abort(403, 'Pesanan tidak dapat diedit.');
        }
        // Ambil semua produk beserta harga dan satuan
        $products = \App\Models\Product::with(['prices' => function($q) {
            $q->orderBy('type');
        }])->where('is_active', 1)->get();
        return view('public.edit_order', compact('order', 'products'));
    }

    /**
     * Update pesanan publik
     */
    public function update(Request $request, $public_code)
    {
        $order = PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        if ($order->status !== 'pending') {
            abort(403, 'Pesanan tidak dapat diedit.');
        }
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'pickup_date' => 'required|date',
            'pickup_time' => 'nullable',
            'delivery_method' => 'required|string',
            'destination' => 'nullable|string',
            'wa_number' => 'nullable|string',
        ]);
        $order->update($validated);
        return redirect()->route('public.order.invoice', ['public_code' => $order->public_code])
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    /**
     * Tampilkan invoice publik berdasarkan public_code
     */
    public function publicInvoice($public_code)
    {
        $order = \App\Models\PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        return view('public.invoice', compact('order'));
    }

    /**
     * Tampilkan detail pemesanan publik (tracking)
     */
    public function publicOrderDetail($public_code)
    {
        $order = \App\Models\PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        return view('public.order_detail', compact('order'));
    }

    /**
     * Form & hasil tracking pesanan publik berdasarkan nomor WhatsApp
     */
    public function trackOrderForm(Request $request)
    {
        $orders = collect();
        $wa_number = $request->get('wa_number');
        if ($wa_number) {
            $orders = \App\Models\PublicOrder::where('wa_number', $wa_number)->with('items')->orderByDesc('created_at')->get();
        }
        return view('public.track_order', compact('orders', 'wa_number'));
    }
}
