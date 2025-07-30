## 💌 Bouquet Greeting Card Enhancement - Unified Cart System

### 📋 Problem Analysis
User mengatakan: "jika ia pesan bouqet, maka muncul satu lagi form input yakni kartu ucapkan"

**Current State:**
- Unified cart system sudah berhasil diimplementasikan ✅
- Bouquet dan bunga satuan bisa berada dalam satu keranjang ✅  
- Tapi belum ada field greeting card untuk bouquet ❌

### 🎯 Solution Architecture

**Konsep Implementasi:**
1. **Dalam Cart Item**: Tambahkan field `greeting_card` untuk bouquet items
2. **Storage Strategy**: Simpan greeting card sebagai attribute bouquet di session cart
3. **UI Enhancement**: Modal input greeting card saat add bouquet to cart
4. **Database Integration**: Simpan ke public_order_items table dengan field tambahan

### 🛠️ Implementation Plan

#### Phase 1: Cart System Enhancement
- [ ] Modifikasi `PublicCartController::addBouquet()` untuk accept greeting card
- [ ] Update cart structure untuk include greeting card data
- [ ] Enhance cart display untuk show greeting card preview

#### Phase 2: UI Components
- [ ] Buat greeting card input modal
- [ ] Integrate dengan bouquet selection flow
- [ ] Visual indicator di cart untuk bouquet with greeting card

#### Phase 3: Checkout & Order Processing
- [ ] Modify checkout view untuk display greeting cards
- [ ] Update `PublicCheckoutController` untuk handle greeting card data
- [ ] Enhance order detail view untuk show greeting messages

### 📝 Data Structure Design

```php
// Enhanced cart structure untuk bouquet with greeting card
'cart' => [
    'bouquet_8_2' => [
        'id' => 'bouquet_8',
        'name' => 'Romantic Rose Bouquet',
        'price' => 150000,
        'qty' => 1,
        'price_type' => 'Medium',
        'size_id' => 2,
        'type' => 'bouquet',
        'image' => 'bouquets/romantic.jpg',
        'greeting_card' => 'Happy Anniversary! Wishing you both all the happiness in the world. ❤️'
    ]
]
```

### 🚀 Step-by-Step Implementation

**Step 1: Enhance Cart Controller**
- Modifikasi `addBouquet()` method untuk accept optional greeting_card parameter
- Update cart structure untuk store greeting card message

**Step 2: Create Greeting Card Modal** 
- Buat reusable modal component untuk input greeting card
- Integration dengan existing bouquet selection flow

**Step 3: Update Cart Display**
- Show greeting card preview dalam cart items
- Visual badge untuk bouquet items yang ada greeting card

**Step 4: Checkout Integration**
- Display greeting card dalam checkout summary
- Store dalam database via order items

### 🎨 User Experience Flow

1. **User selects bouquet** → Pilih ukuran → **NEW: Optional greeting card input**
2. **Add to cart** dengan greeting card message
3. **Cart view** shows bouquet + greeting card preview  
4. **Checkout** displays full order dengan greeting card
5. **Order confirmation** includes greeting card dalam order details

### 🔧 Technical Considerations

**Pros:**
- Maintains unified cart architecture
- Clean separation bouquet vs regular flowers
- Optional feature - tidak wajib diisi
- Stored in session, persistent across pages

**Challenges:**
- Need database migration untuk order items
- Modal UX harus smooth dan intuitive
- Cart size management (text length limits)
- Mobile responsiveness for modal

### 📱 Implementation Priority

**HIGH PRIORITY:**
- Basic greeting card input dalam bouquet flow
- Cart display dengan greeting card preview
- Checkout integration

**MEDIUM PRIORITY:**  
- Rich text formatting untuk greeting card
- Character limit dan validation
- Greeting card templates/suggestions

**LOW PRIORITY:**
- Preview greeting card dengan fancy styling
- Multiple language support
- Greeting card history/favorites

---

**Next Action:** Implement greeting card modal dan enhance addBouquet method
