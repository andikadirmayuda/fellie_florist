<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $flower = Product::with(['prices' => function($query) {
            $query->where('type', 'per_tangkai');
        }])->findOrFail($id);

        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $flower->id,
                'name' => $flower->name,
                'price' => $flower->prices->first()->price,
                'quantity' => 1,
                'image' => $flower->image ? asset('storage/' . $flower->image) : asset('images/default-flower.jpg'),
            ];
        }
        
        Session::put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Item added to cart'
        ]);
    }

    public function getItems()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $items = [];

        foreach ($cart as $id => $item) {
            $items[] = [
                'id' => $id,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'],
                'subtotal' => $item['price'] * $item['quantity']
            ];
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'items' => $items,
            'total' => $total
        ]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity_change;
            
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
            
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    }

    public function remove($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    }
}
