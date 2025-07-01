<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicOrder extends Model
{
    protected $fillable = [
        'public_code',
        'customer_name',
        'pickup_date',
        'pickup_time',
        'delivery_method',
        'destination',
        'status',
        'wa_number',
    ];

    public function items()
    {
        return $this->hasMany(PublicOrderItem::class);
    }
}
