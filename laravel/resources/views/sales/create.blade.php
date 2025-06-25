<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Transaksi Penjualan
        </h2>
    </x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 dark:bg-red-900 dark:text-red-200">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('sales.store') }}" method="POST" id="saleForm" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">No. Penjualan (Otomatis)</label>
                    <input type="text" class="form-input w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" value="(Akan otomatis)" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Waktu Pemesanan</label>
                    <input type="text" class="form-input w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" value="{{ now() }}" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Kategori Produk</label>
                    <select class="form-select w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" id="categorySelect">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Produk</label>
                    <select class="form-select w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" id="productSelect">
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-category="{{ $product->category_id }}" @if($product->current_stock == 0) disabled @endif>
                                {{ $product->name }}
                                @if($product->current_stock == 0)
                                    (Stok Habis)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Tipe Harga</label>
                    <select class="form-select w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" id="priceTypeSelect">
                        <option value="">Pilih Tipe Harga</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Jumlah</label>
                    <input type="number" class="form-input w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" id="quantityInput" min="1" value="1">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Cari Produk dengan Kode</label>
                    <input type="text" id="searchByCodeInput" class="form-input w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" placeholder="Scan/masukkan kode produk...">
                </div>
            </div>
            <div id="searchByCodeResult" class="my-2"></div>
            <div>
                <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition mt-2" id="addItemBtn">Tambah Produk</button>
            </div>
            <hr class="my-4 border-gray-300 dark:border-gray-700">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Daftar Produk</h4>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800" id="itemsTable">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Tipe Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Subtotal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Daftar produk akan diisi oleh JS -->
                    </tbody>
                </table>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Subtotal</label>
                    <input type="text" class="form-input w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" id="subtotalInput" name="subtotal" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Total</label>
                    <input type="text" class="form-input w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" id="totalInput" name="total" readonly>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Metode Pembayaran</label>
                <select class="form-select w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" name="payment_method" required>
                    <option value="pilih">Pilih Metode Pembayaran</option>
                    <option value="cash">Cash</option>
                    <option value="debit">Debit</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>
            <div id="cashSection" class="mt-4" style="display:none;">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Bayar (Cash)</label>
                <input type="number" min="0" step="100" id="cashGivenInput" class="form-input w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100" placeholder="Masukkan nominal uang cash...">
                <div class="mt-2 text-sm">
                    <span>Kembalian: </span>
                    <span id="cashChange" class="font-bold text-green-600">Rp 0</span>
                </div>
            </div>
            <input type="hidden" name="items" id="itemsInput">
            <input type="hidden" name="cash_given" id="hiddenCashGivenInput">
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded transition">Simpan</button>
            </div>
        </form>
    </div>
