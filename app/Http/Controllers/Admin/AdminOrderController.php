<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function list()
    {
        $orders = Order::with(['product', 'customer', 'vendor', 'tracking'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['product', 'customer', 'vendor', 'tracking'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
}
