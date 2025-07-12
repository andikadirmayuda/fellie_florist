<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bouquet extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name', 
        'category_id', 
        'description', 
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(BouquetCategory::class, 'category_id');
    }

    public function prices()
    {
        return $this->hasMany(BouquetPrice::class);
    }

    public function components()
    {
        return $this->hasMany(BouquetComponent::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(BouquetSize::class, 'bouquet_prices', 'bouquet_id', 'size_id')
                    ->using(BouquetPrice::class)
                    ->withPivot('price')
                    ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(BouquetOrderItem::class);
    }

    // Helper method untuk cek apakah bouquet bisa dihapus
    public function canDelete()
    {
        return !$this->orderItems()->exists();
    }
}
