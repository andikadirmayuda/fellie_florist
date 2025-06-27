<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicFlowerController;

Route::get('/bunga-ready', [PublicFlowerController::class, 'index'])->name('public.flowers');
