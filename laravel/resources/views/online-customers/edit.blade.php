<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('online-customers.show', $customerData->wa_number) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="bi bi-pencil text-pink-500 mr-2"></i>
                {{ __('Edit Pelanggan Online') }} - {{ $customerData->customer_name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Customer Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Pelanggan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Nama:</span>
                    <span class="ml-2 font-medium">{{ $customerData->customer_name }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">WhatsApp:</span>
                    <span class="ml-2 font-medium">{{ $customerData->wa_number }}</span>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('online-customers.update', $customerData->wa_number) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Reseller Settings -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="bi bi-star text-yellow-500 mr-2"></i>
                        Pengaturan Reseller
                    </h4>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_reseller" 
                                   value="1" 
                                   {{ old('is_reseller', optional($customerData->customer)->is_reseller ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Tetapkan sebagai reseller</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label for="reseller_discount" class="block text-sm font-medium text-gray-700 mb-2">
                            Diskon Reseller (%)
                        </label>
                        <input type="number" 
                               name="reseller_discount" 
                               id="reseller_discount"
                               value="{{ old('reseller_discount', optional($customerData->customer)->reseller_discount ?? '') }}"
                               min="0" 
                               max="100" 
                               step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500"
                               placeholder="Contoh: 10.5">
                        @error('reseller_discount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Promo Settings -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="bi bi-gift text-red-500 mr-2"></i>
                        Pengaturan Promo
                    </h4>
                    
                    <div class="mb-4">
                        <label for="promo_discount" class="block text-sm font-medium text-gray-700 mb-2">
                            Diskon Promo (%)
                        </label>
                        <input type="number" 
                               name="promo_discount" 
                               id="promo_discount"
                               value="{{ old('promo_discount', optional($customerData->customer)->promo_discount ?? '') }}"
                               min="0" 
                               max="100" 
                               step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500"
                               placeholder="Contoh: 15">
                        @error('promo_discount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="bi bi-journal-text mr-1"></i>
                    Catatan
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500"
                          placeholder="Catatan khusus untuk pelanggan ini...">{{ old('notes', optional($customerData->customer)->notes ?? '') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 flex gap-3">
                <button type="submit" class="px-6 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    <i class="bi bi-check-circle mr-2"></i>
                    Simpan Perubahan
                </button>
                
                <a href="{{ route('online-customers.show', $customerData->wa_number) }}" 
                   class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    <i class="bi bi-x-circle mr-2"></i>
                    Batal
                </a>
            </div>
            
        </form>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <i class="bi bi-exclamation-triangle mr-2"></i>
        Terdapat kesalahan dalam form
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.fixed.bottom-4').remove();
        }, 5000);
    </script>
    @endif
</x-app-layout>
