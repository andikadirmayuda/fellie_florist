<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicInvoiceController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\ArchiveSettingController;
use App\Http\Controllers\HistorySettingController;

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

    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{product}/history', [InventoryController::class, 'history'])->name('inventory.history');
    Route::get('/inventory/{product}/adjust', [InventoryController::class, 'adjustForm'])->name('inventory.adjust-form');
    Route::post('/inventory/{product}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

    // Order Management Routes
    Route::resource('orders', OrderController::class);
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{order}/share-whatsapp', [OrderController::class, 'shareWhatsApp'])->name('orders.share-whatsapp');

    // Order History Routes
    Route::get('order-histories', [OrderHistoryController::class, 'index'])->name('order-histories.index');
    Route::get('order-histories/{history}', [OrderHistoryController::class, 'show'])->name('order-histories.show');

    // Settings Routes
    Route::prefix('settings')->name('settings.')->middleware(['auth'])->group(function () {
        // Archive Settings
        Route::get('/archive', [ArchiveSettingController::class, 'index'])->name('archive');
        Route::post('/archive', [ArchiveSettingController::class, 'update'])->name('archive.update');
        
        // History Settings
        Route::get('/history', [HistorySettingController::class, 'index'])->name('history');
        Route::put('/history', [HistorySettingController::class, 'update'])->name('history.update');
    });
    Route::get('customers/{id}/history', [CustomerController::class, 'orderHistory'])->name('customers.history');
});

// Public Invoice Route (No Auth Required)
Route::get('/i/{token}', [PublicInvoiceController::class, 'show'])->name('public.invoice');

require __DIR__.'/auth.php';
