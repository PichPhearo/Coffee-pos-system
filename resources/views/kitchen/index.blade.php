<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Queue | Doray's coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        espresso: '#1C1410',
                        roast: '#2D1B0E',
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
    </style>
</head>

<body class="bg-brown-50 min-h-screen flex flex-col">

    @php
    $incomingOrders = collect($orders ?? []);
    $pendingOrders = $incomingOrders->where('status', 'pending');
    $preparingOrders = $incomingOrders->where('status', 'preparing');
    $readyOrders = $incomingOrders->where('status', 'ready');
    $completedOrdersCount = $completedCount ?? 0;
    @endphp

    {{-- ── Top bar ── --}}
    <header class="bg-espresso border-b border-white/[0.06] px-6 py-3 flex items-center justify-between shrink-0">

        {{-- Brand --}}
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-crema flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 fill-espresso" viewBox="0 0 24 24">
                    <path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7V8h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 1 18.5 1zm0 5H16V5h2.5c.83 0 1.5.67 1.5 1.5S19.33 8 18.5 8zM4 19h16v2H4v-2z" />
                </svg>
            </div>
            <div>
                <p class="font-serif text-cream text-base leading-none">Doray's coffee</p>
                <p class="text-crema/60 text-[10px] tracking-widest uppercase mt-0.5">Kitchen Display</p>
            </div>
        </div>

        {{-- Status summary pills --}}
        <div class="hidden sm:flex items-center gap-2">
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-900/40 border border-amber-700/40 text-amber-300 text-xs font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                {{ $pendingOrders->count() }} Pending
            </span>
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-900/40 border border-blue-700/40 text-blue-300 text-xs font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                {{ $preparingOrders->count() }} Preparing
            </span>
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-900/40 border border-green-700/40 text-green-300 text-xs font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                {{ $readyOrders->count() }} Ready
            </span>
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900/40 border border-slate-700/40 text-slate-300 text-xs font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                {{ $completedOrdersCount }} Completed
            </span>
        </div>

        {{-- Profile + Logout --}}
        <div class="flex items-center gap-2">
            {{-- User info --}}
            <div class="hidden sm:flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-white/[0.06]">
                <div class="w-6 h-6 rounded-full bg-brown-700 flex items-center justify-center shrink-0">
                    <span class="text-crema text-[10px] font-semibold uppercase">
                        {{ substr(Auth::user()->name ?? 'K', 0, 1) }}
                    </span>
                </div>
                <span class="text-cream text-xs font-medium">{{ Auth::user()->name ?? 'Kitchen Staff' }}</span>
            </div>

            {{-- Profile link --}}
            <a href="{{ route('profile.edit') }}"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-400 hover:text-crema hover:bg-white/[0.06] transition-all"
                title="Profile">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-400 hover:text-red-400 hover:bg-red-950/30 transition-all"
                    title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>

    </header>

    {{-- ── Main content ── --}}
    <main class="flex-1 px-6 py-5 overflow-auto">

        {{-- Section label --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-serif text-xl text-espresso">Incoming Orders</h2>
            <span class="text-xs text-brown-400 bg-cream border border-brown-100 px-3 py-1.5 rounded-full">
                {{ $incomingOrders->count() }} {{ Str::plural('order', $incomingOrders->count()) }}
            </span>
        </div>

        {{-- ── 4-column order cards grid ── --}}
        @if($incomingOrders->count())
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            @foreach($incomingOrders as $order)
            @php
            $status = data_get($order, 'status', 'pending');
            $items = data_get($order, 'items', []);

            $topBorder = [
            'pending' => 'border-t-amber-400',
            'preparing' => 'border-t-blue-400',
            'ready' => 'border-t-green-400',
            'served' => 'border-t-brown-300',
            ][$status] ?? 'border-t-brown-200';

            $statusBadge = [
            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
            'preparing' => 'bg-blue-100 text-blue-700 border-blue-200',
            'ready' => 'bg-green-100 text-green-700 border-green-200',
            'served' => 'bg-brown-100 text-brown-500 border-brown-200',
            ][$status] ?? 'bg-brown-100 text-brown-500 border-brown-200';

            $dot = [
            'pending' => 'bg-amber-400',
            'preparing' => 'bg-blue-400',
            'ready' => 'bg-green-400',
            'served' => 'bg-brown-300',
            ][$status] ?? 'bg-brown-300';
            @endphp

            <article class="bg-cream rounded-2xl border border-brown-100 border-t-4 {{ $topBorder }} shadow-sm flex flex-col overflow-hidden">

                {{-- Card header --}}
                <div class="px-4 pt-4 pb-3 border-b border-brown-100 flex items-start justify-between gap-2">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-brown-400">Order</p>
                        <p class="text-xl font-serif text-espresso leading-tight">#{{ data_get($order, 'id', '0000') }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[11px] font-medium {{ $statusBadge }} shrink-0 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                        {{ ucfirst($status) }}
                    </span>
                </div>

                {{-- Order meta --}}
                <div class="px-4 py-3 grid grid-cols-3 border-b border-brown-100">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-brown-400">Cashier</p>
                        <p class="text-xs font-semibold text-brown-700 truncate">{{ data_get($order, 'user.name', 'Front Desk') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-brown-400">Type</p>
                        <p class="text-xs font-semibold text-brown-700 uppercase">{{ data_get($order, 'order_type', 'Dine-in') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-brown-400">Placed</p>
                        <p class="text-xs font-semibold text-brown-700 truncate">{{ data_get($order, 'created_at', 'Just now') }}</p>
                    </div>
                </div>

                {{-- Items list --}}
                <div class="px-4 py-3 flex-1">
                    <p class="text-xs uppercase tracking-widest text-brown-500 mb-2">Items</p>
                    @if(count($items))
                    <ul class="space-y-2.5">
                        @foreach($items as $item)
                        <li class="flex items-start gap-2 rounded-xl border border-brown-100 bg-white p-2.5">
                            <span class="w-7 h-7 rounded-md bg-brown-100 border border-brown-200 text-sm font-semibold text-brown-700 flex items-center justify-center shrink-0 mt-0.5">
                                {{ data_get($item, 'quantity', 1) }}
                            </span>
                            <div>
                                <p class="text-base font-semibold text-espresso leading-snug">
                                    {{ data_get($item, 'product.name', data_get($item, 'name', 'Custom Item')) }}
                                </p>
                                <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-brown-50 border border-brown-100 text-[11px] font-medium text-brown-700">
                                        <span class="text-[9px] uppercase tracking-wider text-brown-400">Size</span>
                                        {{ ucfirst((string) data_get($item, 'size', '-')) }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-brown-50 border border-brown-100 text-[11px] font-medium text-brown-700">
                                        <span class="text-[9px] uppercase tracking-wider text-brown-400">Sugar</span>
                                        {{ ucwords((string) data_get($item, 'sugar_level', '-')) }}
                                    </span>
                                </div>
                                @if(data_get($item, 'note'))
                                <p class="mt-1 text-xs text-brown-500 italic">Note: {{ data_get($item, 'note') }}</p>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-xs text-brown-400">No items yet.</p>
                    @endif
                </div>

                {{-- Special note --}}
                @if(data_get($order, 'notes'))
                <div class="mx-4 mb-3 flex items-start gap-2 px-3 py-2 rounded-xl bg-amber-50 border border-amber-200">
                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-[11px] text-amber-700 leading-snug">{{ data_get($order, 'notes') }}</p>
                </div>
                @endif

                {{-- Action buttons --}}
                <div class="px-4 pb-4 flex flex-col gap-2">
                    @if($status === 'pending')
                    <form method="POST" action="{{ route('kitchen.update', data_get($order, 'id')) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="preparing">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Start Preparing
                        </button>
                    </form>
                    @elseif($status === 'preparing')
                    <form method="POST" action="{{ route('kitchen.update', data_get($order, 'id')) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="ready">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Mark as Ready
                        </button>
                    </form>
                    @elseif($status === 'ready')
                    <div class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-green-50 border border-green-200 text-green-700 text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Ready for pickup
                    </div>
                    <form method="POST" action="{{ route('kitchen.update', data_get($order, 'id')) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-espresso hover:bg-brown-800 text-cream text-xs font-medium transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Completed
                        </button>
                    </form>
                    @endif
                </div>

            </article>
            @endforeach

        </div>

        @else
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-16 h-16 rounded-2xl bg-cream border border-brown-100 flex items-center justify-center mb-4 shadow-sm">
                <svg class="w-8 h-8 text-brown-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-base font-medium text-brown-500">No orders in queue</p>
            <p class="text-sm text-brown-400 mt-1">New orders from the cashier will appear here.</p>
        </div>
        @endif

    </main>

    {{-- ── Footer ── --}}
    <footer class="shrink-0 px-6 py-3 bg-cream border-t border-brown-100 flex items-center justify-between">
        <span class="text-[11px] text-brown-300">Doray's cofee</span>
        <span class="text-[11px] text-brown-300" id="clock"></span>
    </footer>

    <script>
        function tick() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
        tick();
        setInterval(tick, 1000);

        window.addEventListener('load', () => {
            if (window.Echo) {
                window.Echo.channel('kitchen.orders')
                    .listen('.order.placed', () => {
                        window.location.reload();
                    });
            }
        });
    </script>

</body>

</html>