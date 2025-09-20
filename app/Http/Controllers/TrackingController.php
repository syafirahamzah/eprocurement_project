<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tracking;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function edit(Order $order)
{

    return view('vendor.tracking.edit', compact('order'));
}

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'estimated_delivery' => 'nullable|date',
        ]);

        $tracking = Tracking::updateOrCreate(
            ['order_id' => $order->id],
            [
                'status' => $request->status,
                'updated_at_status' => now(),
                'estimated_delivery' => $request->estimated_delivery,
            ]
        );

        return redirect()->route('vendor.orders')->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
