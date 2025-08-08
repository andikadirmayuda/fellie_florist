# Sistem Hak Akses (Role & Permission) - Fellie Florist

## Overview
Sistem ini mengimplementasikan 5 level user dengan hak akses yang berbeda sesuai dengan tanggung jawab masing-masing dalam operasional toko bunga.

## 5 Jenis User & Hak Akses

### 1. **OWNER** 👑
**Akses**: Kendali penuh pada semua fitur sistem
**Role**: `owner`
**Permissions**: Semua permission yang tersedia
**Default Login**: 
- Email: `owner@fellieflorist.com`
- Password: `Websitefellie2025@#`

---

### 2. **ADMIN** 🛡️
**Akses**: Administrator dengan akses hampir penuh
**Role**: `admin`
**Default Login**: 
- Email: `admin@fellieflorist.com`
- Password: `Websitefellie2025@#`

**Permissions**:
- **User Management**: View, Create, Edit users (tidak termasuk delete)
- **Product & Category**: Akses penuh (CRUD)
- **Inventory**: Akses penuh (view, manage, adjust stock)
- **Order Management**: Akses penuh (CRUD, update status, process payment)
- **Customer Management**: Akses penuh (CRUD, manage reseller)
- **Sales Management**: Akses penuh (CRUD)
- **Bouquet Management**: Akses penuh (CRUD)
- **Reports**: Akses semua laporan (sales, inventory, customer, income)
- **Settings**: Manage settings, WhatsApp, notifications

---

### 3. **KASIR** 💰
**Akses**: Fokus pada transaksi penjualan dan kasir
**Role**: `kasir`
**Default Login**: 
- Email: `kasir@fellieflorist.com`
- Password: `Websitefellie2025@#`

**Permissions**:
- **Product**: View products & categories (untuk transaksi)
- **Order Management**: View, Create, Edit orders + Update status + Process payment
- **Customer**: View customers (minimal untuk transaksi)
- **Sales**: Akses penuh (view, create, edit sales)
- **Reports**: Laporan penjualan saja
- **Communication**: WhatsApp & notifications
- **Dashboard**: View dashboard

**Tugas Utama**:
- Melayani transaksi penjualan
- Membuat pesanan baru
- Memproses pembayaran
- Melihat laporan penjualan

---

### 4. **KARYAWAN** 👷
**Akses**: Fokus pada operasional produk dan inventaris
**Role**: `karyawan`
**Default Login**: 
- Email: `karyawan@fellieflorist.com`
- Password: `Websitefellie2025@#`

**Permissions**:
- **Product**: View & Edit products (update stok/status)
- **Inventory**: Akses penuh (view, manage, adjust stock)
- **Order**: View orders + Update status (untuk persiapan pesanan)
- **Bouquet**: View & Edit bouquets (persiapan)
- **Reports**: Laporan inventaris saja
- **Dashboard**: View dashboard & notifications

**Tugas Utama**:
- Mengelola stok produk
- Menyiapkan pesanan
- Mengatur inventaris
- Update status produk

---

### 5. **CUSTOMER SERVICE** 📞
**Akses**: Fokus pada pelayanan dan komunikasi pelanggan
**Role**: `customers service`
**Default Login**: 
- Email: `cs@fellieflorist.com`
- Password: `Websitefellie2025@#`

**Permissions**:
- **Customer Management**: Akses penuh (CRUD, manage reseller)
- **Order**: View, Create, Edit + Update status (bantuan pelanggan)
- **Product**: View products & categories (info untuk pelanggan)
- **Reports**: Laporan pelanggan
- **Communication**: WhatsApp & notifications
- **Dashboard**: View dashboard

**Tugas Utama**:
- Melayani pertanyaan pelanggan
- Mengelola data pelanggan & reseller
- Membantu proses pemesanan
- Follow-up komunikasi pelanggan

---

## Struktur Permission yang Diimplementasikan

### **User Management**
- `view-users` - Melihat daftar user
- `create-user` - Membuat user baru
- `edit-user` - Edit data user
- `delete-user` - Hapus user (Owner only)

### **Product & Category Management**
- `view-products` - Melihat produk
- `create-product` - Tambah produk baru
- `edit-product` - Edit produk
- `delete-product` - Hapus produk
- `view-categories` - Melihat kategori
- `create-category` - Tambah kategori
- `edit-category` - Edit kategori
- `delete-category` - Hapus kategori

### **Inventory Management**
- `view-inventory` - Melihat inventaris
- `manage-inventory` - Kelola inventaris
- `adjust-stock` - Sesuaikan stok

### **Order Management**
- `view-orders` - Melihat pesanan
- `create-order` - Buat pesanan baru
- `edit-order` - Edit pesanan
- `delete-order` - Hapus pesanan
- `update-order-status` - Update status pesanan
- `process-payment` - Proses pembayaran

### **Customer Management**
- `view-customers` - Melihat pelanggan
- `create-customer` - Tambah pelanggan
- `edit-customer` - Edit data pelanggan
- `delete-customer` - Hapus pelanggan
- `manage-reseller` - Kelola reseller

### **Sales Management**
- `view-sales` - Melihat penjualan
- `create-sale` - Buat transaksi penjualan
- `edit-sale` - Edit penjualan
- `delete-sale` - Hapus penjualan

### **Bouquet Management**
- `view-bouquets` - Melihat bouquet
- `create-bouquet` - Buat bouquet baru
- `edit-bouquet` - Edit bouquet
- `delete-bouquet` - Hapus bouquet

### **Report Management**
- `view-reports` - Akses dasar laporan
- `view-sales-report` - Laporan penjualan
- `view-inventory-report` - Laporan inventaris
- `view-customer-report` - Laporan pelanggan
- `view-income-report` - Laporan pendapatan
- `generate-report` - Generate laporan
- `export-report` - Export laporan

### **Settings & Communication**
- `manage-settings` - Kelola pengaturan
- `view-dashboard` - Akses dashboard
- `send-whatsapp` - Kirim WhatsApp
- `view-notifications` - Melihat notifikasi

---

## Cara Penggunaan

### Menjalankan Seeder
```bash
# Install role dan permission
php artisan db:seed --class=RoleAndPermissionSeeder

# Install default users
php artisan db:seed --class=UserSeeder
```

### Cek Permission di Controller/Blade
```php
// Di Controller
if (auth()->user()->hasPermission('create-product')) {
    // User bisa create product
}

// Di Blade Template
@if(auth()->user()->hasPermission('view-reports'))
    <a href="{{ route('reports.index') }}">Laporan</a>
@endif

// Cek role
@if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('admin'))
    <!-- Content for owner/admin only -->
@endif
```

---

## Keamanan & Best Practices

1. **Principle of Least Privilege**: Setiap role hanya mendapat akses minimum yang dibutuhkan
2. **Separation of Duties**: Tugas-tugas sensitif dibagi antar role
3. **Audit Trail**: Semua aksi penting dicatat untuk audit
4. **Regular Review**: Hak akses perlu direview secara berkala

---

## Catatan Penting

- **Owner & Admin** memiliki akses hampir sama, namun Owner bisa menghapus user
- **Kasir** fokus pada transaksi dan tidak bisa mengubah master data
- **Karyawan** fokus pada operasional produk dan tidak bisa akses data keuangan
- **Customer Service** fokus pada pelanggan dan komunikasi
- Semua password default harus diganti setelah first login

---

*Sistem ini dapat dikustomisasi lebih lanjut sesuai kebutuhan operasional yang berkembang.*
