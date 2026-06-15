<x-layouts.admin title="Reports" subtitle="Sales and operations insights">
	@php
		$orders = \App\Models\Order::query();
		$today = now()->startOfDay();

		$todayRevenue = (float) (clone $orders)
			->where('status', 'completed')
			->whereDate('updated_at', $today)
			->sum('total_price');

		$todayOrders = (int) (clone $orders)
			->whereDate('created_at', $today)
			->count();

		$completedToday = (int) (clone $orders)
			->where('status', 'completed')
			->whereDate('updated_at', $today)
			->count();

		$completionRate = $todayOrders > 0 ? ($completedToday / $todayOrders) * 100 : 0;

		$paymentBreakdown = \App\Models\Order::query()
			->select('payment_method', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_orders'), \Illuminate\Support\Facades\DB::raw('SUM(total_price) as total_sales'))
			->where('status', 'completed')
			->groupBy('payment_method')
			->orderByDesc('total_sales')
			->get();

		$weeklyDays = collect(range(6, 0))->map(fn ($d) => now()->subDays($d));
		$weeklyLabels = $weeklyDays->map(fn ($d) => $d->format('D'))->values();

		$weeklyRevenue = $weeklyDays->map(function ($d) {
			return (float) \App\Models\Order::where('status', 'completed')
				->whereDate('updated_at', $d->toDateString())
				->sum('total_price');
		})->values();

		$topItems = \Illuminate\Support\Facades\DB::table('order_items')
			->join('products', 'products.id', '=', 'order_items.product_id')
			->select('products.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as qty'))
			->groupBy('products.name')
			->orderByDesc('qty')
			->limit(8)
			->get();

		$topItemLabels = $topItems->pluck('name')->values();
		$topItemSeries = $topItems->pluck('qty')->map(fn ($q) => (int) $q)->values();

		$statusSummary = [
			'pending' => \App\Models\Order::where('status', 'pending')->count(),
			'preparing' => \App\Models\Order::where('status', 'preparing')->count(),
			'ready' => \App\Models\Order::where('status', 'ready')->count(),
			'completed' => \App\Models\Order::where('status', 'completed')->count(),
		];
	@endphp

	<div class="space-y-6">
		<section class="rounded-2xl border border-brown-100 bg-gradient-to-r from-cream via-brown-50 to-cream p-6 shadow-sm">
			<div class="flex items-center justify-between gap-3 flex-wrap">
				<div>
					<p class="text-xs uppercase tracking-[0.18em] text-brown-500">Report Snapshot</p>
					<h2 class="mt-1 font-serif text-2xl text-espresso">Performance Overview</h2>
					<p class="mt-1 text-sm text-brown-600">A compact view of sales momentum, order flow, and payment mix.</p>
				</div>
				<div class="rounded-xl bg-espresso px-4 py-2 text-right">
					<p class="text-[11px] uppercase tracking-wide text-crema">Updated</p>
					<p class="text-sm font-medium text-cream">{{ now()->format('d M Y, H:i') }}</p>
				</div>
			</div>
		</section>

		<section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
			<article class="rounded-2xl border border-brown-100 bg-cream p-4 shadow-sm">
				<p class="text-xs uppercase tracking-wider text-brown-500">Revenue Today</p>
				<p class="mt-1 text-3xl font-semibold text-espresso">${{ number_format($todayRevenue, 2) }}</p>
			</article>
			<article class="rounded-2xl border border-blue-200 bg-blue-50 p-4 shadow-sm">
				<p class="text-xs uppercase tracking-wider text-blue-700">Orders Today</p>
				<p class="mt-1 text-3xl font-semibold text-blue-900">{{ $todayOrders }}</p>
			</article>
			<article class="rounded-2xl border border-green-200 bg-green-50 p-4 shadow-sm">
				<p class="text-xs uppercase tracking-wider text-green-700">Completed Today</p>
				<p class="mt-1 text-3xl font-semibold text-green-900">{{ $completedToday }}</p>
			</article>
			<article class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
				<p class="text-xs uppercase tracking-wider text-amber-700">Completion Rate</p>
				<p class="mt-1 text-3xl font-semibold text-amber-900">{{ number_format($completionRate, 1) }}%</p>
			</article>
		</section>

		<section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
			<article class="xl:col-span-2 rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
				<h3 class="font-serif text-lg text-espresso">7-Day Revenue</h3>
				<p class="text-xs text-brown-500">Completed-order revenue trend</p>
				<div class="mt-4 h-72">
					<canvas id="reportRevenueChart"></canvas>
				</div>
			</article>

			<article class="rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
				<h3 class="font-serif text-lg text-espresso">Order Status Mix</h3>
				<p class="text-xs text-brown-500">Current queue composition</p>
				<div class="mt-4 h-72">
					<canvas id="reportStatusChart"></canvas>
				</div>
			</article>
		</section>

		<section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
			<article class="rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
				<h3 class="font-serif text-lg text-espresso">Top Products</h3>
				<p class="text-xs text-brown-500">Best sellers by quantity</p>
				<div class="mt-4 h-72">
					<canvas id="reportTopItemsChart"></canvas>
				</div>
			</article>

			<article class="rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
				<h3 class="font-serif text-lg text-espresso">Payment Breakdown</h3>
				<p class="text-xs text-brown-500">Completed orders by payment method</p>

				<div class="mt-4 overflow-hidden rounded-xl border border-brown-100">
					<table class="min-w-full text-sm">
						<thead class="bg-brown-50 text-brown-600">
							<tr>
								<th class="px-3 py-2 text-left font-semibold">Method</th>
								<th class="px-3 py-2 text-right font-semibold">Orders</th>
								<th class="px-3 py-2 text-right font-semibold">Sales</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-brown-100 bg-white">
							@forelse($paymentBreakdown as $row)
								<tr>
									<td class="px-3 py-2 font-medium uppercase text-espresso">{{ $row->payment_method }}</td>
									<td class="px-3 py-2 text-right text-brown-700">{{ (int) $row->total_orders }}</td>
									<td class="px-3 py-2 text-right text-brown-700">${{ number_format((float) $row->total_sales, 2) }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="3" class="px-3 py-6 text-center text-brown-500">No payment data yet.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</article>
		</section>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
		const reportRevenueCtx = document.getElementById('reportRevenueChart');
		if (reportRevenueCtx) {
			new Chart(reportRevenueCtx, {
				type: 'line',
				data: {
					labels: @json($weeklyLabels),
					datasets: [{
						label: 'Revenue ($)',
						data: @json($weeklyRevenue),
						borderColor: '#8B5E2A',
						backgroundColor: 'rgba(139, 94, 42, 0.18)',
						fill: true,
						tension: 0.35,
					}],
				},
				options: {
					maintainAspectRatio: false,
					plugins: { legend: { position: 'bottom' } },
					scales: { y: { beginAtZero: true } },
				},
			});
		}

		const reportStatusCtx = document.getElementById('reportStatusChart');
		if (reportStatusCtx) {
			new Chart(reportStatusCtx, {
				type: 'doughnut',
				data: {
					labels: ['Pending', 'Preparing', 'Ready', 'Completed'],
					datasets: [{
						data: [
							{{ $statusSummary['pending'] }},
							{{ $statusSummary['preparing'] }},
							{{ $statusSummary['ready'] }},
							{{ $statusSummary['completed'] }},
						],
						backgroundColor: ['#F59E0B', '#3B82F6', '#22C55E', '#334155'],
						borderWidth: 0,
					}],
				},
				options: {
					maintainAspectRatio: false,
					cutout: '66%',
					plugins: { legend: { position: 'bottom' } },
				},
			});
		}

		const reportTopItemsCtx = document.getElementById('reportTopItemsChart');
		if (reportTopItemsCtx) {
			new Chart(reportTopItemsCtx, {
				type: 'bar',
				data: {
					labels: @json($topItemLabels),
					datasets: [{
						label: 'Qty Sold',
						data: @json($topItemSeries),
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
	</script>
</x-layouts.admin>
