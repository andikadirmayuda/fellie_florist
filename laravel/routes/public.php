<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicFlowerController;
use App\Http\Controllers\PublicOrderController;

Route::get('/bunga-ready', [PublicFlowerController::class, 'index'])->name('public.flowers');
Route::post('/public-order', [PublicOrderController::class, 'store'])->name('public.order.store');
