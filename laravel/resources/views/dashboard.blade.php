<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Owner & Admin Section -->
                        @if ($user->hasRole('owner') || $user->hasRole('admin'))
                        <div class="p-4 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">User Management</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Manage system users and their roles</p>
                            <a href="{{ route('users.index') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Manage Users
                            </a>
                        </div>
                        @endif

                        <!-- Products Section -->
                        <div class="p-4 bg-green-100 dark:bg-green-900 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Products</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Browse and manage flower products</p>
                            <a href="#" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                View Products
                            </a>
                        </div>

                        <!-- Orders Section -->
                        <div class="p-4 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Orders</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">View and manage flower orders</p>
                            <a href="#" class="inline-block bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                                Manage Orders
                            </a>
                        </div>

                        <!-- Reports Section - Only for Owner, Admin, and Kasir -->
                        @if ($user->hasAnyRole(['owner', 'admin', 'kasir']))
                        <div class="p-4 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Reports</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">View sales and inventory reports</p>
                            <a href="#" class="inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                                View Reports
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
