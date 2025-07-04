<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BouquetOrder extends Model
{
    protected $fillable = [
        'customer_name', 'wa_number', 'notes', 'total_price'
    ];
    public function items() {
        return $this->hasMany(BouquetOrderItem::class);
    }
}
