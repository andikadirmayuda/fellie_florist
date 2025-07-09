<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'bouquet_id', 'size_id', 'price'
    ];

    public function bouquet()
    {
        return $this->belongsTo(Bouquet::class);
    }

    public function size()
    {
        return $this->belongsTo(BouquetSize::class, 'size_id');
    }
}
