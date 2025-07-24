<!-- Modal Pilih Harga Produk -->
<div id="cartPriceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative">
        <button onclick="closeCartPriceModal()" class="absolute top-3 right-3 text-gray-400 hover:text-rose-500">
            <i class="bi bi-x-lg"></i>
        </button>
        <h3 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
            <i class="bi bi-tag mr-2"></i> Pilih Harga Produk
        </h3>
        <div id="modalPriceOptions">
            <!-- Daftar harga akan diisi via JS -->
        </div>
        <div class="mt-6 flex justify-end gap-2">
            <button onclick="closeCartPriceModal()"
                class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">Batal</button>
            <button id="modalAddToCartBtn"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold hover:from-rose-600 hover:to-pink-600"
                disabled>Tambah ke Keranjang</button>
        </div>
    </div>
</div>
<script>
    let selectedPriceId = null;

    function formatPrice(price) {
        return Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function openCartPriceModal(flowerId, prices) {
        const modal = document.getElementById('cartPriceModal');
        const optionsDiv = document.getElementById('modalPriceOptions');
        const addBtn = document.getElementById('modalAddToCartBtn');
        selectedPriceId = null;
        addBtn.disabled = true;
        // Render price options
        optionsDiv.innerHTML = prices.map(price => `
            <label class="flex items-center gap-3 mb-2 cursor-pointer">
                <input type="radio" name="priceOption" value="${price.type}" onchange="selectPriceOption('${price.type}')">
                <span class="font-semibold text-gray-700">${price.label}</span>
                <span class="ml-auto text-rose-600 font-bold">Rp ${formatPrice(price.price)}</span>
            </label>
        `).join('');
        // Show modal
        modal.classList.remove('hidden');
        // Set add to cart action
        addBtn.onclick = function () {
            if (selectedPriceId) addToCartWithPrice(flowerId, selectedPriceId);
        };
    }
    function closeCartPriceModal() {
        document.getElementById('cartPriceModal').classList.add('hidden');
    }
    function selectPriceOption(priceId) {
        selectedPriceId = priceId;
        document.getElementById('modalAddToCartBtn').disabled = false;
    }
    // Tidak perlu fungsi addToCartWithPrice lokal, gunakan yang global dari flowers.blade.php
</script>