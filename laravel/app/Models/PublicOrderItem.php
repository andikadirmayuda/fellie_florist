<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function order()
    {
        return $this->belongsTo(PublicOrder::class, 'public_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
