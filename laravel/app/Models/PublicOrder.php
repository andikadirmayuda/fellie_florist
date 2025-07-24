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
        'payment_status',
        'amount_paid',
        'payment_proof',
        'wa_number',
        'packing_photo',
        'info',
    ];

    public function items()
    {
        return $this->hasMany(PublicOrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(PublicOrderPayment::class, 'public_order_id');
    }
}
