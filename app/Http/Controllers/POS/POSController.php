<?php

namespace App\Http\Controllers\POS;

use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();

        return view('pos.menu', compact('categories', 'products'));
    }

    public function orders(Request $request)
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', $request->user()->id)
            ->where('status', '!=', 'completed')
            ->orderBy('id')
            ->get();

        return view('pos.orders', compact('orders'));
    }

    public function history(Request $request)
    {
        $validated = $request->validate([
            'period' => ['nullable', 'in:day,week,month'],
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $period = $validated['period'] ?? 'day';
        $selectedDate = $validated['date'] ?? null;

        $query = Order::with(['items.product'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'completed');

        if ($selectedDate) {
            $query->whereDate('updated_at', $selectedDate);
        } elseif ($period === 'day') {
            $query->whereDate('updated_at', now()->toDateString());
        } elseif ($period === 'week') {
            $query->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereBetween('updated_at', [now()->startOfMonth(), now()->endOfMonth()]);
        }

        $orders = $query->orderByDesc('updated_at')->get();

        return view('pos.history', compact('orders', 'period', 'selectedDate'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,card,ewallet'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.size' => ['nullable', 'string', 'max:20'],
            'items.*.sugar_level' => ['nullable', 'string', 'max:30'],
            'items.*.note' => ['nullable', 'string', 'max:500'],
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $productIds = collect($validated['items'])->pluck('product_id')->unique()->values();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $total = 0;
            $lineItems = [];

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    abort(422, 'Invalid product in cart.');
                }

                $linePrice = (float) $product->price;
                $quantity = (int) $item['quantity'];
                $total += $linePrice * $quantity;

                $lineItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $linePrice,
                    'size' => isset($item['size']) ? trim((string) $item['size']) : null,
                    'sugar_level' => isset($item['sugar_level']) ? trim((string) $item['sugar_level']) : null,
                    'note' => isset($item['note']) ? trim((string) $item['note']) : null,
                ];
            }

            $order = new Order();
            $order->user_id = $request->user()->id;
            $order->total_price = $total;
            $order->payment_method = $validated['payment_method'];
            $order->status = 'pending';
            $order->save();

            foreach ($lineItems as $lineItem) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $lineItem['product_id'];
                $orderItem->quantity = $lineItem['quantity'];
                $orderItem->price = $lineItem['price'];
                $orderItem->size = $lineItem['size'] !== '' ? $lineItem['size'] : null;
                $orderItem->sugar_level = $lineItem['sugar_level'] !== '' ? $lineItem['sugar_level'] : null;
                $orderItem->note = $lineItem['note'] !== '' ? $lineItem['note'] : null;
                $orderItem->save();
            }

            return $order;
        });

        OrderPlaced::dispatch($order->id);

        return response()->json([
            'message' => 'Order placed successfully.',
            'order_id' => $order->id,
        ]);
    }
}
