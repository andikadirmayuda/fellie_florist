<!-- Side Cart Panel (Keranjang Belanja) -->
<div id="sideCart"
    class="fixed right-0 top-0 h-full w-80 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
    <div class="h-full flex flex-col">
        <!-- Cart Header -->
        <div
            class="p-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-rose-50 to-pink-50">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="bi bi-bag mr-2"></i> Keranjang Belanja
            </h3>
            <button onclick="toggleCart()" class="text-gray-500 hover:text-gray-700">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4" id="cartItems">
            <!-- Cart Information Panel -->
            @include('public.partials.unified-cart-info')
            <!-- Cart items will be dynamically loaded here -->
        </div>
        <!-- Cart Footer -->
        <div class="border-t border-gray-200 p-4 bg-white">
            <div class="flex justify-between mb-4">
                <span class="font-semibold">Total:</span>
                <span class="font-bold text-rose-600" id="cartTotal">Rp 0</span>
            </div>
            <a href="{{ route('public.checkout') }}"
                class="block w-full bg-gradient-to-r from-rose-500 to-pink-500 text-white text-center py-3 rounded-xl font-semibold hover:from-rose-600 hover:to-pink-600 transition-all duration-200">
                Lanjut ke Checkout
            </a>
        </div>
    </div>
</div>