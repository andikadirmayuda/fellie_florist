<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class StockHold extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'status',
        'released_at'
    ];

    protected $casts = [
        'released_at' => 'datetime',
    ];

    // Relasi
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scope untuk hold yang masih aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'hold')
                    ->whereNull('released_at');
    }

    // Accessor untuk durasi hold dalam jam
    public function getDurationAttribute(): float
    {
        $start = Carbon::parse($this->created_at);
        $end = $this->released_at ?? Carbon::now();
        
        return round($start->diffInHours($end, true), 2);
    }

    // Boot method untuk memastikan released_at sesuai dengan status
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($stockHold) {
            if ($stockHold->status === 'hold') {
                $stockHold->released_at = null;
            } elseif ($stockHold->status === 'released' && !$stockHold->released_at) {
                $stockHold->released_at = now();
            }
        });
    }
}
