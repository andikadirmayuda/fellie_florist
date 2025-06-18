<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'total'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stockHolds(): HasMany
    {
        return $this->hasMany(StockHold::class);
    }

    public static function boot()
    {
        parent::boot();

        // Hapus stock holds saat order dihapus
        static::deleting(function ($order) {
            $order->stockHolds()->delete();
        });
    }
}
