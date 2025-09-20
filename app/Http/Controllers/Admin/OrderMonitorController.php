<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderMonitorController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product', 'customer', 'vendor', 'tracking'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.monitoring.index', compact('orders'));
    }
}
