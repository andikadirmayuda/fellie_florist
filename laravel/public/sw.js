// Service Worker untuk Push Notifications - Fellie Florist
const CACHE_NAME = 'fellie-florist-v1';
const urlsToCache = [
    '/logo-fellie-02.png',
    '/css/app.css',
    '/js/app.js'
];

// Install event
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

// Push event handler
self.addEventListener('push', function(event) {
    console.log('Push received:', event);
    
    let notificationData = {};
    
    try {
        notificationData = event.data ? JSON.parse(event.data.text()) : {};
    } catch (e) {
        notificationData = {
            title: '🌸 Fellie Florist',
            body: event.data ? event.data.text() : 'Pesanan baru masuk!',
            url: '/admin/public-orders'
        };
    }

    const options = {
        body: notificationData.body || 'Pesanan baru masuk!',
        icon: '/logo-fellie-02.png',
        badge: '/logo-fellie-02.png',
        image: notificationData.image || '/logo-fellie-02.png',
        tag: notificationData.tag || 'fellie-order-notification',
        requireInteraction: true,
        vibrate: [200, 100, 200], // Vibration pattern
        actions: [
            {
                action: 'view',
                title: '👀 Lihat Detail',
                icon: '/logo-fellie-02.png'
            },
            {
                action: 'dismiss',
                title: '✖️ Tutup',
                icon: '/logo-fellie-02.png'
            }
        ],
        data: {
            url: notificationData.url || '/admin/public-orders',
            order_id: notificationData.order_id,
            type: notificationData.type || 'general'
        },
        timestamp: Date.now()
    };

    event.waitUntil(
        self.registration.showNotification(
            notificationData.title || '🌸 Fellie Florist', 
            options
        )
    );
});

// Notification click handler
self.addEventListener('notificationclick', function(event) {
    console.log('Notification clicked:', event);
    
    event.notification.close();

    if (event.action === 'view' || !event.action) {
        // Open the app/page when notification or "view" action is clicked
        event.waitUntil(
            clients.matchAll({ type: 'window' }).then(function(clientList) {
                const url = event.notification.data.url || '/admin/public-orders';
                
                // Check if there's already a window/tab open with the target URL
                for (let i = 0; i < clientList.length; i++) {
                    const client = clientList[i];
                    if (client.url.includes('/admin') && 'focus' in client) {
                        client.focus();
                        client.navigate(url);
                        return;
                    }
                }
                
                // If no suitable tab is found, open a new one
                if (clients.openWindow) {
                    return clients.openWindow(url);
                }
            })
        );
    } else if (event.action === 'dismiss') {
        // Just close the notification (already done above)
        console.log('Notification dismissed');
    }
});

// Background sync (for offline functionality)
self.addEventListener('sync', function(event) {
    if (event.tag === 'background-sync') {
        console.log('Background sync triggered');
        // Handle background sync here if needed
    }
});

// Fetch event (for caching)
self.addEventListener('fetch', function(event) {
    // Only handle GET requests for caching
    if (event.request.method === 'GET') {
        event.respondWith(
            caches.match(event.request).then(function(response) {
                // Return cached version or fetch from network
                return response || fetch(event.request);
            })
        );
    }
});
