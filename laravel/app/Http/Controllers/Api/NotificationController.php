<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get pending notifications untuk admin
     */
    public function getPendingNotifications()
    {
        try {
            $notifications = PushNotificationService::getPendingNotifications();
            return response()->json($notifications);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch notifications'], 500);
        }
    }

    /**
     * Mark notification sebagai delivered
     */
    public function markAsDelivered($notificationId)
    {
        try {
            PushNotificationService::markAsDelivered($notificationId);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to mark notification'], 500);
        }
    }

    /**
     * Test push notification
     */
    public function testNotification()
    {
        try {
            $testData = (object)[
                'id' => 'TEST001',
                'customer_name' => 'Test Customer',
                'total' => 150000,
                'public_code' => 'TEST001'
            ];

            PushNotificationService::sendNewOrderNotification($testData);
            
            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send test notification'], 500);
        }
    }
}
