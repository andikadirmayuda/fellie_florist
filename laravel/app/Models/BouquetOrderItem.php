<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'bouquet_order_id', 'product_id', 'quantity', 'price'
    ];
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function bouquetOrder() {
        return $this->belongsTo(BouquetOrder::class);
    }
}
