<x-layouts.admin title="New Category" subtitle="Add a new category to your menu">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-brown-400 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-brown-600 transition-colors">Dashboard</a>
        <span class="text-brown-200">/</span>
        <a href="{{ route('admin.categories.index') }}" class="hover:text-brown-600 transition-colors">Categories</a>
        <span class="text-brown-200">/</span>
        <span class="text-brown-600">New</span>
    </nav>

    <div class="max-w-xl mx-auto">

        {{-- Card --}}
        <div class="bg-cream rounded-2xl border border-brown-100 shadow-sm overflow-hidden">

            {{-- Card header --}}
            <div class="px-7 py-5 border-b border-brown-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brown-100 flex items-center justify-center shrink-0">
                    <svg class="w-[18px] h-[18px] text-brown-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-espresso">Create Category</p>
                    <p class="text-[11px] text-brown-400 mt-0.5">Categories help organise your menu items</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.categories.store') }}" method="POST" class="px-7 py-6 flex flex-col gap-5">
                @csrf

                {{-- Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="name" class="text-xs font-medium text-brown-600 tracking-wide">
                        Category Name <span class="text-red-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="e.g. Hot Drinks, Pastries, Cold Brew…"
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

                {{-- Description (optional) --}}
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
                    >{{ old('description') }}</textarea>
                    @error('description')
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
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-5 py-2.5 rounded-xl text-sm text-brown-500 hover:text-brown-700 hover:bg-brown-100 transition-all duration-150">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-espresso hover:bg-roast text-cream text-sm font-medium transition-all duration-150 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Category
                    </button>
                </div>

            </form>
        </div>

        {{-- Success flash --}}
        @if(session('success'))
            <div class="mt-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

    </div>

</x-layouts.admin>