# Troubleshooting: Kode Reseller Tidak Muncul

## Langkah-Langkah Debugging

### 1. **Cek Status Customer sebagai Reseller**
Pastikan customer sudah diset sebagai reseller terlebih dahulu:

1. Buka halaman detail customer
2. Lihat di bagian "Debug Info" (sementara)
3. Pastikan tertulis:
   - `Customer exists: Yes`
   - `Is Reseller: Yes`
   - `Reseller Discount: [angka]%`

### 2. **Jika Customer belum jadi Reseller**
1. Gunakan form "Tetapkan sebagai Reseller" 
2. Masukkan persentase diskon (misal: 15)
3. Klik "Set Reseller"
4. Refresh halaman

### 3. **Jika sudah Reseller tapi Section tidak muncul**
Kemungkinan masalah:
- Customer record belum tersimpan dengan benar
- Cache view belum clear

**Solusi:**
```bash
php artisan view:clear
php artisan cache:clear
```

### 4. **Test Generate Kode Secara Manual**
Buka terminal dan jalankan:
```bash
php artisan tinker
```

Kemudian test:
```php
// Cek customer
$customer = App\Models\Customer::where('phone', '08123456789')->first();
$customer->is_reseller; // harus true

// Test generate kode
App\Models\ResellerCode::createForCustomer('08123456789', 24, 'Test code');
```

### 5. **Cek Error di Log**
Jika masih bermasalah, cek error log:
```bash
tail -f storage/logs/laravel.log
```

### 6. **Form Generate Kode**
Pastikan form terisi dengan benar:
- **Masa Berlaku**: 1-168 jam (default: 24)
- **Catatan**: Opsional
- Klik tombol "Generate"

### 7. **Cek Flash Messages**
Setelah klik generate, lihat notifikasi:
- **Hijau**: Sukses - "Kode reseller berhasil dibuat: [KODE]"
- **Merah**: Error - "Customer belum terdaftar sebagai reseller"

## Debugging View

Saya sudah menambahkan debug info sementara. Anda akan melihat:

### Jika Customer bukan Reseller:
```
Debug Info:
Customer exists: Yes/No
Is Reseller: Yes/No  
Reseller Discount: [angka]%
```

### Jika Customer adalah Reseller:
Section "Kelola Kode Reseller" akan muncul dengan:
- Form generate kode baru
- Daftar kode aktif (jika ada)
- Riwayat kode

## Flow yang Benar

1. **Set Customer sebagai Reseller**
   - Quick Action: "Tetapkan sebagai Reseller" 
   - Input diskon % → Klik "Set Reseller"

2. **Generate Kode**
   - Section "Kelola Kode Reseller" muncul
   - Form "Generate Kode Baru"
   - Set masa berlaku → Klik "Generate"

3. **Kode Muncul**
   - Notifikasi sukses
   - Kode aktif ditampilkan
   - Customer bisa gunakan kode di frontend

## Jika Masih Bermasalah

Kirim screenshot dari:
1. Bagian "Debug Info" 
2. Console browser (F12) untuk melihat error JavaScript
3. Flash message yang muncul setelah klik generate

Atau jalankan command ini dan kirim hasilnya:
```bash
php artisan route:list --name=generate-code
```
