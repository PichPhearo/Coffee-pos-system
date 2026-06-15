<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        $readyOrders = Order::where('status', 'ready')->count();

        $totalRevenue = (float) Order::where('status', 'completed')->sum('total_price');
        $avgTicket = $completedOrders > 0 ? $totalRevenue / $completedOrders : 0.0;

        $productsCount = Product::count();
        $categoriesCount = Category::count();
        $staffCount = User::whereIn('role', ['admin', 'cashier', 'barista'])->count();

        $days = collect(range(6, 0))->map(fn($d) => now()->subDays($d));
        $lineLabels = $days->map(fn($day) => $day->format('D'))->values();

        $revenueSeries = $days->map(function ($day) {
            return (float) Order::where('status', 'completed')
                ->whereDate('created_at', $day->toDateString())
                ->sum('total_price');
        })->values();

        $ordersSeries = $days->map(function ($day) {
            return (int) Order::whereDate('created_at', $day->toDateString())->count();
        })->values();

        $topProducts = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as sold_qty'))
            ->groupBy('products.name')
            ->orderByDesc('sold_qty')
            ->limit(5)
            ->get();

        $topLabels = $topProducts->pluck('name')->values();
        $topSeries = $topProducts->pluck('sold_qty')->map(fn($v) => (int) $v)->values();

        $recentCompleted = Order::where('status', 'completed')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'updated_at' => optional($order->updated_at)->format('d M Y, H:i'),
                    'total_price' => (float) $order->total_price,
                ];
            })
            ->values();

        return response()->json([
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
            'preparing_orders' => $preparingOrders,
            'ready_orders' => $readyOrders,
            'total_revenue' => $totalRevenue,
            'avg_ticket' => $avgTicket,
            'products_count' => $productsCount,
            'categories_count' => $categoriesCount,
            'staff_count' => $staffCount,
            'line_labels' => $lineLabels,
            'revenue_series' => $revenueSeries,
            'orders_series' => $ordersSeries,
            'top_labels' => $topLabels,
            'top_series' => $topSeries,
            'recent_completed' => $recentCompleted,
        ]);
    }
}
