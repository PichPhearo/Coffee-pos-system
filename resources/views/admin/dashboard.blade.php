<x-layouts.admin title="Dashboard" subtitle="Business overview and performance trends">

    @php
        $totalOrders = \App\Models\Order::count();
        $completedOrders = \App\Models\Order::where('status', 'completed')->count();
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $preparingOrders = \App\Models\Order::where('status', 'preparing')->count();
        $readyOrders = \App\Models\Order::where('status', 'ready')->count();

        $totalRevenue = (float) \App\Models\Order::where('status', 'completed')->sum('total_price');
        $avgTicket = $completedOrders > 0 ? $totalRevenue / $completedOrders : 0.0;

        $productsCount = \App\Models\Product::count();
        $categoriesCount = \App\Models\Category::count();
        $staffCount = \App\Models\User::whereIn('role', ['admin', 'cashier', 'barista'])->count();

        $days = collect(range(6, 0))->map(fn ($d) => now()->subDays($d));
        $lineLabels = $days->map(fn ($day) => $day->format('D'))->values();

        $revenueSeries = $days->map(function ($day) {
            return (float) \App\Models\Order::where('status', 'completed')
                ->whereDate('created_at', $day->toDateString())
                ->sum('total_price');
        })->values();

        $ordersSeries = $days->map(function ($day) {
            return (int) \App\Models\Order::whereDate('created_at', $day->toDateString())->count();
        })->values();

        $topProducts = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as sold_qty'))
            ->groupBy('products.name')
            ->orderByDesc('sold_qty')
            ->limit(5)
            ->get();

        $topLabels = $topProducts->pluck('name')->values();
        $topSeries = $topProducts->pluck('sold_qty')->map(fn ($v) => (int) $v)->values();

        $recentCompleted = \App\Models\Order::where('status', 'completed')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();
    @endphp

    <div id="admin-dashboard" data-stats-url="{{ route('admin.dashboard.stats') }}" class="space-y-6">
        <section class="rounded-2xl border border-brown-100 bg-gradient-to-r from-cream to-brown-50 p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-widest text-brown-500">Today Snapshot</p>
                    <h2 class="mt-1 font-serif text-2xl text-espresso">Operations Dashboard</h2>
                </div>
                <div class="rounded-xl bg-espresso px-4 py-2 text-right text-cream">
                    <p class="text-[11px] uppercase tracking-wider text-crema">Revenue</p>
                    <p id="metric-total-revenue" class="text-xl font-semibold">${{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-brown-100 bg-cream p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-brown-400">Total Orders</p>
                <p id="metric-total-orders" class="mt-1 text-3xl font-semibold text-espresso">{{ $totalOrders }}</p>
                <p class="mt-1 text-xs text-brown-500">All-time transactions</p>
            </article>

            <article class="rounded-2xl border border-green-200 bg-green-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-green-700">Completed</p>
                <p id="metric-completed-orders" class="mt-1 text-3xl font-semibold text-green-900">{{ $completedOrders }}</p>
                <p class="mt-1 text-xs text-green-700">Successfully served</p>
            </article>

            <article class="rounded-2xl border border-blue-200 bg-blue-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-blue-700">Average Ticket</p>
                <p id="metric-avg-ticket" class="mt-1 text-3xl font-semibold text-blue-900">${{ number_format($avgTicket, 2) }}</p>
                <p class="mt-1 text-xs text-blue-700">Per completed order</p>
            </article>

            <article class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-amber-700">Catalog</p>
                <p id="metric-products-count" class="mt-1 text-3xl font-semibold text-amber-900">{{ $productsCount }}</p>
                <p class="mt-1 text-xs text-amber-700"><span id="metric-categories-count">{{ $categoriesCount }}</span> categories</p>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
                <h3 class="font-serif text-lg text-espresso">7-Day Revenue Trend</h3>
                <p class="text-xs text-brown-500">Revenue and order volume</p>
                <div class="mt-4 h-72">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </article>

            <article class="rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
                <h3 class="font-serif text-lg text-espresso">Order Status Mix</h3>
                <p class="text-xs text-brown-500">Current operational load</p>
                <div class="mt-4 h-72">
                    <canvas id="statusDonutChart"></canvas>
                </div>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
                <h3 class="font-serif text-lg text-espresso">Top Selling Products</h3>
                <p class="text-xs text-brown-500">By quantity sold</p>
                <div class="mt-4 h-72">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </article>

            <article class="rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
                <h3 class="font-serif text-lg text-espresso">Recent Completed Orders</h3>
                <p class="text-xs text-brown-500">Latest customer handovers</p>
                <div id="recent-completed-list" class="mt-4 space-y-3">
                    @forelse($recentCompleted as $order)
                        <div class="flex items-center justify-between rounded-xl border border-brown-100 bg-white px-3 py-2">
                            <div>
                                <p class="text-sm font-medium text-espresso">Order #{{ $order->id }}</p>
                                <p class="text-[11px] text-brown-500">{{ optional($order->updated_at)->format('d M Y, H:i') }}</p>
                            </div>
                            <p class="text-sm font-semibold text-espresso">${{ number_format((float) $order->total_price, 2) }}</p>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-brown-200 bg-white p-4 text-sm text-brown-500">
                            No completed orders yet.
                        </p>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-brown-100 bg-cream p-3 text-center shadow-sm">
                <p class="text-[11px] uppercase tracking-wide text-brown-500">Pending</p>
                <p id="metric-pending-orders" class="text-2xl font-semibold text-amber-700">{{ $pendingOrders }}</p>
            </div>
            <div class="rounded-xl border border-brown-100 bg-cream p-3 text-center shadow-sm">
                <p class="text-[11px] uppercase tracking-wide text-brown-500">Preparing</p>
                <p id="metric-preparing-orders" class="text-2xl font-semibold text-blue-700">{{ $preparingOrders }}</p>
            </div>
            <div class="rounded-xl border border-brown-100 bg-cream p-3 text-center shadow-sm">
                <p class="text-[11px] uppercase tracking-wide text-brown-500">Ready</p>
                <p id="metric-ready-orders" class="text-2xl font-semibold text-green-700">{{ $readyOrders }}</p>
            </div>
            <div class="rounded-xl border border-brown-100 bg-cream p-3 text-center shadow-sm">
                <p class="text-[11px] uppercase tracking-wide text-brown-500">Staff</p>
                <p id="metric-staff-count" class="text-2xl font-semibold text-espresso">{{ $staffCount }}</p>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($lineLabels);
        const revenueSeries = @json($revenueSeries);
        const ordersSeries = @json($ordersSeries);

        const statusCounts = [
            {{ $pendingOrders }},
            {{ $preparingOrders }},
            {{ $readyOrders }},
            {{ $completedOrders }},
        ];

        const topLabels = @json($topLabels);
        const topSeries = @json($topSeries);

        const dashboardEl = document.getElementById('admin-dashboard');
        const statsUrl = dashboardEl?.dataset?.statsUrl;
        const moneyFormatter = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        let revenueTrendChartInstance;
        let statusDonutChartInstance;
        let topProductsChartInstance;

        const revenueTrendCtx = document.getElementById('revenueTrendChart');
        if (revenueTrendCtx) {
            revenueTrendChartInstance = new Chart(revenueTrendCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Revenue ($)',
                            data: revenueSeries,
                            borderColor: '#8B5E2A',
                            backgroundColor: 'rgba(139, 94, 42, 0.15)',
                            tension: 0.35,
                            fill: true,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Orders',
                            data: ordersSeries,
                            borderColor: '#1C1410',
                            backgroundColor: '#1C1410',
                            tension: 0.3,
                            fill: false,
                            yAxisID: 'y1',
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: { beginAtZero: true },
                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } },
                    },
                },
            });
        }

        const statusDonutCtx = document.getElementById('statusDonutChart');
        if (statusDonutCtx) {
            statusDonutChartInstance = new Chart(statusDonutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Preparing', 'Ready', 'Completed'],
                    datasets: [{
                        data: statusCounts,
                        backgroundColor: ['#F59E0B', '#3B82F6', '#22C55E', '#334155'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: { legend: { position: 'bottom' } },
                },
            });
        }

        const topProductsCtx = document.getElementById('topProductsChart');
        if (topProductsCtx) {
            topProductsChartInstance = new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: topLabels,
                    datasets: [{
                        label: 'Qty Sold',
                        data: topSeries,
                        backgroundColor: '#C8A96E',
                        borderRadius: 8,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } },
                },
            });
        }

        function applyDashboardData(data) {
            setText('metric-total-revenue', `$${moneyFormatter.format(Number(data.total_revenue || 0))}`);
            setText('metric-total-orders', Number(data.total_orders || 0));
            setText('metric-completed-orders', Number(data.completed_orders || 0));
            setText('metric-avg-ticket', `$${moneyFormatter.format(Number(data.avg_ticket || 0))}`);
            setText('metric-products-count', Number(data.products_count || 0));
            setText('metric-categories-count', Number(data.categories_count || 0));
            setText('metric-pending-orders', Number(data.pending_orders || 0));
            setText('metric-preparing-orders', Number(data.preparing_orders || 0));
            setText('metric-ready-orders', Number(data.ready_orders || 0));
            setText('metric-staff-count', Number(data.staff_count || 0));

            if (revenueTrendChartInstance) {
                revenueTrendChartInstance.data.labels = data.line_labels || [];
                revenueTrendChartInstance.data.datasets[0].data = data.revenue_series || [];
                revenueTrendChartInstance.data.datasets[1].data = data.orders_series || [];
                revenueTrendChartInstance.update();
            }

            if (statusDonutChartInstance) {
                statusDonutChartInstance.data.datasets[0].data = [
                    Number(data.pending_orders || 0),
                    Number(data.preparing_orders || 0),
                    Number(data.ready_orders || 0),
                    Number(data.completed_orders || 0),
                ];
                statusDonutChartInstance.update();
            }

            if (topProductsChartInstance) {
                topProductsChartInstance.data.labels = data.top_labels || [];
                topProductsChartInstance.data.datasets[0].data = data.top_series || [];
                topProductsChartInstance.update();
            }

            const recentListEl = document.getElementById('recent-completed-list');
            if (recentListEl) {
                const items = Array.isArray(data.recent_completed) ? data.recent_completed : [];

                if (!items.length) {
                    recentListEl.innerHTML = '<p class="rounded-xl border border-dashed border-brown-200 bg-white p-4 text-sm text-brown-500">No completed orders yet.</p>';
                    return;
                }

                recentListEl.innerHTML = items.map((order) => {
                    const id = Number(order.id || 0);
                    const updatedAt = escapeHtml(order.updated_at || '-');
                    const total = moneyFormatter.format(Number(order.total_price || 0));

                    return `
                        <div class="flex items-center justify-between rounded-xl border border-brown-100 bg-white px-3 py-2">
                            <div>
                                <p class="text-sm font-medium text-espresso">Order #${id}</p>
                                <p class="text-[11px] text-brown-500">${updatedAt}</p>
                            </div>
                            <p class="text-sm font-semibold text-espresso">$${total}</p>
                        </div>
                    `;
                }).join('');
            }
        }

        let refreshTimer;
        async function refreshDashboardLive() {
            if (!statsUrl) return;

            try {
                const response = await window.axios.get(statsUrl);
                applyDashboardData(response.data || {});
            } catch (_) {
                // Ignore transient fetch issues; next websocket event will retry.
            }
        }

        function scheduleRefresh() {
            if (refreshTimer) clearTimeout(refreshTimer);
            refreshTimer = setTimeout(refreshDashboardLive, 150);
        }

        window.addEventListener('load', () => {
            if (!window.Echo) {
                return;
            }

            window.Echo.channel('kitchen.orders')
                .listen('.order.placed', scheduleRefresh);

            window.Echo.channel('orders.status')
                .listen('.order.status.updated', scheduleRefresh);
        });
    </script>
</x-layouts.admin>