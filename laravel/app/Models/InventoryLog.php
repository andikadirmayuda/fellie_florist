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
        'notes',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    /**
     * Get the product associated with this inventory log.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for filtering logs by source
     */
    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Get formatted quantity with sign
     */
    public function getFormattedQuantityAttribute(): string
    {
        return ($this->qty > 0 ? '+' : '') . $this->qty;
    }
}
