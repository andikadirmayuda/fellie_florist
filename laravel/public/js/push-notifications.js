// Push Notification Manager
class PushNotificationManager {
    constructor() {
        this.isSupported = 'Notification' in window && 'serviceWorker' in navigator;
        this.permission = this.isSupported ? Notification.permission : 'denied';
        this.init();
    }

    async init() {
        if (!this.isSupported) {
            console.log('Push notifications not supported');
            return;
        }

        // Register service worker
        try {
            const registration = await navigator.serviceWorker.register('/sw.js');
            console.log('Service Worker registered:', registration);
            
            // Start polling for notifications
            this.startPolling();
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

        const defaultOptions = {
            icon: '/logo-fellie-02.png',
            badge: '/logo-fellie-02.png',
            tag: 'fellie-notification',
            requireInteraction: true,
            actions: [
                {
                    action: 'view',
                    title: 'Lihat Detail'
                },
                {
                    action: 'dismiss', 
                    title: 'Tutup'
                }
            ]
        };

        const notification = new Notification(title, { ...defaultOptions, ...options });
        
        notification.onclick = function(event) {
            event.preventDefault();
            if (options.url) {
                window.open(options.url, '_blank');
            }
            notification.close();
        };

        return notification;
    }

    startPolling() {
        // Poll untuk notifikasi baru setiap 30 detik
        setInterval(async () => {
            try {
                const response = await fetch('/api/admin/notifications/pending');
                const notifications = await response.json();
                
                notifications.forEach(notification => {
                    this.showNotification(
                        notification.message.title,
                        {
                            body: notification.message.body,
                            icon: notification.message.icon,
                            tag: notification.message.tag,
                            url: notification.message.url,
                            data: notification.message.data
                        }
                    );

                    // Mark as delivered
                    fetch(`/api/admin/notifications/${notification.id}/delivered`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                });
            } catch (error) {
                console.error('Error polling notifications:', error);
            }
        }, 30000); // 30 seconds
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

// Auto-request permission saat halaman dimuat
document.addEventListener('DOMContentLoaded', async function() {
    // Tampilkan tombol enable notification jika belum granted
    if (pushManager.permission !== 'granted') {
        showNotificationPrompt();
    }
});

function showNotificationPrompt() {
    const prompt = document.createElement('div');
    prompt.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 z-50 bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg';
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
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="bg-blue-700 px-4 py-2 rounded font-medium hover:bg-blue-800">
                    Nanti
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(prompt);
}

async function enableNotifications() {
    const granted = await pushManager.requestPermission();
    if (granted) {
        // Test notification
        await pushManager.testNotification();
        
        // Remove prompt
        document.querySelector('.fixed.top-4').remove();
        
        // Show success message
        showSuccessMessage('✅ Notifikasi berhasil diaktifkan! Anda akan mendapat alert real-time untuk pesanan baru.');
    } else {
        showErrorMessage('❌ Gagal mengaktifkan notifikasi. Silakan aktifkan melalui pengaturan browser.');
    }
}

function showSuccessMessage(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transform transition-transform duration-300 translate-x-full';
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
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

function showErrorMessage(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg';
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">×</button>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 5000);
}
