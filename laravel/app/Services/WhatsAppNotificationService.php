<?php

namespace App\Services;

use App\Models\PublicOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Generate pesan WhatsApp untuk pesanan baru
     */
    public static function generateNewOrderMessage(PublicOrder $order)
    {
        try {
            // Load items if not already loaded
            if (!$order->relationLoaded('items')) {
                $order->load('items');
            }
            
            // Format tanggal Indonesia
            $createdAt = $order->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i');
            $pickupDate = Carbon::parse($order->pickup_date)->format('d/m/Y');
            
            // Detail pesanan
            $orderDetails = "• Kode: {$order->public_code}\n";
            $orderDetails .= "• Nama: {$order->customer_name}\n";
            $orderDetails .= "• WhatsApp: {$order->wa_number}\n";
            $orderDetails .= "• Tanggal Pesan: {$createdAt}\n";
            $orderDetails .= "• Tanggal Ambil: {$pickupDate} {$order->pickup_time}\n";
            $orderDetails .= "• Metode: {$order->delivery_method}\n";
            if ($order->destination) {
                $orderDetails .= "• Tujuan: {$order->destination}\n";
            }
            $orderDetails .= "• Status: " . ucfirst($order->status) . "\n";
            $orderDetails .= "• Status Bayar: " . ucfirst($order->payment_status);
            
            // Item pesanan
            $orderItems = "";
            $total = 0;
            foreach ($order->items as $item) {
                $subtotal = $item->quantity * $item->price;
                $total += $subtotal;
                $orderItems .= "• {$item->product_name} x{$item->quantity} = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
            }
            
            // Format total
            $formattedTotal = "Rp " . number_format($total, 0, ',', '.');
            
            // Catatan (jika ada)
            $notes = "";
            if ($order->notes) {
                $notes = "📝 *Catatan:*\n{$order->notes}\n\n";
            }
            
            // Link invoice (jika ada)
            $invoiceLink = "";
            if ($order->public_code) {
                $invoiceUrl = route('public.order.invoice', ['public_code' => $order->public_code]);
                $invoiceLink = "🔗 *Link Invoice:*\n{$invoiceUrl}\n\n";
            }
            
            // Build message dari template
            $template = config('whatsapp.message_templates.new_order');
            $message = str_replace([
                '{order_details}',
                '{order_items}',
                '{total}',
                '{notes}',
                '{invoice_link}'
            ], [
                $orderDetails,
                $orderItems,
                $formattedTotal,
                $notes,
                $invoiceLink
            ], $template);
            
            return $message;
            
        } catch (\Exception $e) {
            Log::error('Error generating WhatsApp message for new order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }
    
    /**
     * Generate pesan WhatsApp untuk update status
     */
    public static function generateStatusUpdateMessage(PublicOrder $order, $oldStatus, $newStatus)
    {
        try {
            $invoiceLink = "";
            if ($order->public_code) {
                $invoiceUrl = route('public.order.invoice', ['public_code' => $order->public_code]);
                $invoiceLink = "🔗 *Link Invoice:*\n{$invoiceUrl}\n\n";
            }
            
            $template = config('whatsapp.message_templates.status_update');
            $message = str_replace([
                '{order_code}',
                '{old_status}',
                '{new_status}',
                '{customer_name}',
                '{invoice_link}'
            ], [
                $order->public_code,
                ucfirst($oldStatus),
                ucfirst($newStatus),
                $order->customer_name,
                $invoiceLink
            ], $template);
            
            return $message;
            
        } catch (\Exception $e) {
            Log::error('Error generating WhatsApp message for status update', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }
    
    /**
     * Generate URL WhatsApp untuk grup karyawan atau individual
     */
    public static function generateEmployeeGroupWhatsAppUrl($message)
    {
        return self::generateWhatsAppUrl($message);
    }
    
    /**
     * Generate URL WhatsApp umum (mendukung grup link dan nomor)
     */
    public static function generateWhatsAppUrl($message)
    {
        $employeeGroup = config('whatsapp.employee_group');
        $groupType = config('whatsapp.employee_group_type', 'group_link');
        
        if ($groupType === 'group_link' && filter_var($employeeGroup, FILTER_VALIDATE_URL)) {
            // Untuk link grup WhatsApp, kita tidak bisa langsung mengirim pesan
            // User harus join grup dulu, lalu paste pesan manual
            return $employeeGroup;
        } else {
            // Fallback ke nomor telepon jika bukan link grup
            $encodedMessage = urlencode($message);
            // Remove protocol dari URL jika ada
            $cleanNumber = preg_replace('/^https?:\/\//', '', $employeeGroup);
            $cleanNumber = preg_replace('/[^0-9]/', '', $cleanNumber);
            return "https://wa.me/{$cleanNumber}?text={$encodedMessage}";
        }
    }
    
    /**
     * Get info target WhatsApp (grup atau individual)
     */
    public static function getTargetInfo()
    {
        $employeeGroup = config('whatsapp.employee_group');
        $groupType = config('whatsapp.employee_group_type', 'group_link');
        
        if ($groupType === 'group_link' && filter_var($employeeGroup, FILTER_VALIDATE_URL)) {
            return [
                'type' => 'group',
                'name' => 'Grup Karyawan Fellie Florist',
                'target' => $employeeGroup,
                'note' => 'Pesan akan disalin ke clipboard. Buka grup dan paste manual.'
            ];
        } else {
            return [
                'type' => 'individual',
                'name' => 'Nomor Karyawan',
                'target' => $employeeGroup,
                'note' => 'Pesan akan dikirim langsung ke WhatsApp.'
            ];
        }
    }
    
    /**
     * Generate URL WhatsApp untuk customer
     */
    public static function generateCustomerWhatsAppUrl($phoneNumber, $message)
    {
        // Format nomor WhatsApp (hapus 0 di depan, tambah 62)
        $formattedNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $phoneNumber));
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$formattedNumber}?text={$encodedMessage}";
    }
}
