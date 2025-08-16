<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $public_code
 * @property string $customer_name
 * @property string $pickup_date
 * @property string|null $pickup_time
 * @property string $delivery_method
 * @property string|null $destination
 * @property string|null $notes
 * @property string $status
 * @property string|null $payment_status
 * @property float|null $amount_paid
 * @property string|null $payment_proof
 * @property string|null $wa_number
 * @property string|null $packing_photo
 * @property array|null $packing_files
 * @property float $shipping_fee
 * @property bool $stock_holded
 * @property string|null $info
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property float $total
 * @property string $order_number
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\PublicOrderItem[] $items
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\PublicOrderPayment[] $payments
 */
class PublicOrder extends Model
{
    /**
     * Check if this is an online order
     */
    public function isOnlineOrder()
    {
        // Pesanan dianggap online jika memiliki public_code dan wa_number
        return !empty($this->public_code) && !empty($this->wa_number);
    }

    protected $fillable = [
        'public_code',
        'customer_name',
        'receiver_name',
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
        'receiver_wa',
        'packing_photo',
        'packing_files',
        'shipping_fee',
        'stock_holded',
        'info',
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

    public function customBouquet()
    {
        return $this->hasOne(CustomBouquet::class);
    }

    // Calculate total from items
    public function getTotalAttribute()
    {
        // Load items relation if not already loaded
        if (!$this->relationLoaded('items')) {
            $this->load('items');
        }

        $itemsTotal = $this->items->sum(function ($item) {
            return ($item->quantity ?? 0) * ($item->price ?? 0);
        });

        return $itemsTotal + ($this->shipping_fee ?? 0);
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
