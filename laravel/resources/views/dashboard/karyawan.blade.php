<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Orders Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-2">Current Orders</div>
                        <p class="text-gray-600">View and process customer orders</p>
                        <div class="mt-4">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View Orders →</a>
                        </div>
                    </div>
                </div>

                <!-- Inventory Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-2">Inventory Status</div>
                        <p class="text-gray-600">Check and update product inventory</p>
                        <div class="mt-4">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View Inventory →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="mt-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-4">Today's Tasks</div>
                        <div class="space-y-4">
                            <div class="p-4 border rounded-lg">
                                <h3 class="font-semibold">Prepare Flower Arrangements</h3>
                                <p class="text-gray-600 text-sm mt-1">Check and prepare today's flower arrangements</p>
                            </div>
                            <div class="p-4 border rounded-lg">
                                <h3 class="font-semibold">Update Inventory</h3>
                                <p class="text-gray-600 text-sm mt-1">Update the inventory for fresh flowers</p>
                            </div>
                            <div class="p-4 border rounded-lg">
                                <h3 class="font-semibold">Check Orders</h3>
                                <p class="text-gray-600 text-sm mt-1">Review and process pending orders</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
