# 🚨 TROUBLESHOOTING: Data Checkout Tidak Masuk Sistem

## ✅ Perbaikan Yang Sudah Dilakukan

### 1. **Fixed Product ID Issue untuk Bouquets**
```php
// BEFORE: 'bouquet_8' (string) → AFTER: 8 (integer)
if (isset($item['type']) && $item['type'] === 'bouquet') {
    if (preg_match('/bouquet_(\d+)/', $productId, $matches)) {
        $productId = $matches[1]; // Extract numeric ID
    }
}
```

### 2. **Enhanced Logging untuk Debug**
- ✅ Request logging di `addBouquet()`
- ✅ Cart contents logging di checkout
- ✅ Order creation logging
- ✅ Error logging untuk bouquet price not found

### 3. **Fixed JavaScript Error di Greeting Card Modal**
- ✅ Added fallback success notification
- ✅ Proper error handling untuk showSuccessNotification

### 4. **Enhanced Cart Display**
- ✅ Greeting card preview dalam cart items
- ✅ Visual pink box untuk greeting card messages

## 🔍 TESTING STEPS

### Step 1: Test Manual Tanpa Greeting Card
```javascript
// Test via browser console:
fetch('/cart/add-bouquet', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        bouquet_id: 1, // Sesuaikan dengan ID bouquet yang ada
        size_id: 1,    // Sesuaikan dengan size ID yang ada  
        quantity: 1,
        greeting_card: ""
    })
}).then(r => r.json()).then(console.log);
```

### Step 2: Cek Cart Session
Tambahkan route temporary di `routes/web.php`:
```php
Route::get('/debug-cart', function () {
    return response()->json([
        'cart' => session('cart', []),
        'session_id' => session()->getId()
    ]);
});
```

### Step 3: Test Checkout Process
1. Add bouquet to cart 
2. Go to `/debug-cart` - pastikan ada data
3. Proceed to checkout
4. Monitor Laravel logs: `tail -f storage/logs/laravel.log`

## 🎯 KEMUNGKINAN MASALAH & SOLUSI

### Issue A: CSRF Token Missing
**Symptom:** 419 error atau "Token Mismatch"
**Solution:** 
```html
<!-- Pastikan ada di head section -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### Issue B: Bouquet ID/Size ID Tidak Valid  
**Symptom:** "Bouquet tidak ditemukan" atau "Ukuran tidak tersedia"
**Check:**
```sql
SELECT b.id, b.name, bp.id as price_id, bp.size_id, bs.name as size_name, bp.price 
FROM bouquets b 
JOIN bouquet_prices bp ON b.id = bp.bouquet_id 
JOIN bouquet_sizes bs ON bp.size_id = bs.id;
```

### Issue C: Session Storage Problem
**Symptom:** Cart tampak kosong di checkout
**Solution:**
- Check session configuration
- Clear browser cache & cookies
- Test di browser lain

### Issue D: Database Constraint Errors
**Symptom:** Order tidak tersimpan tapi tidak ada error log
**Check:**
- Product ID harus exist di products table ATAU nullable
- Foreign key constraints pada public_order_items

## 🛠️ DEBUGGING COMMANDS

```bash
# 1. Clear all cache
php artisan cache:clear
php artisan config:clear  
php artisan view:clear

# 2. Check logs real-time
tail -f storage/logs/laravel.log

# 3. Check recent orders
php artisan tinker
>>> App\Models\PublicOrder::with('items')->latest()->first()

# 4. Check bouquet data
>>> App\Models\Bouquet::with('prices.size')->first()
```

## 🔧 QUICK FIXES

### Fix 1: Jika Bouquet Price Tidak Ditemukan
```php
// Di BouquetController, pastikan ada data:
$bouquet = Bouquet::with(['prices.size'])->find(1);
dd($bouquet->prices); // Check ada data atau tidak
```

### Fix 2: Jika Cart Kosong di Checkout
```php
// Di checkout blade, tambahkan debug:
@if(empty($cartData))
    <div class="bg-red-100 p-4">
        DEBUG: Cart kosong<br>
        Session ID: {{ session()->getId() }}<br>
        Raw Cart: {{ json_encode(session('cart', [])) }}
    </div>
@endif
```

### Fix 3: Jika Order Tidak Tersimpan
```php
// Temporary di PublicCheckoutController process():
try {
    DB::beginTransaction();
    
    // ... existing code ...
    
    dd('Order should be created', $order->id, $order->items->count());
    
} catch (\Exception $e) {
    dd('Error creating order:', $e->getMessage(), $e->getTraceAsString());
}
```

## 🎯 NEXT STEPS

1. **Test dengan data minimal** - 1 bouquet, 1 size, tanpa greeting card
2. **Monitor logs** - Lihat apakah ada error yang tidak tertangkap  
3. **Check database** - Pastikan foreign key constraints tidak blocking
4. **Browser network tab** - Lihat response dari AJAX calls

Jika masih tidak work, mari fokus pada **testing step by step** mulai dari add to cart → check session → checkout process.
