<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bouquet extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'category_id', 'description', 'image'
    ];

    public function category()
    {
        return $this->belongsTo(BouquetCategory::class, 'category_id');
    }

    public function prices()
    {
        return $this->hasMany(BouquetPrice::class);
    }

    public function templateItems()
    {
        return $this->hasMany(BouquetTemplateItem::class);
    }
}
