<x-layouts.admin title="Products" subtitle="Manage your menu items">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-brown-400 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-brown-600 transition-colors">Dashboard</a>
        <span class="text-brown-200">/</span>
        <span class="text-brown-600">Products</span>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Sticky toolbar: summary + actions + search + filter --}}
    <div class="sticky top-[72px] z-30 -mx-8 px-8 py-3 mb-5 bg-brown-50/85 backdrop-blur-md border-b border-brown-100">
        <div class="flex items-center gap-3 flex-wrap">

            {{-- Product count --}}
            <div class="flex items-center gap-2 bg-cream border border-brown-100 rounded-xl px-4 py-2 shadow-sm">
                <div class="w-2 h-2 rounded-full bg-crema"></div>
                <span class="text-sm text-brown-600">
                    <span class="font-medium text-espresso">{{ $products->total() }}</span>
                    {{ Str::plural('product', $products->total()) }}
                </span>
            </div>

            {{-- Search --}}
            <div class="relative flex-1 min-w-[200px] max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-brown-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
                </svg>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search products…"
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-brown-200 text-sm text-espresso placeholder-brown-300 bg-white focus:outline-none focus:border-crema focus:ring-2 focus:ring-crema/20 transition-all duration-150">
            </div>

            {{-- Category filter --}}
            <select id="categoryFilter"
                class="px-3 py-2 rounded-xl border border-brown-200 text-sm text-brown-600 bg-white focus:outline-none focus:border-crema focus:ring-2 focus:ring-crema/20 transition-all duration-150">
                <option value="">All categories</option>
                @foreach($products->pluck('category.name')->filter()->unique()->sort() as $catName)
                <option value="{{ $catName }}">{{ $catName }}</option>
                @endforeach
            </select>

            {{-- New product --}}
            <a href="{{ route('admin.products.create') }}"
                class="ml-auto flex items-center gap-2 px-5 py-2.5 rounded-xl bg-espresso hover:bg-roast text-cream text-sm font-medium transition-all duration-150 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Product
            </a>
        </div>
    </div>

    {{-- Table card --}}
    <div class="bg-cream rounded-2xl border border-brown-100 shadow-sm overflow-hidden">

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-brown-50 border-b border-brown-100">
                        <th class="text-left text-[11px] font-medium tracking-wider uppercase text-brown-400 px-6 py-3 w-12">#</th>
                        <th class="text-left text-[11px] font-medium tracking-wider uppercase text-brown-400 px-6 py-3">Product</th>
                        <th class="text-left text-[11px] font-medium tracking-wider uppercase text-brown-400 px-6 py-3">Category</th>
                        <th class="text-left text-[11px] font-medium tracking-wider uppercase text-brown-400 px-6 py-3">Price</th>
                        <th class="text-left text-[11px] font-medium tracking-wider uppercase text-brown-400 px-6 py-3">Added</th>
                        <th class="text-right text-[11px] font-medium tracking-wider uppercase text-brown-400 px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brown-50" id="tableBody">

                    @forelse($products as $product)
                    <tr class="hover:bg-brown-50/60 transition-colors duration-100 product-row">

                        {{-- Row number --}}
                        <td class="px-6 py-4 text-sm text-brown-300">
                            {{ $products->firstItem() + $loop->index }}
                        </td>

                        {{-- Product name + thumbnail --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-brown-100 shrink-0 flex items-center justify-center">
                                    @if($product->image)
                                    <img
                                        src="{{ Storage::url($product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                    @else
                                    <svg class="w-5 h-5 text-brown-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-espresso product-name">{{ $product->name }}</p>
                                    @if($product->description)
                                    <p class="text-[11px] text-brown-400 mt-0.5 max-w-[200px] truncate">
                                        {{ $product->description }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Category badge --}}
                        <td class="px-6 py-4">
                            @if($product->category)
                            <span class="product-category inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-brown-100 text-brown-600 text-xs font-medium">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $product->category->name }}
                            </span>
                            @else
                            <span class="text-xs text-brown-300">—</span>
                            @endif
                        </td>

                        {{-- Price --}}
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-espresso">
                                ${{ number_format($product->price, 2) }}
                            </span>
                        </td>

                        {{-- Created at --}}
                        <td class="px-6 py-4 text-sm text-brown-400">
                            {{ $product->created_at->format('d M Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1">

                                {{-- Edit --}}
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    title="Edit"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-400 hover:text-brown-700 hover:bg-brown-100 transition-all duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-brown-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brown-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-brown-500">No products yet</p>
                                <p class="text-xs text-brown-300">Add your first product to get started</p>
                                <a href="{{ route('admin.products.create') }}"
                                    class="mt-1 flex items-center gap-1.5 px-4 py-2 rounded-xl bg-espresso text-cream text-xs font-medium hover:bg-roast transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    New Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    {{-- No search results row --}}
                    <tr id="noResults" class="hidden">
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-brown-400">
                            No products match your search.
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-brown-100 flex items-center justify-between">
            <p class="text-xs text-brown-400">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
            </p>
            <div class="flex items-center gap-1">

                @if($products->onFirstPage())
                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-200 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
                @else
                <a href="{{ $products->previousPageUrl() }}"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-400 hover:bg-brown-100 hover:text-brown-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                @endif

                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-xs transition-colors
                              {{ $page == $products->currentPage()
                                  ? 'bg-espresso text-cream font-medium'
                                  : 'text-brown-400 hover:bg-brown-100 hover:text-brown-700' }}">
                    {{ $page }}
                </a>
                @endforeach

                @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-400 hover:bg-brown-100 hover:text-brown-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @else
                <span class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-200 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
                @endif

            </div>
        </div>
        @endif

    </div>

    {{-- Live search + category filter --}}
    <script>
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const noResults = document.getElementById('noResults');

        function filterRows() {
            const q = searchInput.value.toLowerCase().trim();
            const cat = categoryFilter.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.product-row');
            let visible = 0;

            rows.forEach(row => {
                const name = row.querySelector('.product-name').textContent.toLowerCase();
                const catEl = row.querySelector('.product-category');
                const catName = catEl ? catEl.textContent.trim().toLowerCase() : '';
                const show = name.includes(q) && (cat === '' || catName.includes(cat));
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            noResults.classList.toggle('hidden', visible > 0);
        }

        searchInput.addEventListener('input', filterRows);
        categoryFilter.addEventListener('change', filterRows);
    </script>

</x-layouts.admin>