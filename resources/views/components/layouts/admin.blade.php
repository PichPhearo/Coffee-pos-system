@props([
'title' => 'Dashboard',
'subtitle' => 'Welcome back',
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Doray's coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    @vite(['resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        espresso: '#1C1410',
                        crema: '#C8A96E',
                        cream: '#FDF8F2',
                        brown: {
                            50: '#FAF5EF',
                            100: '#F2E4CC',
                            200: '#E4C99A',
                            300: '#CFA76A',
                            400: '#B8863E',
                            500: '#8B5E2A',
                            600: '#6B4420',
                            700: '#4E2F14',
                            800: '#33200D',
                            900: '#1C1208',
                        },
                    },
                    fontFamily: {
                        serif: ['"DM Serif Display"', 'serif'],
                        sans: ['"DM Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .nav-link.active {
            background: rgba(200, 169, 110, 0.12);
            color: #C8A96E;
        }

        .nav-link.active .nav-icon {
            color: #C8A96E;
        }

        .nav-link.active .nav-indicator {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-brown-50 text-espresso min-h-screen">

    <div class="flex min-h-screen">

        {{-- ══════════════════════════════
         SIDEBAR
    ══════════════════════════════ --}}
        <aside class="w-60 min-h-screen bg-espresso flex flex-col fixed top-0 left-0 z-50">

            {{-- Brand --}}
            <div class="px-5 pt-6 pb-5 border-b border-white/[0.06]">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-crema flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 fill-espresso" viewBox="0 0 24 24">
                            <path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7V8h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 1 18.5 1zm0 5H16V5h2.5c.83 0 1.5.67 1.5 1.5S19.33 8 18.5 8zM4 19h16v2H4v-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-serif text-cream text-[17px] leading-tight">Doray's coffee</p>
                        <p class="text-crema/70 text-[10px] tracking-widest uppercase mt-0.5">Admin Panel</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 pt-5 pb-2 flex flex-col gap-0.5 overflow-y-auto">

                <p class="text-[10px] font-medium tracking-widest uppercase text-brown-700 px-3 mb-2">Menu</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-brown-400 hover:text-crema hover:bg-white/[0.05] transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-indicator w-[3px] h-4 rounded-full bg-crema opacity-0 transition-opacity -ml-0.5 shrink-0"></span>
                    <svg class="nav-icon w-[17px] h-[17px] shrink-0 text-brown-600 group-hover:text-crema transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="nav-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-brown-400 hover:text-crema hover:bg-white/[0.05] transition-all duration-150 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <span class="nav-indicator w-[3px] h-4 rounded-full bg-crema opacity-0 transition-opacity -ml-0.5 shrink-0"></span>
                    <svg class="nav-icon w-[17px] h-[17px] shrink-0 text-brown-600 group-hover:text-crema transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="text-sm">Products</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="nav-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-brown-400 hover:text-crema hover:bg-white/[0.05] transition-all duration-150 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="nav-indicator w-[3px] h-4 rounded-full bg-crema opacity-0 transition-opacity -ml-0.5 shrink-0"></span>
                    <svg class="nav-icon w-[17px] h-[17px] shrink-0 text-brown-600 group-hover:text-crema transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="text-sm">Categories</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                    class="nav-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-brown-400 hover:text-crema hover:bg-white/[0.05] transition-all duration-150 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span class="nav-indicator w-[3px] h-4 rounded-full bg-crema opacity-0 transition-opacity -ml-0.5 shrink-0"></span>
                    <svg class="nav-icon w-[17px] h-[17px] shrink-0 text-brown-600 group-hover:text-crema transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="text-sm">Orders</span>
                    @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                    <span class="ml-auto bg-crema text-espresso text-[10px] font-semibold px-1.5 py-0.5 rounded-full leading-none">
                        {{ $pendingOrdersCount }}
                    </span>
                    @endif
                </a>

                <div class="my-3 border-t border-white/[0.06]"></div>
                <p class="text-[10px] font-medium tracking-widest uppercase text-brown-700 px-3 mb-2">Manage</p>

                <a href="{{ route('admin.staff.index') }}"
                    class="nav-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-brown-400 hover:text-crema hover:bg-white/[0.05] transition-all duration-150 {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                    <span class="nav-indicator w-[3px] h-4 rounded-full bg-crema opacity-0 transition-opacity -ml-0.5 shrink-0"></span>
                    <svg class="nav-icon w-[17px] h-[17px] shrink-0 text-brown-600 group-hover:text-crema transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm">Staff</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                    class="nav-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-brown-400 hover:text-crema hover:bg-white/[0.05] transition-all duration-150 {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <span class="nav-indicator w-[3px] h-4 rounded-full bg-crema opacity-0 transition-opacity -ml-0.5 shrink-0"></span>
                    <svg class="nav-icon w-[17px] h-[17px] shrink-0 text-brown-600 group-hover:text-crema transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="text-sm">Reports</span>
                </a>

            </nav>

            {{-- Profile + Logout --}}
            <div class="px-3 pt-3 border-t border-white/[0.06] flex flex-col gap-0.5">

                <a href="{{ route('admin.profile.edit') }}"
                    class="nav-link group flex items-center gap-3 px-3 py-2.5 rounded-lg text-brown-400 hover:text-crema hover:bg-white/[0.05] transition-all duration-150 {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                    <span class="nav-indicator w-[3px] h-4 rounded-full bg-crema opacity-0 transition-opacity -ml-0.5 shrink-0"></span>
                    <svg class="nav-icon w-[17px] h-[17px] shrink-0 text-brown-600 group-hover:text-crema transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-sm">Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full group flex items-center gap-3 px-3 py-2.5 rounded-lg text-brown-400 hover:text-red-400 hover:bg-red-950/30 transition-all duration-150">
                        <span class="w-[3px] h-4 -ml-0.5 shrink-0"></span>
                        <svg class="w-[17px] h-[17px] shrink-0 text-brown-600 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm">Logout</span>
                    </button>
                </form>

            </div>

            {{-- User card --}}
            <div class="mx-3 my-3 p-3 rounded-xl bg-white/[0.05] flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-brown-700 flex items-center justify-center shrink-0">
                    <span class="text-crema text-xs font-semibold uppercase">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </span>
                </div>
                <div class="min-w-0">
                    <p class="text-cream text-xs font-medium truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-brown-600 text-[10px] truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>

        </aside>

        {{-- ══════════════════════════════
         MAIN CONTENT
    ══════════════════════════════ --}}
        <div class="flex-1 ml-60 flex flex-col min-h-screen">

            {{-- Top bar --}}
            <header class="sticky top-0 z-40 bg-cream/80 backdrop-blur-sm border-b border-brown-100 px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="font-serif text-xl text-espresso leading-tight">{{ $title }}</h1>
                    <p class="text-[11px] text-brown-400 mt-0.5">{{ $subtitle }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden sm:block text-xs text-brown-300 bg-brown-100 px-3 py-1.5 rounded-full">
                        {{ now()->format('D, d M Y') }}
                    </span>
                    <button class="w-9 h-9 rounded-full bg-brown-100 hover:bg-brown-200 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-brown-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Page slot --}}
            <main class="flex-1 px-8 py-7">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="px-8 py-4 border-t border-brown-100 flex items-center justify-between">
                <span class="text-[11px] text-brown-300">Doray's coffee</span>
                <span class="text-[11px] text-brown-300">{{ now()->year }}</span>
            </footer>

        </div>
    </div>

</body>

</html>