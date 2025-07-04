<?php

namespace App\Http\Controllers;

use App\Models\BouquetOrder;
use App\Models\BouquetOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BouquetOrderController extends Controller
{
    public function index()
    {
        $orders = BouquetOrder::orderByDesc('created_at')->get();
        return view('bouquet.orders_index', compact('orders'));
    }

    public function create()
    {
        // Ambil produk dengan current_stock > 0
        $products = \App\Models\Product::where('current_stock', '>', 0)->get();
        return view('bouquet.orders', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'wa_number' => 'required',
            'flowers' => 'required|array',
            'flowers.*.product_id' => 'required|exists:products,id',
            'flowers.*.quantity' => 'required|integer|min:1',
        ]);
        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($request->flowers as $flower) {
                $product = Product::find($flower['product_id']);
                if ($product->stock < $flower['quantity']) {
                    return back()->withErrors(['msg' => "Stok bunga {$product->name} tidak cukup!"])->withInput();
                }
                $total += $product->price * $flower['quantity'];
            }
            $order = BouquetOrder::create([
                'customer_name' => $request->customer_name,
                'wa_number' => $request->wa_number,
                'notes' => $request->notes,
                'total_price' => $total,
            ]);
            foreach ($request->flowers as $flower) {
                $product = Product::find($flower['product_id']);
                BouquetOrderItem::create([
                    'bouquet_order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $flower['quantity'],
                    'price' => $product->price,
                ]);
                $product->stock -= $flower['quantity'];
                $product->save();
            }
            DB::commit();
            return redirect()->route('bouquet.orders.index')->with('success', 'Pemesanan buket berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Terjadi kesalahan.'])->withInput();
        }
    }

    public function show(BouquetOrder $order)
    {
        $order->load('items.product');
        return view('bouquet.orders_show', compact('order'));
    }

    public function edit(BouquetOrder $order)
    {
        $order->load('items');
        $products = Product::where('stock', '>', 0)->orWhereIn('id', $order->items->pluck('product_id'))->get();
        return view('bouquet.orders_edit', compact('order', 'products'));
    }

    public function update(Request $request, BouquetOrder $order)
    {
        // Implementasi update sesuai kebutuhan
    }

    public function destroy(BouquetOrder $order)
    {
        $order->delete();
        return redirect()->route('bouquet.orders.index')->with('success', 'Data dihapus!');
    }
}
