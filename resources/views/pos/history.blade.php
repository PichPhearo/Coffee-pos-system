<x-layouts.pos title="Order History" subtitle="Completed orders received by customers">
    @php
    $orders = collect($orders ?? []);
    $period = $period ?? 'day';
    $selectedDate = $selectedDate ?? null;
    $periodLabel = $selectedDate
    ? \Illuminate\Support\Carbon::parse($selectedDate)->format('d M Y')
    : match ($period) {
    'week' => 'This Week',
    'month' => 'This Month',
    default => 'Today',
    };
    @endphp

    <div class="space-y-4">
        <div class="rounded-2xl border border-brown-200 bg-white p-3">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs uppercase tracking-wider text-brown-500">Filter Period</span>
                    <a
                        href="{{ route('pos.history', ['period' => 'day']) }}"
                        class="inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-medium transition {{ ! $selectedDate && $period === 'day' ? 'border-brown-700 bg-brown-700 text-white' : 'border-brown-200 bg-white text-brown-700 hover:bg-brown-50' }}">
                        Day
                    </a>
                    <a
                        href="{{ route('pos.history', ['period' => 'week']) }}"
                        class="inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-medium transition {{ ! $selectedDate && $period === 'week' ? 'border-brown-700 bg-brown-700 text-white' : 'border-brown-200 bg-white text-brown-700 hover:bg-brown-50' }}">
                        Week
                    </a>
                    <a
                        href="{{ route('pos.history', ['period' => 'month']) }}"
                        class="inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-medium transition {{ ! $selectedDate && $period === 'month' ? 'border-brown-700 bg-brown-700 text-white' : 'border-brown-200 bg-white text-brown-700 hover:bg-brown-50' }}">
                        Month
                    </a>
                </div>

                <div class="h-6 w-px bg-brown-100"></div>

                <form method="GET" action="{{ route('pos.history') }}" class="flex flex-wrap items-center gap-2">
                    <span class="text-xs uppercase tracking-wider text-brown-500">Exact Date</span>
                    <input
                        type="date"
                        name="date"
                        value="{{ $selectedDate }}"
                        class="rounded-lg border border-brown-200 bg-white px-3 py-1.5 text-sm text-brown-700">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg border border-brown-700 bg-brown-700 px-3 py-1.5 text-sm font-medium text-white">
                        Apply
                    </button>
                    <a
                        href="{{ route('pos.history', ['period' => 'day']) }}"
                        class="inline-flex items-center rounded-lg border border-brown-200 bg-white px-3 py-1.5 text-sm font-medium text-brown-700 hover:bg-brown-50">
                        Clear
                    </a>
                </form>
            </div>
        </div>

        <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
            <p class="text-xs uppercase tracking-wider text-green-700">Completed Orders - {{ $periodLabel }}</p>
            <p class="mt-1 text-2xl font-semibold text-green-900">{{ $orders->count() }}</p>
        </div>

        <div class="bg-cream rounded-2xl border border-brown-100 shadow-sm overflow-hidden">
            @if($orders->isEmpty())
            <div class="p-6 text-brown-600">No completed orders yet.</div>
            @else
            <div class="space-y-3 p-3">
                @foreach($orders as $order)
                <article class="rounded-xl border border-brown-100 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-brown-500">Order</p>
                            <h3 class="text-lg font-semibold text-espresso">#{{ data_get($order, 'id') }}</h3>
                            <p class="mt-1 text-xs text-brown-500">Completed {{ optional(data_get($order, 'updated_at'))->format('d M Y, H:i') }}</p>
                        </div>

                        <span class="inline-flex items-center rounded-full border border-green-200 bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            Completed
                        </span>
                    </div>

                    <div class="mt-3 rounded-xl border border-brown-100 bg-white p-3">
                        <p class="mb-2 text-xs uppercase tracking-wider text-brown-500">Items</p>
                        <ul class="space-y-1.5">
                            @foreach(data_get($order, 'items', []) as $item)
                            <li class="flex items-center justify-between gap-3 text-sm text-espresso">
                                <span>{{ data_get($item, 'quantity', 1) }}x {{ data_get($item, 'product.name', 'Item') }}</span>
                                <span class="text-brown-500">${{ number_format((float) data_get($item, 'price', 0), 2) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm">
                        <span class="text-brown-600">Payment: <span class="font-medium uppercase text-espresso">{{ data_get($order, 'payment_method', '-') }}</span></span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-espresso">Total: ${{ number_format((float) data_get($order, 'total_price', 0), 2) }}</span>
                            <button
                                type="button"
                                class="view-receipt-btn rounded-lg border border-brown-200 bg-white px-3 py-1.5 text-xs font-medium text-brown-700 hover:bg-brown-50"
                                data-order='@json($order)'
                                data-date-field="updated_at"
                                data-date-prefix="Completed">
                                View Receipt
                            </button>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <x-pos.receipt-modal />

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
</x-layouts.pos>