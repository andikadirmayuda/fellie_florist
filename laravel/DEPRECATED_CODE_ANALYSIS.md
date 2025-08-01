# ANALISIS KODE TIDAK BERFUNGSI / DEPRECATED - FELLIE FLORIST

## 🔴 FILE & FITUR YANG TIDAK DIGUNAKAN LAGI (DEPRECATED)

### 1. CUSTOMER MANAGEMENT (OFFLINE/UTAMA) - DEPRECATED ❌

#### Controller yang Deprecated:
- **`app/Http/Controllers/CustomerController.php`** ✅ SUDAH DITANDAI DEPRECATED
  - Semua method redirect ke `OnlineCustomerController`
  - Status: DEPRECATED - bisa dihapus

#### Routes yang Dikomentar:
```php
// Customer Management Routes (DEPRECATED - Gunakan online-customers)
// Route::resource('customers', CustomerController::class);

// Customer Trash Routes (DEPRECATED)
// Route::get('customers/trashed', [CustomerController::class, 'trashed'])->name('customers.trashed');
// Route::patch('customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
// Route::delete('customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.force-delete');
```

#### Model yang Masih Ada tapi Tidak Digunakan:
- **`app/Models/Customer.php`** - Hanya digunakan untuk relasi dengan `PublicOrder`

### 2. ORDER MANAGEMENT (OFFLINE/UTAMA) - MIXED STATUS ⚠️

#### Controller yang Tidak Ada:
- **`app/Http/Controllers/OrderController.php`** ❌ FILE TIDAK ADA
  - Referenced di routes tapi file tidak exist

#### Model yang Ada tapi Tidak Digunakan Optimal:
- **`app/Models/Order.php`** ⚠️ MASIH DIGUNAKAN MINIMAL
  - Hanya digunakan di `ReportController` untuk laporan pendapatan
  - Tidak ada CRUD interface untuk model ini
  - Status: SEMI-DEPRECATED

#### Related Models yang Tidak Digunakan:
- **`app/Models/OrderItem.php`** ❌ TIDAK DIGUNAKAN
- **`app/Models/OrderHistory.php`** ❌ TIDAK DIGUNAKAN

### 3. ROUTES YANG DIKOMENTAR/TIDAK AKTIF ❌

```php
// Order WhatsApp Routes - MISSING CONTROLLER
// Route::post('/order-whatsapp', [\App\Http\Controllers\OrderWhatsAppController::class, 'store']);
// Route::get('/order-whatsapp', [\App\Http\Controllers\OrderWhatsAppController::class, 'form']);

// Unused Payment Route
// Route::post('/admin/public-orders/{id}/add-payment', [AdminPublicOrderController::class, 'addPayment']);
```

### 4. VIEWS YANG TIDAK DIGUNAKAN ❌

- Views terkait `CustomerController` (jika ada)
- Views terkait `OrderController` (jika ada)
- Views terkait offline order management

### 5. COMMANDS YANG TIDAK AKTIF ⚠️

- **`app/Console/Commands/CleanupOrderHistories.php`** - Untuk OrderHistory yang tidak digunakan
- **`app/Console/Commands/ArchiveOldOrders.php`** - Untuk Order model yang semi-deprecated

---

## ✅ SISTEM YANG MASIH AKTIF & BERFUNGSI

### 1. ONLINE CUSTOMER MANAGEMENT ✅
- `OnlineCustomerController` - AKTIF
- Model: `Customer` (untuk relasi), `PublicOrder`
- Views: `resources/views/online-customers/`

### 2. PUBLIC ORDER SYSTEM ✅
- `PublicOrderController` - AKTIF
- `AdminPublicOrderController` - AKTIF  
- Models: `PublicOrder`, `PublicOrderItem`, `PublicOrderPayment`
- Views: Admin public orders management

### 3. PRODUCT & INVENTORY MANAGEMENT ✅
- `ProductController` - AKTIF
- `CategoryController` - AKTIF
- `InventoryController` - AKTIF
- Semua related models & views

### 4. SALES MANAGEMENT ✅
- `SaleController` - AKTIF
- Related models & views

### 5. BOUQUET MANAGEMENT ✅
- `BouquetController` - AKTIF
- `BouquetCategoryController` - AKTIF
- `BouquetSizeController` - AKTIF
- `BouquetComponentController` - AKTIF

### 6. REPORTING SYSTEM ✅
- `ReportController` - AKTIF
- Semua laporan berfungsi normal

---

## 🔧 REKOMENDASI PEMBERSIHAN

### LANGKAH 1: HAPUS FILE DEPRECATED ❌
```bash
# Backup dulu sebelum hapus
rm app/Http/Controllers/CustomerController.php
rm app/Models/OrderHistory.php
rm app/Models/OrderItem.php
rm app/Console/Commands/CleanupOrderHistories.php
rm app/Console/Commands/ArchiveOldOrders.php
```

### LANGKAH 2: BERSIHKAN ROUTES 🧹
```php
// Hapus semua route yang dikomentar dari web.php
// Hapus import controller yang tidak digunakan
```

### LANGKAH 3: EVALUASI MODEL ORDER ⚠️
- Pertimbangkan apakah `Order` model masih diperlukan
- Jika tidak, migrate data ke `PublicOrder` dan hapus

### LANGKAH 4: BERSIHKAN DATABASE MIGRATIONS 🗃️
- Review migrasi terkait tabel yang tidak digunakan:
  - `orders` table (jika tidak diperlukan)
  - `order_items` table
  - `order_histories` table

### LANGKAH 5: UPDATE DOKUMENTASI 📚
- Update README.md
- Hapus referensi ke fitur yang sudah deprecated

---

## 🎯 KESIMPULAN

**SISTEM UTAMA YANG BERFUNGSI:**
- ✅ Online Customer Management (berdasarkan WhatsApp)
- ✅ Public Order System (pesanan online)
- ✅ Product & Inventory Management
- ✅ Sales Management (offline/toko)
- ✅ Bouquet Management
- ✅ Reporting System

**SISTEM YANG DEPRECATED/TIDAK BERFUNGSI:**
- ❌ Offline Customer Management (CustomerController)
- ❌ Offline Order Management (OrderController - file tidak ada)
- ❌ OrderHistory system
- ❌ Beberapa routes dan commands

**PRIORITAS PEMBERSIHAN: TINGGI** 🔥
Sistem memiliki banyak kode mati yang bisa membingungkan developer dan memperbesar ukuran aplikasi.
