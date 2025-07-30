<?php

// Temporary debug route - add to routes/web.php untuk testing
Route::get('/debug-cart', function () {
    $cart = session('cart', []);
    return response()->json([
        'cart_contents' => $cart,
        'cart_count' => count($cart),
        'session_id' => session()->getId(),
        'has_csrf' => session()->token()
    ]);
});

Route::get('/debug-orders', function () {
    $orders = \App\Models\PublicOrder::with('items')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    return response()->json([
        'recent_orders' => $orders,
        'total_orders' => \App\Models\PublicOrder::count()
    ]);
});
