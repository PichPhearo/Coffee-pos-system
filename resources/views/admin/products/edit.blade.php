<x-layouts.admin title="Edit Product" subtitle="Update product details">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-brown-400 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-brown-600 transition-colors">Dashboard</a>
        <span class="text-brown-200">/</span>
        <a href="{{ route('admin.products.index') }}" class="hover:text-brown-600 transition-colors">Products</a>
        <span class="text-brown-200">/</span>
        <span class="text-brown-600">Edit</span>
    </nav>

    {{-- <div class="max-w-xl"> --}}
    <div class="max-w-xl mx-auto">
        {{-- Card --}}
        <div class="bg-cream rounded-2xl border border-brown-100 shadow-sm overflow-hidden">

            {{-- Card header --}}
            <div class="px-7 py-5 border-b border-brown-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-brown-100 flex items-center justify-center shrink-0">
                        <svg class="w-[18px] h-[18px] text-brown-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-espresso">Edit Product</p>
                        <p class="text-[11px] text-brown-400 mt-0.5">ID #{{ $product->id }}</p>
                    </div>
                </div>

                {{-- Category badge --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brown-100 text-brown-500 text-xs font-medium">
                    {{ $product->category->name ?? 'Uncategorized' }}
                </span>
            </div>

            {{-- Form --}}
            <form id="product-update-form" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="px-7 py-6 flex flex-col gap-5">
                @csrf
                @method('PUT')

                {{-- Top Section: Two Column Layout (Fields + Image) --}}
                <div class="grid grid-cols-2 gap-6">
                    
                    {{-- LEFT COLUMN: Name, Price, Category --}}
                    <div class="flex flex-col gap-2.5">
                        
                        {{-- Product Name --}}
                        <div class="flex flex-col gap-1.5">
                            <label for="name" class="text-xs font-medium text-brown-600 tracking-wide">
                                Product Name <span class="text-red-400">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $product->name) }}"
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
                                    value="{{ old('price', $product->price) }}"
                                    step="0.01"
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
                                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
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
                    </div>

                    {{-- RIGHT COLUMN: Large Image --}}
                    <div class="flex flex-col gap-1.5">
                        {{-- Current image preview --}}
                        @if($product->image)
                            <label class="text-xs font-medium text-brown-600 tracking-wide">Current Image</label>
                            <div class="w-full h-48 rounded-xl overflow-hidden bg-brown-50 border border-brown-100 flex items-center justify-center">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <label class="text-xs font-medium text-brown-600 tracking-wide">Product Image</label>
                            <div class="w-full h-64 rounded-xl bg-brown-50 border-2 border-dashed border-brown-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-brown-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs text-brown-400">No image</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- File Upload (Full Width) --}}
                <div class="flex flex-col gap-1.5">
                    <label for="image" class="text-xs font-medium text-brown-600 tracking-wide">
                        Choose File
                        <span class="text-brown-300 font-normal ml-1">optional</span>
                    </label>
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
                    <p class="text-[11px] text-brown-400">JPG, PNG, GIF, WebP (Max 5MB)</p>
                    @error('image')
                        <p class="text-xs text-red-400 flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Meta info: Created & Last Updated (Side by side) --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="px-4 py-3 rounded-xl bg-brown-50 border border-brown-100">
                        <p class="text-xs text-brown-600 font-medium">
                            <span class="text-[10px] uppercase tracking-wider text-brown-400">Created:</span> {{ $product->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="px-4 py-3 rounded-xl bg-brown-50 border border-brown-100">
                        <p class="text-xs text-brown-600 font-medium">
                            <span class="text-[10px] uppercase tracking-wider text-brown-400">Last updated:</span> {{ $product->updated_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-brown-100"></div>

                {{-- Actions --}}
                <div class="flex items-center justify-between">

                    {{-- Delete --}}
                    <button type="submit" form="product-delete-form"
                        class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-red-400 hover:text-red-600 hover:bg-red-50 text-sm transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.index') }}"
                           class="px-5 py-2.5 rounded-xl text-sm text-brown-500 hover:text-brown-700 hover:bg-brown-100 transition-all duration-150">
                            Cancel
                        </a>
                        <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-espresso hover:bg-roast text-cream text-sm font-medium transition-all duration-150 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>

                </div>

            </form>

            {{-- Delete form (separate to avoid nested forms) --}}
            <form id="product-delete-form" action="{{ route('admin.products.destroy', $product) }}" method="POST"
                  onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')">
                @csrf
                @method('DELETE')
            </form>

        </div>

    </div>

</x-layouts.admin>
