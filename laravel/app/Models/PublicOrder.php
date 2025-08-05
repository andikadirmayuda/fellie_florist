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
        'notes',
        'status',
        'payment_status',
        'amount_paid',
        'payment_proof',
        'wa_number',
        'packing_photo',
        'packing_files',
    ];

    protected $casts = [
        'packing_files' => 'array',
    ];

    protected $appends = ['total', 'order_number'];

    public function items()
    {
        return $this->hasMany(PublicOrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(PublicOrderPayment::class, 'public_order_id');
    }

    // Calculate total from items
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    // Get order number from public_code or generate one
    public function getOrderNumberAttribute()
    {
        return $this->public_code ?? 'PO-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Get customer phone from wa_number
    public function getCustomerPhoneAttribute()
    {
        return $this->wa_number;
    }

    // Default delivery fee (can be customized based on business logic)
    public function getDeliveryFeeAttribute()
    {
        return 0; // You can add logic here based on delivery_method or destination
    }

    /**
     * Generate WhatsApp notification URL for employee group
     */
    public function getEmployeeGroupWhatsAppUrlAttribute()
    {
        $message = \App\Services\WhatsAppNotificationService::generateNewOrderMessage($this);
        return $message ? \App\Services\WhatsAppNotificationService::generateEmployeeGroupWhatsAppUrl($message) : null;
    }

    /**
     * Generate WhatsApp notification message for employees
     */
    public function getEmployeeNotificationMessageAttribute()
    {
        return \App\Services\WhatsAppNotificationService::generateNewOrderMessage($this);
    }

    /**
     * Check if order can be shared to employee group
     */
    public function canShareToEmployeeGroupAttribute()
    {
        return !empty($this->public_code) && $this->items->count() > 0;
    }
}