<script>
    let items = [];
    const products = @json($products);
    // Buat mapping harga per produk
    const productPrices = {};
    products.forEach(p => {
        productPrices[p.id] = p.prices;
    });

    document.getElementById('productSelect').onchange = function() {
        let productId = this.value;
        let priceTypeSelect = document.getElementById('priceTypeSelect');
        priceTypeSelect.innerHTML = '<option value="">Pilih Tipe Harga</option>';
        if (productPrices[productId]) {
            productPrices[productId].forEach(price => {
                priceTypeSelect.innerHTML += `<option value="${price.type}" data-price="${price.price}">${price.type.replaceAll('_',' ').toUpperCase()} (Rp ${parseFloat(price.price).toLocaleString()})</option>`;
            });
        }
    };

    function updateCashChange() {
        const total = parseInt(totalInput.value.replace(/[^0-9]/g, '')) || 0;
        const given = parseInt(cashGivenInput.value) || 0;
        let change = given - total;
        cashChange.textContent = formatRupiah(change >= 0 ? change : 0);
        document.getElementById('hiddenCashGivenInput').value = given;
        if (given < total) {
            cashChange.classList.remove('text-green-600');
            cashChange.classList.add('text-red-600');
        } else {
            cashChange.classList.remove('text-red-600');
            cashChange.classList.add('text-green-600');
        }
    }

    function updateTable() {
        let tbody = document.querySelector('#itemsTable tbody');
        tbody.innerHTML = '';
        let subtotal = 0;
        if (items.length === 0) {
            tbody.innerHTML = `<tr><td colspan='6' class='px-6 py-6 text-center text-gray-400 dark:text-gray-500'>Belum ada produk ditambahkan.</td></tr>`;
        } else {
            items.forEach((item, idx) => {
                subtotal += item.price * item.quantity;
                tbody.innerHTML += `
                    <tr>
                        <td class='px-6 py-3 text-gray-900 dark:text-gray-100'>${item.product_name}</td>
                        <td class='px-6 py-3 text-gray-900 dark:text-gray-100'>${item.price_type.replaceAll('_',' ').toUpperCase()}</td>
                        <td class='px-6 py-3 text-gray-900 dark:text-gray-100'>${item.price.toLocaleString()}</td>
                        <td class='px-6 py-3 text-gray-900 dark:text-gray-100'>${item.quantity}</td>
                        <td class='px-6 py-3 text-gray-900 dark:text-gray-100'>${(item.price * item.quantity).toLocaleString()}</td>
                        <td class='px-6 py-3 text-center'><button type="button" class="text-red-600 hover:underline font-medium" onclick="removeItem(${idx})">Hapus</button></td>
                    </tr>
                `;
            });
        }
        document.getElementById('subtotalInput').value = subtotal.toLocaleString();
        document.getElementById('totalInput').value = subtotal.toLocaleString();
        document.getElementById('itemsInput').value = JSON.stringify(items);
        updateCashChange(); // <-- update kembalian setiap kali tabel diupdate
    }
    function removeItem(idx) {
        items.splice(idx, 1);
        updateTable();
    }
    document.getElementById('addItemBtn').onclick = function() {
        let productId = document.getElementById('productSelect').value;
        let priceType = document.getElementById('priceTypeSelect').value;
        let priceTypeOption = document.querySelector(`#priceTypeSelect option[value='${priceType}']`);
        let quantity = parseInt(document.getElementById('quantityInput').value);
        if (!productId || !priceType || quantity < 1) return alert('Lengkapi data produk!');
        let product = products.find(p => p.id == productId);
        let price = priceTypeOption ? parseFloat(priceTypeOption.getAttribute('data-price')) : 0;
        // Cek stok produk sebelum menambah
        let priceObj = (product.prices || []).find(pr => pr.type === priceType);
        let unitEquivalent = priceObj && priceObj.unit_equivalent ? parseInt(priceObj.unit_equivalent) : 1;
        let stokTersedia = product.current_stock;
        let totalButuh = quantity * unitEquivalent;
        if (stokTersedia < totalButuh) {
            alert('Stok produk tidak mencukupi! (Stok tersedia: ' + stokTersedia + ', dibutuhkan: ' + totalButuh + ')');
            return;
        }
        items.push({
            product_id: product.id,
            product_name: product.name,
            price_type: priceType,
            quantity: quantity,
            price: price
        });
        updateTable();
    };
    document.getElementById('categorySelect').onchange = function() {
        let catId = this.value;
        let productSelect = document.getElementById('productSelect');
        Array.from(productSelect.options).forEach(opt => {
            if (!opt.value) return;
            opt.style.display = opt.getAttribute('data-category') == catId ? '' : 'none';
        });
        productSelect.value = '';
    };
    document.getElementById('saleForm').onsubmit = function() {
        if (items.length === 0) {
            alert('Tambahkan minimal 1 produk!');
            return false;
        }
        const paymentSelect = document.querySelector('select[name="payment_method"]');
        if (paymentSelect.value === 'cash') {
            const total = parseInt(document.getElementById('totalInput').value.replace(/[^0-9]/g, '')) || 0;
            const given = parseInt(document.getElementById('cashGivenInput').value) || 0;
            if (given < total) {
                alert('Uang yang diberikan kurang dari total belanja!');
                document.getElementById('cashGivenInput').focus();
                return false;
            }
        }
        document.getElementById('itemsInput').value = JSON.stringify(items);
        return true;
    };
    // Fitur pencarian produk berdasarkan kode
    document.getElementById('searchByCodeInput').addEventListener('change', function() {
        const code = this.value.trim();
        const resultDiv = document.getElementById('searchByCodeResult');
        if (!code) {
            resultDiv.innerHTML = '';
            return;
        }
        const product = products.find(p => p.code == code);
        if (!product) {
            resultDiv.innerHTML = '<div class="text-red-600">Produk dengan kode tersebut tidak ditemukan.</div>';
            return;
        }
        // Tampilkan form pilih tipe harga dan jumlah
        let priceOptions = '';
        (product.prices || []).forEach(price => {
            priceOptions += `<option value="${price.type}" data-price="${price.price}">${price.type.replaceAll('_',' ').toUpperCase()} (Rp ${parseFloat(price.price).toLocaleString()})</option>`;
        });
        resultDiv.innerHTML = `
            <div class='p-3 bg-gray-100 dark:bg-gray-700 rounded mb-2'>
                <div class='font-semibold mb-1'>${product.name}</div>
                <div class='mb-2'>Stok: ${product.current_stock} ${product.base_unit}</div>
                <div class='mb-2'>
                    <label class='block text-sm mb-1'>Tipe Harga</label>
                    <select id='codePriceTypeSelect' class='form-select w-full rounded-md border-gray-300'>
                        <option value=''>Pilih Tipe Harga</option>
                        ${priceOptions}
                    </select>
                </div>
                <div class='mb-2'>
                    <label class='block text-sm mb-1'>Jumlah</label>
                    <input type='number' id='codeQuantityInput' class='form-input w-full rounded-md border-gray-300' min='1' value='1'>
                </div>
                <button type='button' class='bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded' id='addByCodeBtn'>Tambah Produk</button>
            </div>
        `;
        document.getElementById('addByCodeBtn').onclick = function(e) {
            e.preventDefault();
            const priceType = document.getElementById('codePriceTypeSelect').value;
            const priceTypeOption = document.querySelector(`#codePriceTypeSelect option[value='${priceType}']`);
            const quantity = parseInt(document.getElementById('codeQuantityInput').value);
            if (!priceType || quantity < 1) return alert('Lengkapi tipe harga dan jumlah!');
            let priceObj = (product.prices || []).find(pr => pr.type === priceType);
            let unitEquivalent = priceObj && priceObj.unit_equivalent ? parseInt(priceObj.unit_equivalent) : 1;
            let stokTersedia = product.current_stock;
            let totalButuh = quantity * unitEquivalent;
            if (stokTersedia < totalButuh) {
                alert('Stok produk tidak mencukupi! (Stok tersedia: ' + stokTersedia + ', dibutuhkan: ' + totalButuh + ')');
                return;
            }
            let price = priceTypeOption ? parseFloat(priceTypeOption.getAttribute('data-price')) : 0;
            items.push({
                product_id: product.id,
                product_name: product.name,
                price_type: priceType,
                quantity: quantity,
                price: price
            });
            updateTable();
            resultDiv.innerHTML = '';
            document.getElementById('searchByCodeInput').value = '';
        };
    });
    document.getElementById('searchByCodeInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            return false;
        }
    });
    const paymentSelect = document.querySelector('select[name="payment_method"]');
    const cashSection = document.getElementById('cashSection');
    const cashGivenInput = document.getElementById('cashGivenInput');
    const cashChange = document.getElementById('cashChange');
    const totalInput = document.getElementById('totalInput');

    paymentSelect.addEventListener('change', function() {
        if (this.value === 'cash') {
            cashSection.style.display = '';
        } else {
            cashSection.style.display = 'none';
            cashGivenInput.value = '';
            cashChange.textContent = 'Rp 0';
        }
    });

    function formatRupiah(num) {
        return 'Rp ' + (parseInt(num)||0).toLocaleString('id-ID');
    }

    cashGivenInput && cashGivenInput.addEventListener('input', updateCashChange);
</script>
</x-app-layout>
