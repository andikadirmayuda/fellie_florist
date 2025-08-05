# 🎨 Custom Bouquet Implementation Plan

## 📋 **Overview**
Implementasi fitur Custom Bouquet yang memungkinkan customer membuat bouquet sendiri dengan memilih komponen individual (bunga, wrapping, pita, dll) berdasarkan stok yang tersedia.

## 🏗️ **Database Architecture**

### **New Tables Needed:**

#### 1. `custom_bouquets` table
```sql
CREATE TABLE custom_bouquets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    customer_name VARCHAR(255) NULL,
    description TEXT NULL,
    total_price DECIMAL(12,2),
    reference_image VARCHAR(255) NULL, -- Upload contoh bouquet
    status ENUM('draft', 'in_cart', 'ordered') DEFAULT 'draft',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### 2. `custom_bouquet_items` table  
```sql
CREATE TABLE custom_bouquet_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    custom_bouquet_id BIGINT,
    product_id BIGINT,
    price_type ENUM('per_tangkai', 'ikat_5', 'ikat_10', 'ikat_20', 'normal') DEFAULT 'per_tangkai',
    quantity INT,
    unit_price DECIMAL(12,2),
    subtotal DECIMAL(12,2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (custom_bouquet_id) REFERENCES custom_bouquets(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

#### 3. Enhance `public_order_items` table
```sql
ALTER TABLE public_order_items ADD COLUMN custom_bouquet_id BIGINT NULL;
ALTER TABLE public_order_items ADD COLUMN reference_image VARCHAR(255) NULL;
```

## 🛠️ **Implementation Steps**

### **Phase 1: Database & Models (Priority: HIGH)**

1. **Create Migrations**
   ```bash
   php artisan make:migration create_custom_bouquets_table
   php artisan make:migration create_custom_bouquet_items_table
   php artisan make:migration add_custom_bouquet_fields_to_public_order_items
   ```

2. **Create Models**
   ```bash
   php artisan make:model CustomBouquet
   php artisan make:model CustomBouquetItem
   ```

### **Phase 2: Custom Bouquet Builder (Priority: HIGH)**

#### **New Controller: `CustomBouquetController`**
```php
class CustomBouquetController extends Controller 
{
    public function create()       // Show custom bouquet builder
    public function store()        // Save custom bouquet
    public function addItem()      // Add product to custom bouquet
    public function removeItem()   // Remove product from custom bouquet
    public function updateItem()   // Update quantity
    public function uploadReference() // Upload reference image
}
```

#### **New Routes**
```php
// Custom Bouquet Routes
Route::get('/custom-bouquet/create', [CustomBouquetController::class, 'create'])->name('custom.bouquet.create');
Route::post('/custom-bouquet/store', [CustomBouquetController::class, 'store'])->name('custom.bouquet.store');
Route::post('/custom-bouquet/add-item', [CustomBouquetController::class, 'addItem'])->name('custom.bouquet.add-item');
Route::post('/custom-bouquet/upload-reference', [CustomBouquetController::class, 'uploadReference'])->name('custom.bouquet.upload-reference');
```

### **Phase 3: Frontend Custom Builder (Priority: HIGH)**

#### **New View: `custom-bouquet/create.blade.php`**

**Features:**
- 📋 **Product Selection Panel** - Browse bunga by category
- 📊 **Stock Display** - Real-time stock availability
- 🛒 **Builder Cart** - Selected components with quantities
- 💰 **Price Calculator** - Real-time total calculation
- 📸 **Reference Upload** - Upload contoh bouquet yang diinginkan
- 📝 **Notes Section** - Special instructions

**Layout Structure:**
```html
<div class="custom-bouquet-builder">
    <!-- Left Panel: Product Selection -->
    <div class="product-selection">
        <div class="category-tabs">Bunga | Wrapping | Pita | Aksesoris</div>
        <div class="product-grid">
            <!-- Products with stock info -->
        </div>
    </div>
    
    <!-- Right Panel: Builder Cart -->
    <div class="builder-cart">
        <div class="selected-items">
            <!-- Selected components -->
        </div>
        <div class="reference-upload">
            <!-- Upload section -->
        </div>
        <div class="total-calculation">
            <!-- Price breakdown -->
        </div>
        <button class="add-to-main-cart">Tambah ke Keranjang</button>
    </div>
</div>
```

### **Phase 4: Integration with Unified Cart (Priority: MEDIUM)**

#### **Enhance `PublicCartController`**
```php
// New method
public function addCustomBouquet(Request $request)
{
    // Add custom bouquet to unified cart
    // Structure: 'custom_bouquet_ID_timestamp'
}
```

#### **Cart Structure Enhancement**
```php
'cart' => [
    // Regular items...
    'custom_bouquet_123_1703123456' => [
        'id' => 'custom_bouquet_123',
        'name' => 'Custom Bouquet - Romantic Mix',
        'price' => 185000,
        'qty' => 1,
        'type' => 'custom_bouquet',
        'reference_image' => 'uploads/custom_ref_123.jpg',
        'components_summary' => 'Mawar Merah 2 tangkai, Matahari 1 ikat, Wrapping Biru',
        'custom_bouquet_id' => 123
    ]
]
```

### **Phase 5: Enhanced Checkout (Priority: MEDIUM)**

#### **Checkout Form Enhancement**
```html
<!-- For custom bouquet items only -->
<div class="custom-bouquet-section" x-show="hasCustomBouquet">
    <h4>📸 Referensi Custom Bouquet</h4>
    
    <!-- Display uploaded reference -->
    <div class="reference-preview">
        <img src="uploaded_reference.jpg" alt="Referensi">
    </div>
    
    <!-- Option to upload additional reference -->
    <div class="additional-upload">
        <label>Upload Gambar Tambahan (Opsional)</label>
        <input type="file" name="additional_reference[]" multiple accept="image/*">
    </div>
    
    <!-- Special instructions -->
    <div class="special-instructions">
        <label>Instruksi Khusus Custom Bouquet</label>
        <textarea name="custom_instructions" placeholder="Contoh: Buatkan lebih compact, warna dominan pink, dll"></textarea>
    </div>
</div>
```

## 🎨 **User Experience Flow**

### **Customer Journey:**
1. **Browse** → Halaman utama → "Custom Bouquet" menu
2. **Stock Check** → Lihat bunga available + stok real-time
3. **Build** → Pilih komponen satu per satu:
   - Select category (Bunga, Wrapping, Pita)
   - Choose product & price type
   - Set quantity (dengan validasi stok)
   - Preview subtotal
4. **Reference** → Upload foto contoh bouquet yang diinginkan
5. **Review** → Lihat summary & total price
6. **Add to Cart** → Masuk ke unified cart system
7. **Checkout** → Form enhanced dengan upload additional reference
8. **Order** → Admin dapat lihat detail custom request

### **Visual Indicators:**
- 🎨 **Purple badge** untuk custom bouquet di cart
- 📸 **Camera icon** untuk items dengan reference image
- 📋 **Component list** preview di cart
- ⚡ **Real-time stock** counter saat memilih

## 🔧 **Technical Considerations**

### **Pros:**
- ✅ **Leverage existing** product & stock system
- ✅ **Unified cart** compatibility 
- ✅ **Scalable** - easy to add new component types
- ✅ **Admin friendly** - clear order details

### **Challenges & Solutions:**

#### **1. Stock Management**
**Challenge:** Real-time stock validation
**Solution:** 
```javascript
// AJAX stock check saat add item
function validateStock(productId, priceType, quantity) {
    // Check current stock vs requested quantity
    // Update UI accordingly
}
```

#### **2. Price Calculation**
**Challenge:** Dynamic pricing based on price_type
**Solution:**
```php
// Real-time price calculation
public function calculateCustomBouquetPrice($items) {
    $total = 0;
    foreach($items as $item) {
        $price = ProductPrice::where('product_id', $item['product_id'])
                            ->where('type', $item['price_type'])
                            ->first();
        $total += $price->price * $item['quantity'];
    }
    return $total;
}
```

#### **3. Image Storage**
**Challenge:** Reference image management
**Solution:**
```php
// Organized storage structure
'storage/app/public/custom-bouquets/{custom_bouquet_id}/'
├── reference.jpg      // Original uploaded reference
├── additional_1.jpg   // Additional checkout uploads
└── additional_2.jpg
```

## 📱 **Mobile Responsiveness**

### **Mobile-First Design:**
- **Collapsible panels** - Product selection & cart toggle
- **Touch-friendly** product selection
- **Swipe navigation** between categories
- **Quick add buttons** with haptic feedback
- **Mobile camera** integration for reference upload

## 🚀 **Implementation Priority**

### **WEEK 1 (Essential):**
- [ ] Database migrations & models
- [ ] Basic custom bouquet builder UI
- [ ] Product selection with stock display
- [ ] Basic add/remove functionality

### **WEEK 2 (Core Features):**
- [ ] Reference image upload
- [ ] Price calculation system
- [ ] Integration with unified cart
- [ ] Basic checkout enhancement

### **WEEK 3 (Polish):**
- [ ] Mobile responsiveness
- [ ] Advanced stock validation
- [ ] Admin panel for custom orders
- [ ] Testing & bug fixes

### **WEEK 4 (Enhancement):**
- [ ] Real-time notifications
- [ ] Advanced image handling
- [ ] Customer order history
- [ ] Analytics & reporting

## 💡 **Additional Recommendations**

### **1. Admin Panel Enhancement**
```php
// New admin features for custom bouquets
- Dashboard untuk track custom orders
- Bulk stock update untuk popular items
- Custom bouquet templates dari customer designs
- Pricing analysis untuk custom vs template bouquets
```

### **2. Customer Features**
```php
// Future enhancements
- Save custom designs sebagai favorites
- Quick reorder custom bouquets
- Share custom designs dengan friends
- Price comparison dengan template bouquets
```

### **3. Business Intelligence**
```php
// Analytics opportunities
- Popular custom combinations
- Seasonal demand patterns
- Component usage statistics
- Pricing optimization insights
```

## 🎯 **Success Metrics**

### **User Experience:**
- [ ] Custom bouquet creation < 5 minutes
- [ ] Mobile completion rate > 70%
- [ ] Customer satisfaction score > 4.5/5

### **Business Impact:**
- [ ] Increase average order value by 30%
- [ ] Reduce custom order processing time by 50%
- [ ] Customer retention improvement

### **Technical Performance:**
- [ ] Page load time < 3 seconds
- [ ] Real-time stock accuracy 99%+
- [ ] Image upload success rate > 95%

---

## 🎉 **Kesimpulan**

Fitur Custom Bouquet ini akan menjadi **game changer** untuk bisnis florist Anda karena:

1. **Unique Value Proposition** - Tidak semua florist punya custom builder
2. **Higher Revenue** - Custom orders typically have higher margins
3. **Customer Engagement** - Interactive experience builds loyalty
4. **Operational Efficiency** - Clear specifications reduce back-and-forth
5. **Scalability** - Foundation untuk future enhancements

**Next Action:** Mulai dengan Phase 1 (Database & Models) dan build incrementally! 🚀
