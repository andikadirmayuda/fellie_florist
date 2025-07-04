<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetSaleItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'bouquet_sale_id', 'product_id', 'quantity', 'price'
    ];
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function bouquetSale() {
        return $this->belongsTo(BouquetSale::class);
    }
}
