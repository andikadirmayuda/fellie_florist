<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <style>
        html {
            scroll-behavior: smooth;
        }

        /* Prevent sidebar flash before Alpine.js loads */
        [x-cloak] {
            display: none !important;
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ app_name() }}</title>
    <link rel="icon" type="image/png" href="{{ app_logo() }}" sizes="32x32">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->

    @vite(['resources/css/app.css', 'resources/css/modern-theme.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="{
                isSidebarOpen: false,
                init() {
                    // Ensure sidebar is closed immediately on initialization
                    this.isSidebarOpen = false;
                    // Clear any stored state to prevent conflicts
                    if (typeof localStorage !== 'undefined') {
                        localStorage.removeItem('sidebarOpen');
                    }
                    console.log('Sidebar initialized with state:', this.isSidebarOpen);
                },
                toggleSidebar() {
                    console.log('Toggle sidebar clicked, current state:', this.isSidebarOpen);
                    this.isSidebarOpen = !this.isSidebarOpen;
                    console.log('New sidebar state:', this.isSidebarOpen);
                }
            }" x-init="init()">
        <!-- Sidebar -->
        <div x-show="isSidebarOpen" x-cloak x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-2xl border-r border-gray-200 overflow-y-auto backdrop-blur-xl">
            @include('layouts.sidebar')
        </div>

        <!-- Overlay -->
        <div x-show="isSidebarOpen" x-cloak x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="isSidebarOpen = false"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm z-20 lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300" :class="{ 'lg:ml-64': isSidebarOpen }">
            <!-- Top Navigation - Sticky Header -->
            <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-gray-200 shadow-sm">
                <div class="flex items-center h-16 px-4">
                    <button @click="toggleSidebar()"
                        class="text-gray-600 hover:text-pink-500 hover:bg-pink-50 focus:outline-none focus:ring-2 focus:ring-pink-500 rounded-lg p-2 transition-all duration-200"
                        title="Toggle Sidebar" type="button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- <div class="ml-4 font-semibold text-xl text-gray-800 dark:text-gray-200">
                        {{ app_name() }}
                    </div> --}}

                    <!-- User Dropdown -->
                    <div class="ml-auto flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <div class="relative" x-data="notificationSystem">
                            <script>
                                function notificationSystem() {
                                    return {
                                        showNotifications: false,
                                        notifications: [],
                                        unreadCount: 0,
                                        async init() {
                                            await this.fetchNotifications();
                                            setInterval(() => this.fetchNotifications(), 30000);
                                        },
                                        async fetchNotifications() {
                                            try {
                                                const response = await fetch('/api/admin/notifications/pending');
                                                const data = await response.json();
                                                this.notifications = data;
                                                this.unreadCount = data.length;
                                            } catch (error) {
                                                console.error('Error fetching notifications:', error);
                                            }
                                        },
                                        async markAsRead(id) {
                                            try {
                                                await fetch(`/api/admin/notifications/${id}/read`, {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                                    }
                                                });
                                                await this.fetchNotifications();
                                            } catch (error) {
                                                console.error('Error marking notification as read:', error);
                                            }
                                        },
                                        toggleNotifications() {
                                            this.showNotifications = !this.showNotifications;
                                        }
                                    }
                                }
                            </script>
                            <button type="button" @click="toggleNotifications"
                                class="relative text-gray-600 hover:text-pink-500 hover:bg-pink-50 focus:outline-none focus:ring-2 focus:ring-pink-500 rounded-lg p-2 transition-all duration-200 flex items-center"
                                title="Notifikasi">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount"
                                    class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                </span>
                            </button>

                            <!-- Notifications Dropdown Panel -->
                            <div x-show="showNotifications" x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="fixed right-4 sm:right-auto sm:absolute mt-2 w-[calc(100vw-2rem)] sm:w-96 max-w-lg bg-white rounded-lg shadow-xl overflow-hidden z-[100] border border-gray-200"
                                style="max-height: calc(100vh - 100px); top: 100%; left: 50%; transform: translateX(-50%); @media (min-width: 640px) { left: auto; transform: none; }">
                                <!-- Panel Header -->
                                <div class="sticky top-0 px-4 py-3 border-b border-gray-100 bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                                        <button x-show="unreadCount > 0"
                                            @click="notifications.forEach(n => markAsRead(n.id))"
                                            class="text-sm text-pink-600 hover:text-pink-800">
                                            Tandai Semua Dibaca
                                        </button>
                                    </div>
                                </div>

                                <!-- Notifications List -->
                                <div class="overflow-y-auto max-h-[calc(100vh-200px)] overscroll-contain">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-6 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="mt-2">Tidak ada notifikasi baru</p>
                                        </div>
                                    </template>

                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                            <div class="flex items-start gap-3">
                                                <!-- Icon -->
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                                                    <span class="text-xl">🔔</span>
                                                </div>
                                                <!-- Content -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex justify-between items-start">
                                                        <p class="text-sm font-medium text-gray-900"
                                                            x-text="notification.title || 'Notifikasi Baru'"></p>
                                                        <p class="text-xs text-gray-400 ml-2" x-text="notification.created_at ? new Date(notification.created_at).toLocaleString('id-ID', {
                                                                hour: '2-digit',
                                                                minute: '2-digit',
                                                                day: '2-digit',
                                                                month: 'short'
                                                            }) : ''">
                                                        </p>
                                                    </div>
                                                    <p class="text-sm text-gray-500 line-clamp-2 mt-1"
                                                        x-text="notification.message || (notification.data && typeof notification.data === 'object' ? 
                                                            (notification.data.message || JSON.stringify(notification.data)) : 
                                                            (typeof notification.data === 'string' ? notification.data : 'Tidak ada detail'))"></p>
                                                    <div class="mt-2 flex justify-between items-center">
                                                        <a x-show="notification.url || (notification.data && notification.data.url)"
                                                            :href="notification.url || (notification.data && notification.data.url)"
                                                            class="text-sm text-pink-600 hover:text-pink-800">
                                                            Lihat Detail
                                                        </a>
                                                        <button @click.stop="markAsRead(notification.id)"
                                                            class="text-sm text-gray-500 hover:text-gray-700">
                                                            Tandai Dibaca
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center text-sm font-medium text-gray-700 hover:text-pink-500 hover:bg-pink-50 rounded-lg px-3 py-2 transition-all duration-200">

                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-pink-400 to-pink-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <div>{{ Auth::user()->name }}</div>
                                    </div>
                                    <div class="ml-2">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <!-- Page Heading -->
            @isset($header)
                <header
                    class="bg-white/80 backdrop-blur-sm shadow-sm rounded-xl mt-6 mb-6 mx-4 flex items-center px-6 py-4 border border-gray-200">
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-pink-400 to-pink-600 rounded-lg mr-4">
                        <i class="bi bi-flower2 text-lg text-white"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-xl font-bold text-gray-800 font-sans">{{ $header }}</div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Floating Menu Button for Mobile (appears when scrolled down) -->
        <div x-data="{ 
                showFab: false,
                init() {
                    // Show FAB when user scrolls down
                    window.addEventListener('scroll', () => {
                        this.showFab = window.scrollY > 200;
                    });
                }
            }" x-init="init()">
            <button x-show="showFab" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-16 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-16 opacity-0"
                @click="toggleSidebar()"
                class="fixed bottom-6 right-6 z-50 lg:hidden bg-pink-500 hover:bg-pink-600 text-white p-4 rounded-full shadow-lg focus:outline-none focus:ring-4 focus:ring-pink-300"
                title="Menu" style="display: none;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- SweetAlert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @livewireScripts
        <script>
            // SweetAlert delete confirmation
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.delete-confirm').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const form = this.closest('form');

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });

                // Toast notifications
                @if(session('success'))
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: '{{ session('success') }}'
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'error',
                        title: '{{ session('error') }}'
                    });
                @endif
            });
        </script>

        <!-- Debug script untuk sidebar -->
        <script src="{{ asset('js/sidebar-debug.js') }}"></script>

        <!-- Fallback script untuk sidebar -->
        <script src="{{ asset('js/sidebar-fallback.js') }}"></script>

        <!-- Push Notifications Component (hanya untuk authenticated users) -->
        @auth
            @include('components.push-notifications')
        @endauth

        <!-- Notification Bell Script -->
        <script>
            // Update notification badge
            function updateNotificationBadge(count) {
                const badge = document.getElementById('notification-badge');
                const bell = document.getElementById('notification-bell');

                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.classList.remove('hidden');
                    // Add visual indicator
                    bell.classList.add('animate-pulse');
                } else {
                    badge.classList.add('hidden');
                    bell.classList.remove('animate-pulse');
                }
            }

            // Poll untuk notification count
            function checkNotifications() {
                fetch('/api/admin/notifications/pending')
                    .then(response => response.json())
                    .then(notifications => {
                        updateNotificationBadge(notifications.length);

                        // Only update badge count, don't show notifications here
                        // Notifications are handled by the push notification manager
                        // to prevent duplicates
                    })
                    .catch(error => {
                        console.log('Error checking notifications:', error);
                    });
            }

            // Check notifications setiap 10 detik
            document.addEventListener('DOMContentLoaded', function () {
                // Request notification permission if not granted
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission().then(function (permission) {
                        console.log('Notification permission:', permission);
                    });
                }

                checkNotifications(); // Check immediately
                setInterval(checkNotifications, 10000); // Then every 10 seconds
            });

            // Bell click handler
            document.addEventListener('DOMContentLoaded', function () {
                const bell = document.getElementById('notification-bell');
                if (bell) {
                    bell.addEventListener('click', function () {
                        // Navigate ke halaman orders
                        window.location.href = '{{ route("admin.public-orders.index") }}';
                    });
                }
            });
        </script>

        @stack('scripts')
</body>

</html>