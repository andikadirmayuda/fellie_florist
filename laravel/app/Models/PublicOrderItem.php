<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $public_order_id
 * @property int|null $product_id
 * @property string $product_name
 * @property string|null $price_type
 * @property int|null $unit_equivalent
 * @property int $quantity
 * @property float $price
 * @property string|null $item_type
 * @property int|null $custom_bouquet_id
 * @property string|null $reference_image
 * @property string|null $custom_instructions
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\PublicOrder $order
 * @property \App\Models\Product|null $product
 */
class PublicOrderItem extends Model
{
    protected $fillable = [
        'public_order_id',
        'product_id',
        'product_name',
        'price_type',
        'unit_equivalent',
        'quantity',
        'price',
        'item_type',
        'custom_bouquet_id',
        'reference_image',
        'custom_instructions',
    ];

    public function order()
    {
        return $this->belongsTo(PublicOrder::class, 'public_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customBouquet()
    {
        return $this->belongsTo(CustomBouquet::class, 'custom_bouquet_id');
    }
}
