// Push Notification Manager
class PushNotificationManager {
    constructor() {
        this.isSupported = 'Notification' in window && 'serviceWorker' in navigator;
        this.permission = this.isSupported ? Notification.permission : 'denied';
        this.serviceWorkerRegistration = null;
        this.notificationQueue = new Set(); // Prevent duplicate notifications
        this.isPolling = false; // Prevent multiple polling
        this.init();
    }

    async init() {
        if (!this.isSupported) {
            console.log('Push notifications not supported');
            return;
        }

        // Register service worker
        try {
            this.serviceWorkerRegistration = await navigator.serviceWorker.register('/sw.js');
            console.log('Service Worker registered:', this.serviceWorkerRegistration);
            
            // Start polling for notifications only once
            if (!this.isPolling) {
                this.startPolling();
            }
        } catch (error) {
            console.error('Service Worker registration failed:', error);
        }
    }

    async requestPermission() {
        if (!this.isSupported) {
            return false;
        }

        if (this.permission === 'granted') {
            return true;
        }

        const permission = await Notification.requestPermission();
        this.permission = permission;
        
        return permission === 'granted';
    }

    async showNotification(title, options = {}) {
        if (!await this.requestPermission()) {
            console.log('Notification permission denied');
            return;
        }

        // Create unique notification key to prevent duplicates
        const notificationKey = `${title}-${options.body || ''}-${Date.now()}`;
        if (this.notificationQueue.has(notificationKey)) {
            console.log('Duplicate notification prevented');
            return;
        }
        this.notificationQueue.add(notificationKey);

        const defaultOptions = {
            icon: '/logo-fellie-02.png',
            badge: '/logo-fellie-02.png',
            tag: 'fellie-notification',
            requireInteraction: false, // Changed to false to prevent persistent notifications
            // Removed actions as they're only supported in ServiceWorker notifications
        };

        try {
            // Use ServiceWorker registration if available for better browser support
            if (this.serviceWorkerRegistration) {
                await this.serviceWorkerRegistration.showNotification(title, { 
                    ...defaultOptions, 
                    ...options,
                    actions: [ // Actions only work with ServiceWorker
                        {
                            action: 'view',
                            title: 'Lihat Detail'
                        },
                        {
                            action: 'dismiss', 
                            title: 'Tutup'
                        }
                    ]
                });
            } else {
                // Fallback to regular Notification API (without actions)
                const notification = new Notification(title, { ...defaultOptions, ...options });
                
                notification.onclick = function(event) {
                    event.preventDefault();
                    if (options.url) {
                        window.open(options.url, '_blank');
                    }
                    notification.close();
                };
            }

            // Clean up notification queue after 5 seconds
            setTimeout(() => {
                this.notificationQueue.delete(notificationKey);
            }, 5000);

        } catch (error) {
            console.error('Error showing notification:', error);
            this.notificationQueue.delete(notificationKey);
        }
    }

    startPolling() {
        // Prevent multiple polling instances
        if (this.isPolling) {
            console.log('Polling already active');
            return;
        }

        this.isPolling = true;
        console.log('Starting notification polling...');

        // Poll untuk notifikasi baru setiap 30 detik - optimized interval
        this.pollingInterval = setInterval(async () => {
            try {
                const response = await fetch('/api/admin/notifications/pending');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const notifications = await response.json();
                
                notifications.forEach(async (notification) => {
                    // Create unique identifier to prevent duplicate notifications
                    const uniqueId = `${notification.id}-${notification.message.title}`;
                    
                    // Additional check to prevent showing same notification
                    if (this.notificationQueue.has(uniqueId)) {
                        console.log('Duplicate notification prevented:', uniqueId);
                        return;
                    }
                    
                    await this.showNotification(
                        notification.message.title,
                        {
                            body: notification.message.body,
                            icon: notification.message.icon,
                            tag: uniqueId, // Use unique tag to prevent duplicates
                            url: notification.message.url,
                            data: notification.message.data
                        }
                    );

                    // Mark as delivered with error handling
                    try {
                        await fetch(`/api/admin/notifications/${notification.id}/delivered`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                    } catch (markError) {
                        console.error('Error marking notification as delivered:', markError);
                    }
                });
            } catch (error) {
                console.error('Error polling notifications:', error);
                // Don't stop polling on error, just log it
            }
        }, 30000); // 30 seconds - balanced for real-time without overloading
    }

    // Stop polling method
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.isPolling = false;
            console.log('Notification polling stopped');
        }
    }

    // Test notification
    async testNotification() {
        await this.showNotification('🌸 Test Notification', {
            body: 'Push notification berhasil diaktifkan untuk Fellie Florist!',
            url: window.location.href
        });
    }
}

