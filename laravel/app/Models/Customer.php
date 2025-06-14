<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'type',
        'address',
        'city',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Get the customer's full address
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}";
    }

    /**
     * Get the formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        // Format phone number if needed
        return $this->phone;
    }

    /**
     * Get type badge HTML
     */
    public function getTypeBadgeAttribute()
    {
        $colors = [
            'walk-in' => 'bg-gray-100 text-gray-800',
            'reseller' => 'bg-blue-100 text-blue-800',
            'regular' => 'bg-green-100 text-green-800',
        ];

        $color = $colors[$this->type] ?? 'bg-gray-100 text-gray-800';

        return "<span class='px-2 py-1 text-xs font-medium rounded-full {$color}'>{$this->type}</span>";
    }

    /**
     * Scope a query to only include customers of a given type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
