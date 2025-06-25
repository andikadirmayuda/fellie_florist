<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_time',
        'total',
        'subtotal',
        'payment_method',
        'public_code', // untuk public receipt
        'cash_given',
        'change',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
