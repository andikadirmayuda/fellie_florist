<?php

namespace App\Http\Controllers;

use App\Models\PublicOrder;
use Illuminate\Http\Request;

class AdminPublicOrderController extends Controller
{
    public function index()
    {
        $orders = PublicOrder::orderByDesc('created_at')->paginate(20);
        return view('admin.public_orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = PublicOrder::with('items')->findOrFail($id);
        return view('admin.public_orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = \App\Models\PublicOrder::with('items')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->input('status');
        if (!in_array($newStatus, ['pending', 'processed', 'completed', 'cancelled'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        // Batasi perubahan status:
        // - Jika sudah completed/cancelled, tidak bisa diubah lagi
        if (in_array($oldStatus, ['completed', 'cancelled'])) {
            return back()->with('error', 'Status pesanan sudah final dan tidak dapat diubah lagi.');
        }
        // - Jika processed, hanya bisa ke completed
        if ($oldStatus === 'processed' && $newStatus !== 'completed') {
            return back()->with('error', 'Status Diproses hanya bisa diubah ke Selesai.');
        }
        // - Jika pending, bisa ke processed/completed/cancelled
        if ($oldStatus === 'pending') {
            if ($newStatus === 'processed') {
                // Kurangi stok permanen, release hold
                foreach ($order->items as $item) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->reduceStock($item->quantity * $item->unit_equivalent, 'public_order', 'public_order:' . $order->id, 'Pesanan publik diproses');
                    }
                }
                $order->stock_holded = true;
            } elseif ($newStatus === 'cancelled') {
                // Kembalikan stok dari hold
                foreach ($order->items as $item) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->addStock($item->quantity * $item->unit_equivalent, 'public_order_cancel', 'public_order:' . $order->id, 'Pesanan publik dibatalkan');
                    }
                }
                $order->stock_holded = false;
            }
            // completed: tidak ada perubahan stok
        }
        $order->status = $newStatus;
        $order->save();
        return back()->with('success', 'Status pesanan berhasil diubah.');
    }
}
