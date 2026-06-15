<x-layouts.admin title="Edit Category" subtitle="Update category details">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-brown-400 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-brown-600 transition-colors">Dashboard</a>
        <span class="text-brown-200">/</span>
        <a href="{{ route('admin.categories.index') }}" class="hover:text-brown-600 transition-colors">Categories</a>
        <span class="text-brown-200">/</span>
        <span class="text-brown-600">Edit</span>
    </nav>

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
                        <p class="text-sm font-medium text-espresso">Edit Category</p>
                        <p class="text-[11px] text-brown-400 mt-0.5">ID #{{ $category->id }}</p>
                    </div>
                </div>

                {{-- Products count badge --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brown-100 text-brown-500 text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    {{ $category->products_count ?? $category->products()->count() }} products
                </span>
            </div>

            {{-- Form --}}
            <form id="category-update-form" action="{{ route('admin.categories.update', $category) }}" method="POST" class="px-7 py-6 flex flex-col gap-5">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="name" class="text-xs font-medium text-brown-600 tracking-wide">
                        Category Name <span class="text-red-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
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

                {{-- Description --}}
                <div class="flex flex-col gap-1.5">
                    <label for="description" class="text-xs font-medium text-brown-600 tracking-wide">
                        Description
                        <span class="text-brown-300 font-normal ml-1">optional</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Short description of this category…"
                        class="w-full px-4 py-2.5 rounded-xl border text-sm text-espresso placeholder-brown-300 bg-white
                               border-brown-200 focus:outline-none focus:border-crema focus:ring-2 focus:ring-crema/20
                               transition-all duration-150 resize-none
                               @error('description') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                    >{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-400 flex items-center gap-1 mt-0.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Meta info --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="px-4 py-3 rounded-xl bg-brown-50 border border-brown-100">
                        <p class="text-[10px] uppercase tracking-wider text-brown-400 mb-1">Created</p>
                        <p class="text-xs text-brown-600 font-medium">{{ $category->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="px-4 py-3 rounded-xl bg-brown-50 border border-brown-100">
                        <p class="text-[10px] uppercase tracking-wider text-brown-400 mb-1">Last updated</p>
                        <p class="text-xs text-brown-600 font-medium">{{ $category->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-brown-100"></div>

                {{-- Actions --}}
                <div class="flex items-center justify-between">

                    {{-- Delete --}}
                    <button type="submit" form="category-delete-form"
                        class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-red-400 hover:text-red-600 hover:bg-red-50 text-sm transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.categories.index') }}"
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

            <form id="category-delete-form" action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                  onsubmit="return confirm('Delete \'{{ addslashes($category->name) }}\'? This cannot be undone.')">
                @csrf
                @method('DELETE')
            </form>
        </div>

    </div>

</x-layouts.admin>