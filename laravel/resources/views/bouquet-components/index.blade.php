<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-800 leading-tight mb-2">
                    {{ __('Komponen Bouquet') }}
                </h2>
                {{-- <Bo class="text-gray-600">Datfar Bouqet</Bo> --}}
            </div>
            <a href="{{ route('bouquet-components.create') }}"
                class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Komponen
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        <div class="group bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                            icon: 'success',
                            title: 'Berhasil',
                            text: @json(session('success')),
                            confirmButtonColor: '#d946ef',
                                                            });
                                                        });
                </script>
            @endif

            {{-- Data sudah diproses di controller --}}

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Category Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6 py-4" aria-label="Tabs">
                        @foreach($categories as $index => $category)
                            <button onclick="showCategory('{{ $category }}')"
                                class="category-tab {{ $index === 0 ? 'active' : '' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                                data-category="{{ $category }}">
                                {{ $category }}
                            </button>
                        @endforeach
                    </nav>
                    @foreach($categories as $category)
                        <div class="category-content" data-category="{{ $category }}">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 px-4 pb-8">
                                @forelse($bouquetGroups as $bouquetId => $group)
                                    @php
                                        $bouquet = $group['bouquet'];
                                        $bouquetCategory = $bouquet->category->name ?? 'Uncategorized';
                                    @endphp
                                    @if($category === 'All' || $category === $bouquetCategory)
                                        <div
                                            class="group bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                                            <div class="grid md:grid-cols-2 gap-0">
                                                <!-- Image Section -->

                                                <div
                                                    class="relative bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center aspect-[4/5] w-full max-h-80 min-h-[320px] overflow-hidden">
                                                    @if($group['bouquet']->image)
                                                        <img src="{{ asset('storage/' . $group['bouquet']->image) }}"
                                                            alt="{{ $group['bouquet']->name }}"
                                                            class="w-full h-full object-cover object-center aspect-[4/5] max-h-80 rounded-none transition-transform duration-300 ease-in-out group-hover:scale-105">
                                                    @else
                                                        <div
                                                            class="text-center w-full h-full flex flex-col items-center justify-center aspect-[4/5] max-h-80">
                                                            <svg class="w-16 h-16 text-pink-300 mx-auto mb-2" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                                                            </svg>
                                                            <p class="text-pink-400 text-sm">{{ $group['bouquet']->name }}</p>
                                                        </div>
                                                    @endif


                                                    <!-- Category Badge -->
                                                    <div class="absolute top-4 left-4">
                                                        <span
                                                            class="bg-white/90 backdrop-blur-sm text-pink-700 px-3 py-1 rounded-full text-xs font-medium shadow-sm">
                                                            {{ $bouquetCategory }}
                                                        </span>
                                                    </div>

                                                    <!-- Favorite Button -->
                                                    <button
                                                        class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm hover:bg-white p-2 rounded-full shadow-sm transition-colors">
                                                        <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="p-6">
                                                    <!-- Header -->
                                                    <div class="mb-4">
                                                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                            {{ $group['bouquet']->name }}
                                                        </h3>
                                                        <div class="flex items-center gap-2">
                                                            <div class="flex items-center">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"
                                                                        viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                    </svg>
                                                                @endfor
                                                                <span class="text-sm font-medium ml-1 text-gray-600">4.8</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Size Tabs -->
                                                    <div class="mb-4">
                                                        <div class="flex flex-wrap gap-1 mb-3">
                                                            @foreach(array_values($group['sizes']) as $sizeIndex => $sizeGroup)
                                                                <button
                                                                    onclick="showSize('{{ $group['bouquet']->id }}', '{{ $sizeGroup['size']->id }}')"
                                                                    class="size-tab size-tab-{{ $group['bouquet']->id }} {{ $sizeIndex === 0 ? 'active' : '' }} px-3 py-1 rounded-md text-xs font-medium transition-colors duration-200"
                                                                    data-bouquet="{{ $group['bouquet']->id }}"
                                                                    data-size="{{ $sizeGroup['size']->id }}">
                                                                    {{ $sizeGroup['size']->name }}
                                                                </button>
                                                            @endforeach
                                                        </div>

                                                        <!-- Size Content -->
                                                        @foreach(array_values($group['sizes']) as $sizeIndex => $sizeGroup)
                                                            <div class="size-content size-content-{{ $group['bouquet']->id }} {{ $sizeIndex !== 0 ? 'hidden' : '' }}"
                                                                data-bouquet="{{ $group['bouquet']->id }}"
                                                                data-size="{{ $sizeGroup['size']->id }}">

                                                                <!-- Price -->
                                                                @if(isset($sizeGroup['size']->price))
                                                                    <div class="text-2xl font-bold text-pink-600 mb-3">
                                                                        Rp {{ number_format($sizeGroup['size']->price, 0, ',', '.') }}
                                                                    </div>
                                                                @endif

                                                                <!-- Components -->
                                                                <div class="mb-4">
                                                                    <h4 class="font-semibold text-sm text-gray-700 mb-2">Komponen Bunga:
                                                                    </h4>
                                                                    <div class="space-y-2">
                                                                        @foreach($sizeGroup['components'] as $comp)
                                                                            @if($comp->product) {{-- Only show components with valid
                                                                                products --}}
                                                                                <div
                                                                                    class="flex justify-between items-center py-1 border-b border-gray-100 last:border-b-0">
                                                                                    <span
                                                                                        class="text-sm text-gray-800">{{ $comp->product->name }}</span>
                                                                                    <div class="flex items-center gap-3">
                                                                                        <span
                                                                                            class="text-sm text-gray-500">{{ $comp->quantity }}
                                                                                            {{ $comp->product->base_unit ?? 'pcs' }}</span>
                                                                                        <div class="flex gap-1">
                                                                                            <a href="{{ route('bouquet-components.show', $comp->id) }}"
                                                                                                title="Lihat Detail"
                                                                                                class="p-1 rounded hover:bg-blue-100 text-blue-600 transition-colors">
                                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                    class="h-4 w-4" fill="none"
                                                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                                                    <path stroke-linecap="round"
                                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                                </svg>
                                                                                            </a>
                                                                                            <a href="{{ route('bouquet-components.edit', $comp->id) }}"
                                                                                                title="Edit"
                                                                                                class="p-1 rounded hover:bg-indigo-100 text-indigo-600 transition-colors">
                                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                    class="h-4 w-4" fill="none"
                                                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                                                    <path stroke-linecap="round"
                                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                                        d="M11 5h2m-1 0v14m-7-7h14" />
                                                                                                </svg>
                                                                                            </a>
                                                                                            <form
                                                                                                action="{{ route('bouquet-components.destroy', $comp->id) }}"
                                                                                                method="POST" class="inline">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit" title="Hapus"
                                                                                                    class="p-1 rounded hover:bg-red-100 text-red-600 transition-colors"
                                                                                                    onclick="return confirm('Hapus komponen ini?')">
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                        class="h-4 w-4" fill="none"
                                                                                                        viewBox="0 0 24 24"
                                                                                                        stroke="currentColor">
                                                                                                        <path stroke-linecap="round"
                                                                                                            stroke-linejoin="round"
                                                                                                            stroke-width="2"
                                                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                                                    </svg>
                                                                                                </button>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif {{-- End product check --}}
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                <!-- Action Button -->
                                                                <a href="{{ route('bouquet-components.manage', ['bouquet' => $group['bouquet']->id, 'size' => $sizeGroup['size']->id]) }}"
                                                                    class="w-full bg-pink-600 hover:bg-pink-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0L4 5M7 13h10m0 0v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6z" />
                                                                    </svg>
                                                                    Kelola Komponen
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="col-span-2 text-center py-12">
                                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3" />
                                        </svg>
                                        <p class="text-gray-500 text-lg">Tidak ada komponen bouquet ditemukan.</p>
                                        <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan komponen bouquet pertama
                                            Anda.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination removed: all components are now displayed on one page. -->
            </div>
        </div>
    </div>

    <!-- JavaScript for Tabs -->
    <script>
                            // Category Tab Functionality
                            function showCategory(category) {
                                // Hide all category contents
                                document.querySelectorAll('.category-content').forEach(content => {
                                    content.classList.add('hidden');
                                });
                            // Show selected category content
                            document.querySelectorAll(`[data-category="${category}"]`).forEach(content => {
                if (content.classList.contains('category-content')) {
                                content.classList.remove('hidden');
                    // Untuk setiap bouquet yang tampil, aktifkan size tab & content pertama
                    content.querySelectorAll('.size-tab-').forEach(tab => {
                                // Reset semua size tab
                                tab.classList.remove('active', 'bg-pink-600', 'text-white');
                            tab.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                    });
                    content.querySelectorAll('.size-content-').forEach(sc => {
                                sc.classList.add('hidden');
                    });
                            // Untuk setiap bouquet, aktifkan size tab & content pertama
                            content.querySelectorAll('[class*="size-tab-"]').forEach(function(tabGroup) {
                        var bouquetId = tabGroup.getAttribute('data-bouquet');
                            var firstTab = content.querySelector('.size-tab-' + bouquetId);
                            var firstContent = content.querySelector('.size-content-' + bouquetId);
                            if(firstTab) {
                                firstTab.classList.add('active', 'bg-pink-600', 'text-white');
                            firstTab.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                        }
                            if(firstContent) {
                                firstContent.classList.remove('hidden');
                        }
                    });
                }
            });
            // Update tab styles
            document.querySelectorAll('.category-tab').forEach(tab => {
                                tab.classList.remove('active', 'border-pink-500', 'text-pink-600');
                            tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
                            document.querySelector(`[data-category="${category}"].category-tab`).classList.add('active', 'border-pink-500', 'text-pink-600');
                            document.querySelector(`[data-category="${category}"].category-tab`).classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        }

                            // Size Tab Functionality
                            function showSize(bouquetId, sizeId) {
            // Cari kategori yang sedang aktif (tidak hidden)
            var activeCategory = document.querySelector('.category-content:not(.hidden)');
                            if (!activeCategory) return;
                            // Hide all size contents for this bouquet in active category
                            activeCategory.querySelectorAll(`.size-content-${bouquetId}`).forEach(content => {
                                content.classList.add('hidden');
            });
                            // Show selected size content in active category
                            var showContent = activeCategory.querySelector(`[data-bouquet="${bouquetId}"][data-size="${sizeId}"].size-content`);
                            if (showContent) showContent.classList.remove('hidden');
                            // Update size tab styles in active category
                            activeCategory.querySelectorAll(`.size-tab-${bouquetId}`).forEach(tab => {
                                tab.classList.remove('active', 'bg-pink-600', 'text-white');
                            tab.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            });
                            var showTab = activeCategory.querySelector(`[data-bouquet="${bouquetId}"][data-size="${sizeId}"].size-tab`);
                            if (showTab) {
                                showTab.classList.add('active', 'bg-pink-600', 'text-white');
                            showTab.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            }
        }

                            // Initialize tabs on page load
                            document.addEventListener('DOMContentLoaded', function() {
                                // Set initial category tab style
                                document.querySelector('.category-tab.active').classList.add('border-pink-500', 'text-pink-600');
                            document.querySelector('.category-tab.active').classList.remove('border-transparent', 'text-gray-500');

            // Set initial size tab styles
            document.querySelectorAll('.size-tab.active').forEach(tab => {
                                tab.classList.add('bg-pink-600', 'text-white');
                            tab.classList.remove('bg-gray-100', 'text-gray-700');
            });

            // Set non-active size tab styles
            document.querySelectorAll('.size-tab:not(.active)').forEach(tab => {
                                tab.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            });

            // Set non-active category tab styles
            document.querySelectorAll('.category-tab:not(.active)').forEach(tab => {
                                tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
        });
    </script>

    <style>
        .category-tab.active {
            border-color: #ec4899;
            color: #ec4899;
        }

        .size-tab.active {
            background-color: #ec4899;
            color: white;
        }

        .size-tab:not(.active) {
            background-color: #f3f4f6;
            color: #374151;
        }

        .size-tab:not(.active):hover {
            background-color: #e5e7eb;
        }
    </style>
</x-app-layout>