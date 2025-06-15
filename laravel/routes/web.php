<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management Routes - Only accessible by owner and admin
    Route::middleware('role:owner,admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Customer Management Routes
    Route::resource('customers', CustomerController::class);

    // Customer Trash Routes
    Route::get('customers/trashed', [CustomerController::class, 'trashed'])->name('customers.trashed');
    Route::patch('customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
    Route::delete('customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.force-delete');

    // Product Management Routes
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    // Inventory Management Routes
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/stock-in', [InventoryController::class, 'stockInForm'])->name('stock-in.form');
        Route::post('/stock-in', [InventoryController::class, 'stockIn'])->name('stock-in.store');
        Route::get('/adjustments', [InventoryController::class, 'adjustmentForm'])->name('adjustment.form');
        Route::post('/adjustments', [InventoryController::class, 'adjustStock'])->name('adjustment.store');
        Route::get('/holds', [InventoryController::class, 'stockHolds'])->name('holds');
        Route::post('/holds/{id}/release', [InventoryController::class, 'releaseHold'])->name('holds.release');
    });
});

require __DIR__.'/auth.php';
