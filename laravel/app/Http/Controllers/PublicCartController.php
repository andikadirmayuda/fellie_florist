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
        foreach ($cart as $cartKey => $item) {
            $total += $item['price'] * $item['qty'];
            
            // Format image URL dengan benar
            $imageUrl = null;
            if (isset($item['image']) && $item['image']) {
                $imageUrl = asset('storage/' . $item['image']);
            }
            
            // Format nama produk dengan price_type
            $productName = $item['name'];
            if (isset($item['price_type']) && $item['price_type']) {
                $priceTypeLabel = $this->getPriceTypeLabel($item['price_type']);
                $productName .= ' (' . $priceTypeLabel . ')';
            }
            
            $items[] = [
                'id' => $cartKey, // Gunakan cartKey sebagai ID unik
                'product_id' => $item['id'], // ID produk asli
                'name' => $productName,
                'price' => $item['price'],
                'quantity' => $item['qty'],
                'price_type' => $item['price_type'] ?? null,
                'image' => $imageUrl
            ];
        }
        return response()->json([
            'items' => $items,
            'total' => $total,
            'success' => true
        ]);
    }

    private function getPriceTypeLabel($priceType)
    {
        $labels = [
            'tangkai' => 'Per Tangkai',
            'ikat5' => 'Ikat 5',
            'reseller' => 'Reseller', 
            'promo' => 'Promo'
        ];
        
        return $labels[$priceType] ?? ucfirst($priceType);
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
            'image' => $productModel->image ?? null,
        ];

        $cart = session()->get('cart', []);

        // Gunakan kombinasi product_id dan price_type sebagai key unik
        $cartKey = $product['id'] . '_' . ($selectedPriceType ?? 'default');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $product['qty'];
        } else {
            $cart[$cartKey] = $product;
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'cart' => $cart
        ]);
    }

    public function updateQuantity(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);
        $quantityChange = $request->input('quantity_change', 0);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $quantityChange;
            
            // Hapus item jika quantity <= 0
            if ($cart[$cartKey]['qty'] <= 0) {
                unset($cart[$cartKey]);
            }
            
            session(['cart' => $cart]);
            
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui.',
                'cart' => $cart
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang.'
        ], 404);
    }

    public function remove(Request $request, $cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session(['cart' => $cart]);
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang.',
                'cart' => $cart
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang.'
        ], 404);
    }

    public function clear(Request $request)
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan.'
        ]);
    }
}
