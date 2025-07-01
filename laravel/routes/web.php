
<?php

// Public Order (API) - untuk form pemesanan publik
Route::post('/public-order', [App\Http\Controllers\PublicOrderController::class, 'store']);

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
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReceiptController;
use App\Http\Controllers\PublicSaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminPublicOrderController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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
    // Ekspor & Impor Produk Excel
    // Export/Import Produk via JSON
    Route::get('products/export-json', [App\Http\Controllers\ProductJsonController::class, 'export'])->name('products.export-json');
    Route::post('products/import-json', [App\Http\Controllers\ProductJsonController::class, 'import'])->name('products.import-json');
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

    // Sales Routes
    Route::resource('sales', App\Http\Controllers\SaleController::class)->names([
        'index' => 'sales.index',
        'create' => 'sales.create',
        'store' => 'sales.store',
        'show' => 'sales.show',
    ]);
    Route::get('/sales/{sale}/download-pdf', [App\Http\Controllers\SaleController::class, 'downloadPdf'])->name('sales.download_pdf');

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
Route::get('/public/receipt/{public_code}', [PublicSaleController::class, 'show'])->name('sales.public_receipt');

// Report Routes - Tanpa middleware, dapat diakses publik
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
    Route::get('/sales/pdf', [ReportController::class, 'salesPdf'])->name('sales.pdf');
    Route::get('/orders', [ReportController::class, 'orders'])->name('orders'); // laporan pemesanan
    Route::get('/customers', [ReportController::class, 'customers'])->name('customers'); // laporan pelanggan
    Route::get('/income', [ReportController::class, 'income'])->name('income'); // laporan pendapatan
    // Route berikut bisa diaktifkan jika fitur Excel sudah tersedia
    // Route::get('/sales/excel', [ReportController::class, 'salesExcel'])->name('sales.excel');
});

// Order WhatsApp Form (tanpa login)
Route::post('/order-whatsapp', [\App\Http\Controllers\OrderWhatsAppController::class, 'store'])->name('order.whatsapp.store');
// Form order WhatsApp publik (GET) dengan produk ready stock
Route::get('/order-whatsapp', [\App\Http\Controllers\OrderWhatsAppController::class, 'form'])->name('order.whatsapp.form');


// Admin Public Order Routes - Bisa diakses semua user yang login
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/public-orders', [AdminPublicOrderController::class, 'index'])->name('admin.public-orders.index');
    Route::get('/admin/public-orders/{id}', [AdminPublicOrderController::class, 'show'])->name('admin.public-orders.show');
});

// Route untuk invoice publik khusus pemesanan publik (PUBLIC, tanpa auth)
Route::get('/invoice/{public_code}', [App\Http\Controllers\PublicOrderController::class, 'publicInvoice'])->name('public.order.invoice');

require __DIR__.'/auth.php';
