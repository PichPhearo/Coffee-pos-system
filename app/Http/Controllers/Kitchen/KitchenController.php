<?php

namespace App\Http\Controllers\Kitchen;

use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('status', '!=', 'completed')
            ->orderBy('id')
            ->get();

        $completedCount = Order::where('status', 'completed')->count();

        return view('kitchen.index', compact('orders', 'completedCount'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:preparing,ready,completed'],
        ]);

        $nextStatus = $validated['status'];
        $allowedTransitions = [
            'pending' => ['preparing'],
            'preparing' => ['ready'],
            'ready' => ['completed'],
        ];

        $currentStatus = (string) $order->status;
        $canTransition = in_array($nextStatus, $allowedTransitions[$currentStatus] ?? [], true);

        if (! $canTransition) {
            return back()->withErrors(['status' => 'Invalid order status transition.']);
        }

        $order->status = $nextStatus;
        $order->save();

        OrderStatusUpdated::dispatch($order->id, $order->status);

        return redirect()->route('kitchen.index');
    }
}
