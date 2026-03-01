<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('orders.my', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        if ($request->user()->isCustomer() && $order->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak punya akses ke pesanan ini.');
        }

        $order->load(['user', 'items.product']);

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,packed,done,cancelled'],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }
}
