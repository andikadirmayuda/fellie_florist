<?php

namespace App\Http\Controllers;

use App\Models\CustomBouquet;
use App\Models\CustomBouquetItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CustomBouquetController extends Controller
{
    /**
     * Show the custom bouquet builder
     */
    public function create()
    {
        // Get all active products with their prices and current stock
        $products = Product::with(['category', 'prices'])
            ->where('is_active', true)
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();

        // Get all categories for filtering
        $categories = Category::orderBy('name')->get();

        // Create a new draft custom bouquet for this session
        $customBouquet = CustomBouquet::create([
            'name' => 'Custom Bouquet Draft',
            'status' => 'draft',
            'total_price' => 0
        ]);

        return view('custom-bouquet.create', compact('products', 'categories', 'customBouquet'));
    }

    /**
     * Get product details for AJAX requests
     */
    public function getProductDetails(Product $product)
    {
        $product->load(['prices', 'category']);
        
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'current_stock' => $product->current_stock,
                'base_unit' => $product->base_unit,
                'category' => $product->category->name ?? 'Uncategorized',
                'prices' => $product->prices->map(function ($price) {
                    return [
                        'type' => $price->type,
                        'price' => $price->price,
                        'unit_equivalent' => $price->unit_equivalent,
                        'is_default' => $price->is_default,
                        'display_name' => $this->getPriceTypeDisplayName($price->type)
                    ];
                })
            ]
        ]);
    }

    /**
     * Add item to custom bouquet
     */
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'custom_bouquet_id' => 'required|exists:custom_bouquets,id',
            'product_id' => 'required|exists:products,id',
            'price_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $customBouquet = CustomBouquet::find($validated['custom_bouquet_id']);
            $product = Product::find($validated['product_id']);

            // Get the price for this product and price type
            $productPrice = ProductPrice::where('product_id', $validated['product_id'])
                ->where('type', $validated['price_type'])
                ->first();

            if (!$productPrice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Price not found for selected product and price type.'
                ]);
            }

            // Calculate required stock based on price type
            $requiredStock = $this->calculateRequiredStock($validated['quantity'], $productPrice);

            // Check stock availability
            if ($product->current_stock < $requiredStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $product->current_stock . ' ' . $product->base_unit
                ]);
            }

            // Check if item already exists in custom bouquet
            $existingItem = CustomBouquetItem::where('custom_bouquet_id', $validated['custom_bouquet_id'])
                ->where('product_id', $validated['product_id'])
                ->where('price_type', $validated['price_type'])
                ->first();

            if ($existingItem) {
                // Update existing item
                $existingItem->quantity += $validated['quantity'];
                $existingItem->subtotal = $existingItem->calculateSubtotal();
                $existingItem->save();
                $item = $existingItem;
            } else {
                // Create new item
                $item = CustomBouquetItem::create([
                    'custom_bouquet_id' => $validated['custom_bouquet_id'],
                    'product_id' => $validated['product_id'],
                    'price_type' => $validated['price_type'],
                    'quantity' => $validated['quantity'],
                    'unit_price' => $productPrice->price,
                ]);
            }

            // Update custom bouquet total price
            $customBouquet->total_price = $customBouquet->calculateTotalPrice();
            $customBouquet->save();

            DB::commit();

            Log::info('Custom bouquet item added:', [
                'custom_bouquet_id' => $validated['custom_bouquet_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price_type' => $validated['price_type'],
                'subtotal' => $item->subtotal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added to custom bouquet successfully.',
                'item' => [
                    'id' => $item->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'price_type' => $item->price_type,
                    'price_type_display' => $item->price_type_display,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ],
                'total_price' => $customBouquet->total_price
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding item to custom bouquet: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error adding item to custom bouquet: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove item from custom bouquet
     */
    public function removeItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:custom_bouquet_items,id',
        ]);

        DB::beginTransaction();
        try {
            $item = CustomBouquetItem::find($validated['item_id']);
            $customBouquet = $item->customBouquet;

            $item->delete();

            // Update custom bouquet total price
            $customBouquet->total_price = $customBouquet->calculateTotalPrice();
            $customBouquet->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from custom bouquet successfully.',
                'total_price' => $customBouquet->total_price
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing item from custom bouquet: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error removing item from custom bouquet.'
            ]);
        }
    }

    /**
     * Update item quantity in custom bouquet
     */
    public function updateItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:custom_bouquet_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $item = CustomBouquetItem::with(['product', 'customBouquet'])->find($validated['item_id']);
            
            // Get the price info for stock calculation
            $productPrice = ProductPrice::where('product_id', $item->product_id)
                ->where('type', $item->price_type)
                ->first();

            // Calculate required stock
            $requiredStock = $this->calculateRequiredStock($validated['quantity'], $productPrice);

            // Check stock availability
            if ($item->product->current_stock < $requiredStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $item->product->current_stock . ' ' . $item->product->base_unit
                ]);
            }

            $item->quantity = $validated['quantity'];
            $item->save(); // This will trigger the subtotal calculation

            // Update custom bouquet total price
            $item->customBouquet->total_price = $item->customBouquet->calculateTotalPrice();
            $item->customBouquet->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item quantity updated successfully.',
                'item' => [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ],
                'total_price' => $item->customBouquet->total_price
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating item quantity: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating item quantity.'
            ]);
        }
    }

    /**
     * Upload reference image for custom bouquet
     */
    public function uploadReference(Request $request)
    {
        $validated = $request->validate([
            'custom_bouquet_id' => 'required|exists:custom_bouquets,id',
            'reference_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $customBouquet = CustomBouquet::find($validated['custom_bouquet_id']);

            // Delete old reference image if exists
            if ($customBouquet->reference_image && Storage::disk('public')->exists($customBouquet->reference_image)) {
                Storage::disk('public')->delete($customBouquet->reference_image);
            }

            // Store new image
            $imagePath = $request->file('reference_image')->store('custom-bouquets', 'public');
            
            $customBouquet->reference_image = $imagePath;
            $customBouquet->save();

            return response()->json([
                'success' => true,
                'message' => 'Reference image uploaded successfully.',
                'image_path' => $imagePath,
                'image_url' => Storage::url($imagePath)
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading reference image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error uploading reference image.'
            ]);
        }
    }

    /**
     * Get custom bouquet details
     */
    public function getDetails(CustomBouquet $customBouquet)
    {
        $customBouquet->load(['items.product']);

        return response()->json([
            'success' => true,
            'custom_bouquet' => [
                'id' => $customBouquet->id,
                'name' => $customBouquet->name,
                'total_price' => $customBouquet->total_price,
                'reference_image' => $customBouquet->reference_image,
                'reference_image_url' => $customBouquet->reference_image ? Storage::url($customBouquet->reference_image) : null,
                'status' => $customBouquet->status,
                'items' => $customBouquet->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price_type' => $item->price_type,
                        'price_type_display' => $item->price_type_display,
                        'unit_price' => $item->unit_price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'components_summary' => $customBouquet->getComponentsSummary()
            ]
        ]);
    }

    /**
     * Helper method to get price type display name
     */
    private function getPriceTypeDisplayName($type)
    {
        $names = [
            'per_tangkai' => 'Per Tangkai',
            'ikat_5' => 'Ikat 5',
            'ikat_10' => 'Ikat 10',
            'ikat_20' => 'Ikat 20',
            'reseller' => 'Reseller',
            'normal' => 'Normal',
            'promo' => 'Promo'
        ];

        return $names[$type] ?? $type;
    }

    /**
     * Helper method to calculate required stock based on price type
     */
    private function calculateRequiredStock($quantity, $productPrice)
    {
        return $quantity * $productPrice->unit_equivalent;
    }

    public function finalize($id)
    {
        try {
            Log::info('Attempting to finalize custom bouquet with ID: ' . $id);
            
            // Find the custom bouquet
            $customBouquet = CustomBouquet::find($id);
            if (!$customBouquet) {
                Log::error('Custom bouquet not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Custom bouquet tidak ditemukan.'
                ], 404);
            }
            
            Log::info('Found custom bouquet ID: ' . $customBouquet->id);
            
            // Check if custom bouquet has items
            $itemsCount = $customBouquet->items()->count();
            Log::info('Custom bouquet has ' . $itemsCount . ' items');
            
            if ($itemsCount === 0) {
                Log::warning('Custom bouquet has no items');
                return response()->json([
                    'success' => false,
                    'message' => 'Custom bouquet tidak memiliki item. Tambahkan beberapa bunga terlebih dahulu.'
                ], 400);
            }

            // Update status to finalized
            Log::info('Updating custom bouquet status to finalized');
            $customBouquet->update(['status' => 'finalized']);

            Log::info('Custom bouquet finalized successfully');
            return response()->json([
                'success' => true,
                'message' => 'Custom bouquet berhasil diselesaikan.',
                'custom_bouquet' => $customBouquet->load(['items.product'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error finalizing custom bouquet: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan custom bouquet. Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
