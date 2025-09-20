<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Vendor;
use App\Events\MessageSent;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function multiVendor()
    {
        $user = Auth::user();
        return view('customer.obrolan-multivendor', compact('user'));
    }

    // Ambil semua pesan antara customer yang login dengan vendor tertentu
    public function fetchMessages(Vendor $vendor)
    {
        $customer = Auth::user();

        // Pastikan customer sudah login
        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $messages = Message::where('customer_id', $customer->id)
            ->where('vendor_id', $vendor->id)
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    // Kirim pesan dari customer ke vendor
    public function sendMessage(Request $request)
    {
        $customer = Auth::user();

        if (!$customer) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'customer_id' => $customer->id,
            'vendor_id' => $request->vendor_id,
            'message' => $request->message,
            'from_customer' => true,
        ]);

        // Broadcast event agar realtime
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}
