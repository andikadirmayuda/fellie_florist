<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    protected $fillable = [
        'product_id',
        'qty',
        'source',
        'reference_id',
        'notes'
    ];

    protected $casts = [
        'qty' => 'integer'
    ];

    /**
     * Get the product associated with this inventory log.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
