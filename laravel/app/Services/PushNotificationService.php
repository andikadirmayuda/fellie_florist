<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Send push notification untuk pesanan baru
     */
    public static function sendNewOrderNotification($order)
    {
        try {
            Log::info('Attempting to send new order notification', [
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'total' => $order->total
            ]);

            $message = [
                'title' => '🌸 Pesanan Baru Masuk!',
                'body' => "{$order->customer_name} - Total: Rp " . number_format($order->total, 0, ',', '.'),
                'icon' => '/logo-fellie-02.png',
                'badge' => '/logo-fellie-02.png',
                'tag' => 'new-order-' . $order->id,
                'url' => route('admin.public-orders.show', $order->id),
                'data' => [
                    'order_id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'total' => $order->total,
                    'type' => 'new_order'
                ]
            ];

            // Broadcast ke semua admin yang online
            self::broadcastToAdmins($message);

            Log::info('New order notification sent successfully', [
                'order_id' => $order->id,
                'message_title' => $message['title']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error sending push notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send push notification untuk update status
     */
    public static function sendStatusUpdateNotification($order, $oldStatus, $newStatus)
    {
        try {
            $statusText = [
                'pending' => 'Menunggu',
                'processed' => 'Diproses', 
                'packing' => 'Dikemas',
                'ready' => 'Siap',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan'
            ];

            $message = [
                'title' => '🔔 Update Status Pesanan',
                'body' => "{$order->public_code} → " . ($statusText[$newStatus] ?? ucfirst($newStatus)),
                'icon' => '/logo-fellie-02.png',
                'tag' => 'status-update-' . $order->id,
                'url' => route('admin.public-orders.show', $order->id),
                'data' => [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'type' => 'status_update'
                ]
            ];

            self::broadcastToAdmins($message);

            return true;
        } catch (\Exception $e) {
            Log::error('Error sending status update notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Broadcast message ke semua admin yang online
     */
    private static function broadcastToAdmins($message)
    {
        // Implementasi broadcasting (bisa menggunakan Pusher, WebSocket, atau Server-Sent Events)
        // Untuk saat ini, kita simpan di session/cache untuk pickup via polling
        
        $notifications = cache()->get('admin_notifications', []);
        $newNotification = [
            'id' => uniqid(),
            'message' => $message,
            'timestamp' => now(),
            'delivered' => false
        ];
        
        $notifications[] = $newNotification;
        
        cache()->put('admin_notifications', $notifications, now()->addHours(1));
        
        Log::info('Notification added to cache', [
            'notification_id' => $newNotification['id'],
            'total_cached_notifications' => count($notifications),
            'message_title' => $message['title']
        ]);
    }

    /**
     * Get pending notifications untuk admin
     */
    public static function getPendingNotifications()
    {
        $notifications = cache()->get('admin_notifications', []);
        $pending = array_filter($notifications, function($notification) {
            return !$notification['delivered'] && 
                   $notification['timestamp']->diffInMinutes(now()) < 60;
        });

        // Debug logging
        Log::info('Retrieved pending notifications', [
            'total_notifications' => count($notifications),
            'pending_count' => count($pending),
            'pending_ids' => array_column($pending, 'id')
        ]);

        return array_values($pending);
    }

    /**
     * Mark notification sebagai delivered
     */
    public static function markAsDelivered($notificationId)
    {
        $notifications = cache()->get('admin_notifications', []);
        foreach ($notifications as &$notification) {
            if ($notification['id'] === $notificationId) {
                $notification['delivered'] = true;
                break;
            }
        }
        cache()->put('admin_notifications', $notifications, now()->addHours(1));
    }
}
