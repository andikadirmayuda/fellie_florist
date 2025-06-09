<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Users Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-2">User Management</div>
                        <p class="text-gray-600">Manage system users and their roles</p>
                        <div class="mt-4">
                            <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-900">Manage Users →</a>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-2">Product Management</div>
                        <p class="text-gray-600">Manage flower products and inventory</p>
                        <div class="mt-4">
                            <a href="#" class="text-blue-600 hover:text-blue-900">Manage Products →</a>
                        </div>
                    </div>
                </div>

                <!-- Sales Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-2">Sales Reports</div>
                        <p class="text-gray-600">View daily sales and reports</p>
                        <div class="mt-4">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View Reports →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
