<?php
// Add these routes to web.php under the middleware('auth') group

// Notification Panel Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/api/admin/notifications', [AdminNotificationController::class, 'getAll'])->name('api.admin.notifications.all');
    Route::post('/api/admin/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('api.admin.notifications.read');
    Route::post('/api/admin/notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('api.admin.notifications.mark-all-read');
});
