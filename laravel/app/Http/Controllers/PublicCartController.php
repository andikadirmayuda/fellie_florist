<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class PublicCartController extends Controller
{
    // Tampilkan keranjang belanja publik
    public function index(Request $request)
    {
        $cart = session('public_cart', []);
        // Ambil stok produk (current_stock) untuk setiap item di cart
        if (!empty($cart)) {
            $productIds = collect($cart)->pluck('product_id')->all();
            $stocks = Product::whereIn('id', $productIds)->pluck('current_stock', 'id');
            foreach ($cart as $key => $item) {
                $cart[$key]['stock'] = $stocks[$item['product_id']] ?? '-';
            }
        }
        return view('public.cart', compact('cart'));
    }

    // Tambah item ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = session('public_cart', []);
        $product = Product::with('prices')->findOrFail($request->product_id);
        // Ambil harga default (atau dari request jika ada price_type)
        $priceType = $request->input('price_type') ?? ($product->prices->first()->type ?? null);
        $selectedPrice = $product->prices->where('type', $priceType)->first();
        $price = $selectedPrice ? $selectedPrice->price : 0;
        $unitEquivalent = $selectedPrice ? $selectedPrice->unit_equivalent : 1;
        // Gunakan key gabungan product_id dan price_type agar bisa multi tipe harga
        $cartKey = $product->id . '_' . $priceType;
        if (isset($cart[$cartKey])) {
            // Jika sudah ada, tambahkan quantity
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'image' => $product->image,
                'price' => $price,
                'price_type' => $priceType,
                'unit_equivalent' => $unitEquivalent,
                'quantity' => $request->quantity,
            ];
        }
        session(['public_cart' => $cart]);
        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    // Hapus item dari keranjang
    public function remove($product_id)
    {
        $cart = session('public_cart', []);
        unset($cart[$product_id]);
        session(['public_cart' => $cart]);
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    // Update jumlah item di keranjang
    public function update(Request $request, $product_id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = session('public_cart', []);
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] = $request->quantity;
            session(['public_cart' => $cart]);
            return back()->with('success', 'Jumlah produk berhasil diupdate.');
        }
        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    // Kosongkan keranjang
    public function clear()
    {
        session()->forget('public_cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
