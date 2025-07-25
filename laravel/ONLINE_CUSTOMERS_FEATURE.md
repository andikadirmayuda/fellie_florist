# Menu Daftar Pelanggan Online

## Fitur yang Telah Dibuat

### 1. **Controller: OnlineCustomerController**
- **Path**: `app/Http/Controllers/OnlineCustomerController.php`
- **Fungsi**: Mengelola pelanggan online dari data pesanan online (PublicOrder)
- **Method Utama**:
  - `index()`: Menampilkan daftar pelanggan online
  - `show()`: Detail pelanggan dan riwayat pesanan
  - `edit()`: Form edit pengaturan pelanggan
  - `update()`: Simpan perubahan data pelanggan
  - `setAsReseller()`: Set pelanggan sebagai reseller
  - `setPromoDiscount()`: Set diskon promo untuk pelanggan

### 2. **Database Migration**
- **File**: `database/migrations/2025_07_25_220210_add_reseller_and_promo_fields_to_customers_table.php`
- **Kolom Baru di Tabel `customers`**:
  - `is_reseller` (boolean): Status reseller
  - `reseller_discount` (decimal): Persentase diskon reseller
  - `promo_discount` (decimal): Persentase diskon promo
  - `notes` (text): Catatan khusus pelanggan

### 3. **Model Customer - Update**
- **Path**: `app/Models/Customer.php`
- **Penambahan**: Kolom baru di `$fillable` dan `$casts`

### 4. **Routes**
- **Path**: `routes/web.php`
- **Routes Baru**:
  ```php
  Route::resource('online-customers', OnlineCustomerController::class);
  Route::post('online-customers/{wa_number}/set-reseller', 'setAsReseller');
  Route::post('online-customers/{wa_number}/set-promo', 'setPromoDiscount');
  ```

### 5. **Views**
- **Path**: `resources/views/online-customers/`
- **Files**:
  - `index.blade.php`: Daftar pelanggan online dengan pencarian
  - `show.blade.php`: Detail pelanggan dan riwayat pesanan
  - `edit.blade.php`: Form pengaturan reseller dan promo

### 6. **Menu Sidebar**
- **Path**: `resources/views/layouts/sidebar.blade.php`
- **Penambahan**: Menu "Pelanggan Online" di bagian Manajemen Pengguna

## Cara Kerja

### 1. **Daftar Pelanggan Online** (`/online-customers`)
- Mengambil data dari tabel `public_orders`
- Mengelompokkan berdasarkan `customer_name` dan `wa_number`
- Menampilkan statistik: total pesanan, total belanja, dll
- Fitur pencarian berdasarkan nama atau nomor WhatsApp

### 2. **Detail Pelanggan** (`/online-customers/{wa_number}`)
- Menampilkan informasi lengkap pelanggan
- Riwayat semua pesanan
- Status reseller dan promo (jika ada)
- Form quick action untuk set reseller dan promo

### 3. **Edit Pelanggan** (`/online-customers/{wa_number}/edit`)
- Form lengkap untuk mengatur:
  - Status reseller dan persentase diskon
  - Diskon promo
  - Catatan khusus

## Fungsi Utama

### **Memberikan Harga Reseller**
- Admin bisa menandai pelanggan sebagai reseller
- Set persentase diskon khusus reseller
- Data tersimpan di tabel `customers`

### **Memberikan Harga Promo**
- Admin bisa memberikan diskon promo khusus
- Berlaku terpisah dari diskon reseller
- Fleksibel untuk campaign marketing

### **Manajemen Data Pelanggan**
- Sinkronisasi otomatis dengan data pesanan online
- Pencarian dan filter data
- Riwayat lengkap aktivitas pelanggan

## Akses Role
- **Owner**: Full access
- **Admin**: Full access  
- **Customer Service**: Full access
- **Role lain**: Tidak ada akses

## URL Akses
- Daftar: `http://127.0.0.1:8000/online-customers`
- Detail: `http://127.0.0.1:8000/online-customers/{wa_number}`
- Edit: `http://127.0.0.1:8000/online-customers/{wa_number}/edit`
