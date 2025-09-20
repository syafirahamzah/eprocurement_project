<?php 

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
 
public function vendorIndex()
{
    $notifications = Notification::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();

    return view('vendor.notifications.index', compact('notifications'));
}

public function markAsRead($id)
{
    $notification = Notification::where('id', $id)
        ->where('user_id', Auth::id())
        ->first();

    if ($notification) {
        $notification->update(['is_read' => true]);

        if (Auth::user()->hasRole('vendor')) {
            return redirect()->route('vendor.notifications.index')->with('success', 'Notifikasi berhasil dibaca.');
        }

        return redirect()->route('customer.notifications')->with('success', 'Notifikasi berhasil dibaca.');
    }

    return back()->with('error', 'Notifikasi tidak ditemukan.');
}






    
    public function vendorMarkAsRead($id)
{
    $notification = Notification::where('id', $id)
        ->where('user_id', Auth::id())  // Pastikan vendor hanya bisa menandai notifikasinya sendiri
        ->first();

    if ($notification) {
        $notification->update(['is_read' => true]);

        return redirect()->route('vendor.notifications.index')->with('success', 'Notifikasi berhasil dibaca.');
    }

    return redirect()->route('vendor.notifications.index')->with('error', 'Notifikasi tidak ditemukan.');
}


    public function vendorSendNotification(Request $request, $customerId)
    {
        // Pastikan hanya vendor yang dapat mengirim notifikasi
        if (!Auth::user()->hasRole('vendor')) {
            return redirect()->route('vendor.dashboard')->with('error', 'Anda bukan vendor!');
        }

        // Mengirim notifikasi ke customer tertentu
        $notification = Notification::create([
            'user_id' => $customerId,  
            'title' => 'Pesanan Sedang Dikirim',
            'message' => 'Pesanan Anda telah dikirim dan sedang dalam perjalanan.',
            'is_read' => false, // Set notifikasi belum dibaca
        ]);

        return redirect()->route('vendor.notifications.index')->with('success', 'Notifikasi berhasil dikirim.');
    }


/**
     * Menampilkan notifikasi untuk customer dan vendor
     */
    public function index()
{
    $notifications = Notification::with(['negotiation.order'])
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();

    // Tentukan view berdasarkan role (vendor atau customer)
    $view = (Auth::user()->hasRole('vendor')) 
        ? 'vendor.notifications' 
        : 'customer.notifications';

    return view($view, compact('notifications'));
}

}

