# Update Aksi Cepat Dashboard - Role-Based Quick Actions

## Yang Telah Diubah

Mengubah bagian "Aksi Cepat" di dashboard untuk menampilkan menu yang sesuai dengan role masing-masing user.

## Aksi Cepat Per Role

### 1. **OWNER & ADMIN** 👑🛡️
- ✅ Tambah Produk
- ✅ Kelola Inventaris  
- ✅ Pesanan Online
- ✅ Laporan

### 2. **KASIR** 💰
- ✅ **Lihat Produk** (bukan tambah, sesuai permission)
- ✅ **Lihat Inventaris** (view only untuk kasir)
- ✅ **Pesanan Online** (untuk proses transaksi)
- ✅ **Penjualan** (akses ke sales.index)
- ✅ **Laporan** (laporan penjualan)

### 3. **KARYAWAN** 👷
- ✅ Lihat Produk
- ✅ Kelola Inventaris (full access untuk karyawan)
- ✅ Pesanan Online (untuk persiapan)
- ✅ Laporan Stok (sesuai tugas karyawan)

### 4. **CUSTOMER SERVICE** 📞
- ✅ Kelola Pelanggan
- ✅ Pesanan Online (untuk bantuan customer)
- ✅ Laporan Pelanggan

### 5. **Default** (jika role tidak dikenali)
- ✅ Lihat Produk
- ✅ Pesanan Online

## Warna & Icon yang Digunakan

- **Biru** (`bg-blue-600`): Lihat Produk
- **Teal** (`bg-teal-600`): Inventaris  
- **Purple** (`bg-purple-600`): Pesanan Online
- **Orange** (`bg-orange-600`): Penjualan
- **Green** (`bg-green-600`): Laporan
- **Gray** (`bg-gray-800`): Tambah Produk (Owner/Admin)

## Benefit

1. **User Experience**: Setiap user melihat menu yang relevan dengan tugasnya
2. **Efficiency**: Akses cepat ke fitur yang paling sering digunakan
3. **Security**: User tidak melihat tombol untuk fitur yang tidak boleh diakses
4. **Role-Based**: Sesuai dengan hak akses yang telah ditetapkan

## Catatan

- Kasir sekarang mendapat akses **"Lihat Inventaris"** bukan "Kelola" untuk mendukung tugasnya dalam transaksi
- Ditambahkan tombol **"Penjualan"** khusus untuk kasir agar bisa langsung akses ke sales
- Customer Service fokus pada pelanggan dan pesanan saja
- Karyawan tetap mendapat akses penuh ke inventaris sesuai tugasnya

---

*Update ini meningkatkan user experience dengan memberikan aksi cepat yang sesuai dengan role dan tanggung jawab masing-masing user.*
