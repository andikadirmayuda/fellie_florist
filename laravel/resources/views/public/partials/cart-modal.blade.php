<!-- Cart Modal -->
<div id="cartModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Tambah ke Keranjang</h3>
            <button onclick="closeCartModal()" class="text-gray-400 hover:text-gray-500">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="addToCartForm" action="{{ route('public.cart.add') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" id="cartProductId">

            <!-- Quantity Input -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Tangkai)</label>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="updateQuantity(-1)"
                        class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                        <i class="bi bi-dash"></i>
                    </button>
                    <input type="number" id="quantity" name="quantity" value="1" min="1"
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                        onchange="validateQuantity(this)">
                    <button type="button" onclick="updateQuantity(1)"
                        class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>

            <!-- Note Input -->
            <div>
                <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea id="note" name="note" rows="2"
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                    placeholder="Tambahkan catatan khusus..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeCartModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 rounded-md">
                    Tambah ke Keranjang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedFlower = null;

    function openCartModal(flowerId) {
        document.getElementById('cartProductId').value = flowerId;
        document.getElementById('cartModal').classList.remove('hidden');
    }

    function closeCartModal() {
        document.getElementById('cartModal').classList.add('hidden');
        document.getElementById('quantity').value = 1;
        document.getElementById('note').value = '';
    }

    function updateQuantity(change) {
        const input = document.getElementById('quantity');
        const newValue = parseInt(input.value) + change;
        if (newValue >= 1) {
            input.value = newValue;
        }
    }

    function validateQuantity(input) {
        if (input.value < 1) {
            input.value = 1;
        }
    }

    // Close modal when clicking outside
    document.getElementById('cartModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeCartModal();
        }
    });
</script>