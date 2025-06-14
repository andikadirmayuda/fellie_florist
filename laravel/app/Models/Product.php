<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('auto_code', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhereHas('category', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
        });
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }

    public function scopeNeedsRestock($query)
    {
        return $query->whereRaw('current_stock < min_stock');
    }

    public function getFormattedStockAttribute()
    {
        return number_format($this->current_stock) . ' ' . $this->base_unit;
    }

    public function getNeedsRestockAttribute()
    {
        return $this->current_stock < $this->min_stock;
    }    protected $fillable = [
        'category_id',
        'auto_code',
        'name',
        'description',
        'base_unit',
        'current_stock',
        'min_stock',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'current_stock' => 'integer',
        'min_stock' => 'integer',
    ];    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function getPriceByType($type)
    {
        return $this->prices()->where('type', $type)->first();
    }
}
