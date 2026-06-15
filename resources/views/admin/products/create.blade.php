<x-layouts.admin title="New Product" subtitle="Add a new menu item">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-brown-400 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-brown-600 transition-colors">Dashboard</a>
        <span class="text-brown-200">/</span>
        <a href="{{ route('admin.products.index') }}" class="hover:text-brown-600 transition-colors">Products</a>
        <span class="text-brown-200">/</span>
        <span class="text-brown-600">New</span>
    </nav>

    <div class="max-w-xl">

        {{-- Card --}}
        <div class="bg-cream rounded-2xl border border-brown-100 shadow-sm overflow-hidden">

            {{-- Card header --}}
            <div class="px-7 py-5 border-b border-brown-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brown-100 flex items-center justify-center shrink-0">
                    <svg class="w-[18px] h-[18px] text-brown-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-espresso">Create Product</p>
                    <p class="text-[11px] text-brown-400 mt-0.5">Add a new item to your menu</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="px-7 py-6 flex flex-col gap-5">
                @csrf

                {{-- Product Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="name" class="text-xs font-medium text-brown-600 tracking-wide">
                        Product Name <span class="text-red-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="e.g. Espresso, Cappuccino, Croissant…"
                        autofocus
                        class="w-full px-4 py-2.5 rounded-xl border text-sm text-espresso placeholder-brown-300 bg-white
                               border-brown-200 focus:outline-none focus:border-crema focus:ring-2 focus:ring-crema/20
                               transition-all duration-150
                               @error('name') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                    >
                    @error('name')
                        <p class="text-xs text-red-400 flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="flex flex-col gap-1.5">
                    <label for="price" class="text-xs font-medium text-brown-600 tracking-wide">
                        Price <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brown-400 text-sm">$</span>
                        <input
                            type="number"
                            id="price"
                            name="price"
                            value="{{ old('price') }}"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full pl-8 pr-4 py-2.5 rounded-xl border text-sm text-espresso placeholder-brown-300 bg-white
                                   border-brown-200 focus:outline-none focus:border-crema focus:ring-2 focus:ring-crema/20
                                   transition-all duration-150
                                   @error('price') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                        >
                    </div>
                    @error('price')
                        <p class="text-xs text-red-400 flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="flex flex-col gap-1.5">
                    <label for="category_id" class="text-xs font-medium text-brown-600 tracking-wide">
                        Category <span class="text-red-400">*</span>
                    </label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full px-4 py-2.5 rounded-xl border text-sm text-espresso bg-white
                               border-brown-200 focus:outline-none focus:border-crema focus:ring-2 focus:ring-crema/20
                               transition-all duration-150
                               @error('category_id') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                    >
                        <option value="">-- Select a category --</option>
                        @forelse($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @empty
                            <option value="" disabled>No categories available</option>
                        @endforelse
                    </select>
                    @error('category_id')
                        <p class="text-xs text-red-400 flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="flex flex-col gap-1.5">
                    <label for="image" class="text-xs font-medium text-brown-600 tracking-wide">
                        Product Image
                        <span class="text-brown-300 font-normal ml-1">optional</span>
                    </label>
                    <div class="relative">
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            class="w-full px-4 py-2.5 rounded-xl border text-sm text-espresso bg-white
                                   border-brown-200 focus:outline-none focus:border-crema focus:ring-2 focus:ring-crema/20
                                   transition-all duration-150 file:mr-4 file:rounded-xl file:border-0 file:bg-brown-100
                                   file:text-xs file:font-medium file:text-brown-600 file:px-3 file:py-1.5
                                   @error('image') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                        >
                    </div>
                    <p class="text-[11px] text-brown-400">Accepted formats: JPG, PNG, GIF, WebP (Max 5MB)</p>
                    @error('image')
                        <p class="text-xs text-red-400 flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="border-t border-brown-100"></div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.products.index') }}"
                       class="px-5 py-2.5 rounded-xl text-sm text-brown-500 hover:text-brown-700 hover:bg-brown-100 transition-all duration-150">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-espresso hover:bg-roast text-cream text-sm font-medium transition-all duration-150 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Product
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.admin>