<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicFlowerController;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\PublicCheckoutController;
use App\Http\Controllers\PublicCartController;
use App\Http\Controllers\PublicBouquetController;

Route::get('/product-fellie', [PublicFlowerController::class, 'index'])->name('public.flowers');
Route::get('/bouquet-fellie', [PublicBouquetController::class, 'index'])->name('public.bouquets');
Route::get('/bouquet/{id}', [PublicBouquetController::class, 'detail'])->name('public.bouquet.detail');
Route::post('/public-order', [PublicOrderController::class, 'store'])->name('public.order.store');

// Edit public order (form & update)
Route::get('/public-order/{public_code}/edit', [PublicOrderController::class, 'edit'])->name('public.order.edit');
Route::post('/public-order/{public_code}/edit', [PublicOrderController::class, 'update'])->name('public.order.update');

// Checkout routes
Route::get('/checkout', [PublicCheckoutController::class, 'show'])->name('public.checkout');
Route::post('/checkout', [PublicCheckoutController::class, 'process'])->name('public.checkout.process');

// Cart routes (agar keranjang dan checkout konsisten di public)
Route::get('/cart', [PublicCartController::class, 'index'])->name('public.cart.index');
Route::post('/cart/add', [PublicCartController::class, 'add'])->name('public.cart.add');
Route::post('/cart/update/{cartKey}', [PublicCartController::class, 'updateQuantity'])->name('public.cart.update');
Route::post('/cart/remove/{cartKey}', [PublicCartController::class, 'remove'])->name('public.cart.remove');
Route::post('/cart/clear', [PublicCartController::class, 'clear'])->name('public.cart.clear');
Route::get('/cart/get', [PublicCartController::class, 'getCart']);

// Invoice publik (detail pesanan publik)
Route::get('/invoice/{public_code}', [PublicOrderController::class, 'publicInvoice'])
    ->middleware('throttle:10,1') // Maksimal 10x akses per menit per IP
    ->name('public.order.invoice');

// Halaman detail pemesanan publik (tracking)
Route::get('/order/{public_code}', [PublicOrderController::class, 'publicOrderDetail'])
    ->middleware('throttle:10,1') // Maksimal 10x akses per menit per IP
    ->name('public.order.detail');

// Tracking pesanan publik berdasarkan nomor WhatsApp
Route::get('/track-order', [App\Http\Controllers\PublicOrderController::class, 'trackOrderForm'])->name('public.order.track');