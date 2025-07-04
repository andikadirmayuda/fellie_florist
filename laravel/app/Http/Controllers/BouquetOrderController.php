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
        // Ambil produk dan kategori
        $products = \App\Models\Product::with('prices')->where('current_stock', '>', 0)->get();
        $categories = \App\Models\Category::all();
        return view('bouquet.orders', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'wa_number' => 'required',
            'items' => 'required|string',
        ]);
        $items = json_decode($request->items, true);
        if (!is_array($items) || count($items) == 0) {
            return back()->withErrors(['items' => 'Item buket harus diisi minimal 1.'])->withInput();
        }
        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    return back()->withErrors(['items' => 'Produk tidak ditemukan.'])->withInput();
                }
                if ($product->current_stock < $item['qty']) {
                    return back()->withErrors(['items' => "Stok produk {$product->name} tidak cukup!"])->withInput();
                }
                $total += $item['price'] * $item['qty'];
            }
            $order = BouquetOrder::create([
                'customer_name' => $request->customer_name,
                'wa_number' => $request->wa_number,
                'notes' => $request->notes,
                'total_price' => $total,
            ]);
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                BouquetOrderItem::create([
                    'bouquet_order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
                $product->current_stock -= $item['qty'];
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
