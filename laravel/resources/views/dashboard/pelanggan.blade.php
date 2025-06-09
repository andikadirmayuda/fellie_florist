<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- New Order Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-2">Place New Order</div>
                        <p class="text-gray-600">Browse and order flowers</p>
                        <div class="mt-4">
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Shop Now
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order History Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-2">Order History</div>
                        <p class="text-gray-600">View your past orders</p>
                        <div class="mt-4">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View Orders →</a>
                        </div>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-900 text-xl mb-2">My Profile</div>
                        <p class="text-gray-600">Update your profile and preferences</p>
                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-900">Edit Profile →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Products -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Featured Products</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="h-48 bg-gray-200 rounded-lg mb-4"></div>
                        <h4 class="font-semibold">Rose Bouquet</h4>
                        <p class="text-gray-600 text-sm">Beautiful arrangement of fresh roses</p>
                        <p class="text-blue-600 font-semibold mt-2">Rp 250.000</p>
                    </div>
                    <!-- More product cards will be added dynamically -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
