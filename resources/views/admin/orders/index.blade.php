<x-layouts.admin title="My Orders" subtitle="Track order status from barista">
    @php
    $orders = collect($orders ?? []);
    $pendingOrders = $orders->where('status', 'pending')->values();
    $preparingOrders = $orders->where('status', 'preparing')->values();
    $readyOrders = $orders->where('status', 'ready')->values();

    $sections = [
    [
    'label' => 'Pending',
    'description' => 'Waiting for barista to start',
    'orders' => $pendingOrders,
    'badge' => 'bg-amber-100 text-amber-700 border-amber-200',
    'wrap' => 'border-amber-200 bg-amber-50',
    ],
    [
    'label' => 'Preparing',
    'description' => 'Currently being prepared',
    'orders' => $preparingOrders,
    'badge' => 'bg-blue-100 text-blue-700 border-blue-200',
    'wrap' => 'border-blue-200 bg-blue-50',
    ],
    [
    'label' => 'Ready',
    'description' => 'Ready for pickup by customer',
    'orders' => $readyOrders,
    'badge' => 'bg-green-100 text-green-700 border-green-200',
    'wrap' => 'border-green-200 bg-green-50',
    ],
    ];
    @endphp

    <div class="space-y-4">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <p class="text-xs uppercase tracking-wider text-amber-700">Pending</p>
                <p class="mt-1 text-2xl font-semibold text-amber-900">{{ $orders->where('status', 'pending')->count() }}</p>
            </div>
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                <p class="text-xs uppercase tracking-wider text-blue-700">Preparing</p>
                <p class="mt-1 text-2xl font-semibold text-blue-900">{{ $orders->where('status', 'preparing')->count() }}</p>
            </div>
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                <p class="text-xs uppercase tracking-wider text-green-700">Ready</p>
                <p class="mt-1 text-2xl font-semibold text-green-900">{{ $orders->where('status', 'ready')->count() }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            @foreach($sections as $section)
            <section class="rounded-2xl border shadow-sm overflow-hidden {{ $section['wrap'] }}">
                <div class="flex items-center justify-between px-5 py-4 border-b border-brown-100 bg-white/70">
                    <div>
                        <h3 class="text-base font-semibold text-espresso">{{ $section['label'] }} Orders</h3>
                        <p class="text-xs text-brown-500">{{ $section['description'] }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $section['badge'] }}">
                        {{ $section['orders']->count() }}
                    </span>
                </div>

                @if($section['orders']->isEmpty())
                <div class="p-5 text-sm text-brown-500 bg-cream">No {{ strtolower($section['label']) }} orders.</div>
                @else
                <div class="divide-y divide-brown-100 bg-cream">
                    @foreach($section['orders'] as $order)
                    @php
                    $itemCount = collect(data_get($order, 'items', []))->sum(fn($i) => (int) data_get($i, 'quantity', 1));
                    @endphp
                    <details class="group order-details">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4 hover:bg-brown-50/60 transition-colors [&::-webkit-details-marker]:hidden">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-semibold text-espresso truncate">#{{ data_get($order, 'id') }}</h4>
                                    <span class="text-[11px] text-brown-400">· {{ $itemCount }} {{ Str::plural('item', $itemCount) }}</span>
                                </div>
                                <p class="text-[11px] text-brown-500 mt-0.5 truncate">
                                    {{ optional(data_get($order, 'created_at'))->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-sm font-semibold text-espresso">${{ number_format((float) data_get($order, 'total_price', 0), 2) }}</span>
                                <span class="hidden sm:inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $section['badge'] }}">
                                    {{ $section['label'] }}
                                </span>
                                <span class="w-7 h-7 rounded-lg bg-brown-100 text-brown-500 flex items-center justify-center shrink-0 transition-transform duration-200 group-open:rotate-180">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </div>
                        </summary>

                        <div class="px-5 pb-5 pt-1 border-t border-brown-100">
                            <div class="mt-3 rounded-xl border border-brown-100 bg-white p-3">
                                <p class="mb-2 text-xs uppercase tracking-wider text-brown-500">Items</p>
                                <ul class="space-y-1.5">
                                    @foreach(data_get($order, 'items', []) as $item)
                                    <li class="flex items-start justify-between gap-3 text-sm text-espresso">
                                        <div>
                                            <p>{{ data_get($item, 'quantity', 1) }}x {{ data_get($item, 'product.name', 'Item') }}</p>
                                            <p class="text-[11px] text-brown-500 mt-0.5">
                                                Size: {{ ucfirst((string) data_get($item, 'size', '-')) }}
                                                • Sugar: {{ ucwords((string) data_get($item, 'sugar_level', '-')) }}
                                            </p>
                                            @if(data_get($item, 'note'))
                                            <p class="text-[11px] text-brown-500 italic">Note: {{ data_get($item, 'note') }}</p>
                                            @endif
                                        </div>
                                        <span class="text-brown-500">${{ number_format((float) data_get($item, 'price', 0), 2) }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm">
                                <span class="text-brown-600">Payment: <span class="font-medium uppercase text-espresso">{{ data_get($order, 'payment_method', '-') }}</span></span>
                                <span class="font-semibold text-espresso">Total: ${{ number_format((float) data_get($order, 'total_price', 0), 2) }}</span>
                            </div>
                        </div>
                    </details>
                    @endforeach
                </div>
                @endif
            </section>
            @endforeach
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            if (!window.Echo) {
                return;
            }

            window.Echo.channel('orders.status')
                .listen('.order.status.updated', () => {
                    window.location.reload();
                });
        });
    </script>
</x-layouts.admin>