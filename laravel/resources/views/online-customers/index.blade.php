<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="bi bi-people-fill text-pink-500 mr-2"></i>
            {{ __('Daftar Pelanggan Online') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('online-customers.index') }}" class="flex gap-3">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Cari nama pelanggan atau nomor WhatsApp..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                            </div>
                            <button type="submit"
                                class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                                <i class="bi bi-search mr-1"></i> Cari
                            </button>
                            @if($search)
                                <a href="{{ route('online-customers.index') }}"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                    <i class="bi bi-x-circle mr-1"></i> Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-blue-100">Total Pelanggan</p>
                                    <p class="text-2xl font-bold">{{ $onlineCustomers->total() }}</p>
                                </div>
                                <i class="bi bi-people text-3xl text-blue-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-green-100">Total Pesanan</p>
                                    <p class="text-2xl font-bold">{{ $onlineCustomers->sum('total_orders') }}</p>
                                </div>
                                <i class="bi bi-bag-check text-3xl text-green-200"></i>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-purple-100">Total Penjualan</p>
                                    <p class="text-2xl font-bold">Rp
                                        {{ number_format($onlineCustomers->sum('total_spent'), 0, ',', '.') }}
                                    </p>
                                </div>
                                <i class="bi bi-currency-dollar text-3xl text-purple-200"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Customers Table -->
                    @if($onlineCustomers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pelanggan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kontak
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Pesanan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Belanja
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Terakhir Pesan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($onlineCustomers as $customer)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                                            <i class="bi bi-person-fill text-pink-500"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $customer->customer_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Bergabung:
                                                            {{ \Carbon\Carbon::parse($customer->first_order_date)->format('d M Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <i class="bi bi-whatsapp text-green-500 mr-1"></i>
                                                    {{ $customer->wa_number }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col gap-1">
                                                    @if($customer->is_reseller)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="bi bi-star-fill mr-1"></i>
                                                            Reseller
                                                        </span>
                                                    @endif
                                                    @if($customer->promo_discount && $customer->promo_discount > 0)
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="bi bi-gift-fill mr-1"></i>
                                                            Promo {{ $customer->promo_discount }}%
                                                        </span>
                                                    @endif
                                                    @if(!$customer->is_reseller && (!$customer->promo_discount || $customer->promo_discount == 0))
                                                        <span class="text-xs text-gray-400">Regular</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $customer->total_orders }} pesanan
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($customer->total_spent, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($customer->last_order_date)->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('online-customers.show', $customer->wa_number) }}"
                                                        class="text-blue-600 hover:text-blue-900 transition"
                                                        title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('online-customers.edit', $customer->wa_number) }}"
                                                        class="text-green-600 hover:text-green-900 transition"
                                                        title="Edit Pelanggan">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    @if($customer->is_reseller)
                                                        <button type="button"
                                                            class="text-purple-600 hover:text-purple-900 transition"
                                                            title="Generate Kode Reseller"
                                                            onclick="openGenerateCodeModal('{{ $customer->wa_number }}', '{{ $customer->customer_name }}')">
                                                            <i class="bi bi-key"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $onlineCustomers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-people text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pelanggan online</h3>
                            <p class="text-gray-500">
                                @if($search)
                                    Tidak ditemukan pelanggan dengan kata kunci "{{ $search }}"
                                @else
                                    Belum ada pelanggan yang melakukan pemesanan online
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Reseller Code Modal -->
    <div id="generateCodeModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">
                        <i class="bi bi-key text-purple-500 mr-2"></i>
                        Generate Kode Reseller
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeGenerateCodeModal()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Pelanggan: <span id="customerName" class="font-medium"></span>
                    </p>
                    <p class="text-sm text-gray-600 mb-4">Nomor WA: <span id="customerWA" class="font-medium"></span>
                    </p>

                    <div class="space-y-3">
                        <!-- Checkbox Set as Reseller -->
                        <div class="flex items-center">
                            <input type="checkbox" id="setAsReseller"
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="setAsReseller" class="ml-2 block text-sm text-gray-700">
                                Set sebagai Reseller (jika belum terdaftar)
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Reseller</label>
                            <div class="flex">
                                <input type="text" id="resellerCode"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Masukkan kode reseller" maxlength="20">
                                <button type="button" onclick="generateRandomCode()"
                                    class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200 text-sm">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Kode harus unik dan maksimal 20 karakter</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Selama (Jam)</label>
                            <select id="expiryHours"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                <option value="24">24 Jam (1 Hari)</option>
                                <option value="48">48 Jam (2 Hari)</option>
                                <option value="72" selected>72 Jam (3 Hari)</option>
                                <option value="168">168 Jam (1 Minggu)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Waktu berlaku kode reseller</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                            <textarea id="notes" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Tambahkan catatan untuk kode reseller ini..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeGenerateCodeModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="button" onclick="generateAutoCode()"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <i class="bi bi-magic mr-1"></i>
                        Generate Otomatis
                    </button>
                    <button type="button" onclick="saveManualCode()"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                        <i class="bi bi-save mr-1"></i>
                        Simpan Kode
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCustomerWA = '';

        function openGenerateCodeModal(waNumber, customerName) {
            currentCustomerWA = waNumber;
            document.getElementById('customerName').textContent = customerName;
            document.getElementById('customerWA').textContent = waNumber;
            document.getElementById('generateCodeModal').classList.remove('hidden');

            // Generate random code by default
            generateRandomCode();

            // Set default values
            document.getElementById('expiryHours').value = '72';
            document.getElementById('notes').value = '';
        }

        function closeGenerateCodeModal() {
            document.getElementById('generateCodeModal').classList.add('hidden');
            // Reset form
            document.getElementById('resellerCode').value = '';
            document.getElementById('expiryHours').value = '72';
            document.getElementById('notes').value = '';
            currentCustomerWA = '';
        }

        function generateRandomCode() {
            const prefix = 'RES';
            const randomPart = Math.random().toString(36).substring(2, 8).toUpperCase();
            const code = prefix + randomPart;
            document.getElementById('resellerCode').value = code;
        }

        // Generate kode otomatis
        function generateAutoCode() {
            const expiryHours = document.getElementById('expiryHours').value;
            const notes = document.getElementById('notes').value.trim();
            const setAsReseller = document.getElementById('setAsReseller').checked;

            if (!expiryHours || expiryHours < 1 || expiryHours > 168) {
                alert('Jam berlaku harus antara 1-168 jam!');
                return;
            }

            // Show loading state
            const generateBtn = event.target;
            const originalText = generateBtn.innerHTML;
            generateBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-1"></i> Generating...';
            generateBtn.disabled = true;

            // Debug URL
            const url = `/online-customers/${currentCustomerWA}/generate-code`;
            console.log('Request URL:', url);
            console.log('Current customer WA:', currentCustomerWA);
            console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));

            // Send AJAX request tanpa kode (biar auto-generate)
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    expiry_hours: expiryHours,
                    notes: notes || null,
                    set_as_reseller: setAsReseller
                })
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers.get('content-type'));

                    // Cek apakah response adalah JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // Jika bukan JSON, ambil sebagai text untuk debugging
                        return response.text().then(text => {
                            console.log('Non-JSON response:', text.substring(0, 500));
                            throw new Error('Server returned HTML instead of JSON. Check server logs.');
                        });
                    }
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert('Kode reseller berhasil di-generate!\n\nKode: ' + data.code + '\nBerlaku hingga: ' + data.expires_at);
                        closeGenerateCodeModal();
                        // Refresh halaman untuk update data
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Gagal generate kode reseller'));
                    }
                })
                .catch(error => {
                    console.error('Full error:', error);
                    alert('Terjadi kesalahan saat generate kode reseller. Check console for details.');
                })
                .finally(() => {
                    // Reset button state
                    generateBtn.innerHTML = originalText;
                    generateBtn.disabled = false;
                });
        }

        // Simpan kode manual
        function saveManualCode() {
            const code = document.getElementById('resellerCode').value.trim();
            const expiryHours = document.getElementById('expiryHours').value;
            const notes = document.getElementById('notes').value.trim();
            const setAsReseller = document.getElementById('setAsReseller').checked;

            if (!code) {
                alert('Kode reseller tidak boleh kosong!');
                return;
            }

            if (!expiryHours || expiryHours < 1 || expiryHours > 168) {
                alert('Jam berlaku harus antara 1-168 jam!');
                return;
            }

            // Show loading state
            const saveBtn = event.target;
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-1"></i> Menyimpan...';
            saveBtn.disabled = true;

            // Debug URL
            const url = `/online-customers/${currentCustomerWA}/generate-code`;
            console.log('Request URL:', url);
            console.log('Current customer WA:', currentCustomerWA);
            console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));

            // Send AJAX request dengan kode manual
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    code: code,
                    expiry_hours: expiryHours,
                    notes: notes || null,
                    set_as_reseller: setAsReseller
                })
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers.get('content-type'));

                    // Cek apakah response adalah JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // Jika bukan JSON, ambil sebagai text untuk debugging
                        return response.text().then(text => {
                            console.log('Non-JSON response:', text.substring(0, 500));
                            throw new Error('Server returned HTML instead of JSON. Check server logs.');
                        });
                    }
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert('Kode reseller berhasil disimpan!\n\nKode: ' + data.code + '\nBerlaku hingga: ' + data.expires_at);
                        closeGenerateCodeModal();
                        // Refresh halaman untuk update data
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Gagal menyimpan kode reseller'));
                    }
                })
                .catch(error => {
                    console.error('Full error:', error);
                    alert('Terjadi kesalahan saat menyimpan kode reseller. Check console for details: ' + error.message);
                })
                .finally(() => {
                    // Reset button state
                    saveBtn.innerHTML = originalText;
                    saveBtn.disabled = false;
                });
        }

        // Close modal when clicking outside
        document.getElementById('generateCodeModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeGenerateCodeModal();
            }
        });
    </script>
</x-app-layout>