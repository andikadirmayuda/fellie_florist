<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class PublicCartController extends Controller
{
    // Tambahkan method sesuai kebutuhan, contoh:
    public function addToCart(Request $request)
    {
        // Logika untuk menambah produk ke keranjang
        // return response atau redirect sesuai kebutuhan
        return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang.']);
    }
    
    public function getCart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $items[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['qty'],
                'image' => isset($item['image']) ? $item['image'] : null
            ];
        }
        return response()->json([
            'items' => $items,
            'total' => $total,
            'success' => true
        ]);
    }
    public function add(Request $request)
    {


        $productId = $request->input('product_id');
        $qty = $request->input('quantity', 1);
        $priceType = $request->input('price_type');

        $productModel = Product::find($productId);
        if (!$productModel || !($productModel instanceof Product)) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }

        // Ambil harga berdasarkan price_type jika ada, jika tidak fallback ke default
        $priceQuery = $productModel->prices();
        if ($priceType) {
            $priceModel = $priceQuery->where('type', $priceType)->first();
        } else {
            $priceModel = $priceQuery->where('is_default', true)->first();
        }
        $price = ($priceModel && isset($priceModel->price)) ? $priceModel->price : 0;
        $selectedPriceType = $priceModel ? $priceModel->type : ($priceType ?? null);

        $product = [
            'id' => $productModel->id ?? null,
            'name' => $productModel->name ?? '',
            'price' => $price,
            'qty' => $qty,
            'price_type' => $selectedPriceType,
        ];

        $cart = session()->get('cart', []);

        if (isset($cart[$product['id']])) {
            $cart[$product['id']]['qty'] += $product['qty'];
        } else {
            $cart[$product['id']] = $product;
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'cart' => $cart
        ]);
    }
}
