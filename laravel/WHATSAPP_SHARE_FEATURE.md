# Fitur Share Pesanan ke WhatsApp Grup Karyawan

## Deskripsi
Fitur ini memungkinkan admin untuk share informasi pesanan public ke grup WhatsApp karyawan secara mudah dan cepat. Setiap kali ada pesanan baru masuk, tim karyawan bisa langsung mendapat notifikasi lengkap tentang detail pesanan.

## Fitur yang Tersedia

### 1. Tombol Share Manual
- Tersedia di halaman detail pesanan (`/admin/public-orders/{id}`)
- Tombol biru dengan label "Share ke Grup Karyawan"
- Generate pesan WhatsApp otomatis dengan detail lengkap pesanan
- Membuka WhatsApp Web/App dengan pesan yang sudah terformat

### 2. Format Pesan WhatsApp
Pesan yang digenerate berisi informasi lengkap:
- 🌸 Header "PESANAN BARU MASUK"
- 📋 Detail pesanan (kode, nama customer, kontak, tanggal, dll)
- 🛒 Daftar item yang dipesan dengan harga
- 💰 Total harga pesanan
- 📝 Catatan pesanan (jika ada)
- 🔗 Link invoice publik (jika ada)
- ⚠️ Call to action untuk segera memproses

### 3. Konfigurasi WhatsApp
File konfigurasi: `config/whatsapp.php`
- Nomor grup karyawan (default: 6281234567890)
- Template pesan untuk berbagai jenis notifikasi
- Pengaturan format nomor telepon

## Cara Penggunaan

### Setting Nomor Grup Karyawan
1. Edit file `.env`
2. Tambahkan/edit baris: `WA_GROUP_EMPLOYEES=628xxxxxxxxx`
3. Gunakan format 62 + nomor tanpa 0 di depan
4. Restart server atau jalankan `php artisan config:cache`

### Menggunakan Fitur Share
1. Buka halaman detail pesanan public
2. Scroll ke bagian "Aksi Pesanan"
3. Klik tombol "Share ke Grup Karyawan"
4. Sistem akan generate pesan dan membuka WhatsApp
5. Pesan sudah otomatis terformat, tinggal kirim

## Struktur Files

### Controller
- `app/Http/Controllers/AdminPublicOrderController.php`
  - Method `generateWhatsAppMessage()` untuk generate pesan

### Service
- `app/Services/WhatsAppNotificationService.php`
  - Service class untuk handle semua logic WhatsApp
  - Method untuk generate berbagai jenis pesan
  - Method untuk format URL WhatsApp

### Views
- `resources/views/admin/public_orders/show.blade.php`
  - Tombol share dan JavaScript handler
  - Notification system untuk feedback user

### Configuration
- `config/whatsapp.php` - Konfigurasi WhatsApp
- `.env` - Environment variables

### Routes
- `GET /admin/public-orders/{id}/whatsapp-message` - Generate pesan WhatsApp

## Contoh Pesan WhatsApp

```
🌸 *PESANAN BARU MASUK* 🌸

📋 *Detail Pesanan:*
• Kode: FEL001
• Nama: John Doe
• WhatsApp: 081234567890
• Tanggal Pesan: 04/08/2025 14:30
• Tanggal Ambil: 05/08/2025 10:00
• Metode: Pickup
• Status: Pending
• Status Bayar: Unpaid

🛒 *Item Pesanan:*
• Buket Mawar Merah x2 = Rp 150.000
• Kartu Ucapan x1 = Rp 15.000

💰 *Total: Rp 165.000*

📝 *Catatan:*
Mohon dipacking dengan rapi untuk gift

🔗 *Link Invoice:*
https://example.com/invoice/FEL001

⚠️ *Mohon segera diproses!*
📱 Cek detail lengkap di admin panel.
```

## Troubleshooting

### Pesan Tidak Tergenerate
- Pastikan konfigurasi WhatsApp sudah benar
- Check log error di `storage/logs/laravel.log`
- Pastikan pesanan memiliki items

### WhatsApp Tidak Terbuka
- Pastikan nomor grup karyawan benar (format 62xxx)
- Pastikan WhatsApp Web/Desktop terinstall
- Coba gunakan browser yang berbeda

### Error Permission
- Pastikan user memiliki akses ke halaman admin
- Check middleware auth pada routes

## Kemungkinan Pengembangan Future

1. **Auto-notification**: Otomatis kirim notifikasi saat status berubah
2. **Multiple groups**: Support beberapa grup untuk divisi berbeda
3. **Message templates**: Template berbeda untuk setiap jenis notifikasi
4. **Scheduled notifications**: Reminder otomatis untuk pesanan tertentu
5. **Integration with WhatsApp Business API**: Kirim pesan otomatis tanpa manual

## Security Notes

- Nomor grup karyawan disimpan di config, bukan hardcode
- Pesan tidak berisi informasi sensitif seperti payment details
- URL yang digenerate menggunakan HTTPS
- Semua error di-log untuk monitoring

## Updates

- **v1.0** (04/08/2025): Initial release dengan fitur share manual