// Initialize push notification manager
const pushManager = new PushNotificationManager();

// Track if prompt is already shown
let isPromptShown = false;

// Auto-request permission saat halaman dimuat
document.addEventListener('DOMContentLoaded', async function() {
    // Don't show popup prompt - let the banner handle permission request
    // The banner in push-notifications.blade.php will handle the UI
    console.log('Push notification manager initialized, banner will handle permission');
});

function showNotificationPrompt() {
    // Prevent multiple prompts
    if (isPromptShown || document.querySelector('.notification-prompt')) {
        return;
    }
    
    isPromptShown = true;
    const prompt = document.createElement('div');
    prompt.className = 'notification-prompt fixed top-4 left-1/2 transform -translate-x-1/2 z-50 bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg';
    prompt.innerHTML = `
        <div class="flex items-center space-x-4">
            <div class="flex-1">
                <p class="font-semibold">🔔 Aktifkan Notifikasi</p>
                <p class="text-sm opacity-90">Dapatkan alert real-time untuk pesanan baru</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="enableNotifications()" class="bg-white text-blue-600 px-4 py-2 rounded font-medium hover:bg-gray-100">
                    Aktifkan
                </button>
                <button onclick="dismissNotificationPrompt()" class="bg-blue-700 px-4 py-2 rounded font-medium hover:bg-blue-800">
                    Nanti
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(prompt);
}

function dismissNotificationPrompt() {
    const prompt = document.querySelector('.notification-prompt');
    if (prompt) {
        prompt.remove();
    }
    isPromptShown = false;
}

async function enableNotifications() {
    try {
        const granted = await pushManager.requestPermission();
        if (granted) {
            // Test notification
            await pushManager.testNotification();
            
            // Remove prompt
            dismissNotificationPrompt();
            
            // Show success message (prevent multiple)
            if (!document.querySelector('.success-notification')) {
                showSuccessMessage('✅ Notifikasi berhasil diaktifkan! Anda akan mendapat alert real-time untuk pesanan baru.');
            }
        } else {
            // Remove prompt
            dismissNotificationPrompt();
            
            // Show error message (prevent multiple) 
            if (!document.querySelector('.error-notification')) {
                showErrorMessage('❌ Gagal mengaktifkan notifikasi. Silakan aktifkan melalui pengaturan browser.');
            }
        }
    } catch (error) {
        console.error('Error enabling notifications:', error);
        dismissNotificationPrompt();
        if (!document.querySelector('.error-notification')) {
            showErrorMessage('❌ Terjadi kesalahan saat mengaktifkan notifikasi.');
        }
    }
}

function showSuccessMessage(message) {
    // Prevent multiple success messages
    const existing = document.querySelector('.success-notification');
    if (existing) {
        existing.remove();
    }

    const notification = document.createElement('div');
    notification.className = 'success-notification fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transform transition-transform duration-300 translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

function showErrorMessage(message) {
    // Prevent multiple error messages
    const existing = document.querySelector('.error-notification');
    if (existing) {
        existing.remove();
    }

    const notification = document.createElement('div');
    notification.className = 'error-notification fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg transform transition-transform duration-300 translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">×</button>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}
