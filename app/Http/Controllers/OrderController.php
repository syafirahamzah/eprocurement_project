<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Negotiation;
use App\Models\Tracking;
use App\Models\Notification;
use App\Models\AkadDokumen;
use App\Models\Agreement;
use App\Observers\OrderObserver;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function orders(Request $request)
{
    $type = $request->query('type');

    $query = Order::with('negotiation')
                  ->where('customer_id', Auth::id()); // hanya pesanan milik customer yang login

    if ($type === 'negotiation') {
        $query->whereNotNull('negotiation_id');
    } elseif ($type === 'regular') {
        $query->whereNull('negotiation_id');
    }

    $orders = $query->get();

    return view('customer.orders', compact('orders'));
}



public function placeOrder($orderId)
{
    $order = Order::findOrFail($orderId);

    // Cek status negosiasi
    if (!$order->negotiation || $order->negotiation->status != 'disetujui') {
    return redirect()->route('customer.orders')->with('error', 'Negosiasi belum disetujui.');
}

    // Update status pesanano
    $order->status = 'diproses';
    $order->save();

    // Arahkan ke halaman konfirmasi
    return redirect()->route('customer.order.confirmation', ['order' => $order->id]);
}

    // Menampilkan semua pesanan
    public function showOrders()
{
    $orders = Order::where('customer_id', Auth::id())->get();
    return view('customer.orders.index', compact('orders'));
}



public function store(Request $request, Negotiation $negotiation)
{
 
    $request->validate([
        'product_id' => 'required',
        'quantity' => 'required|integer|min:1',
         'agreed_price'     => 'required|numeric|min:1',
         'payment_method' => 'required|in:akad,cod',
       'akad_document' => 'required|mimes:pdf|max:2048',
         'agreement_document' => 'required_if:payment_method,akad|file|mimes:pdf',
         'akad_file' => 'required_if:payment_method,akad|mimes:pdf|max:2048',
         'ktp_document' => 'required_if:payment_method,akad|mimes:jpg,jpeg,png|max:2048',

]);
    

if (!$request->filled('negotiation_id')) {
    return back()->withErrors(['negotiation_id' => 'Negosiasi tidak ditemukan.']);
}

    // Ambil data negosiasi
    $negotiation = Negotiation::findOrFail($request->negotiation_id);


    // Validasi kepemilikan dan status negosiasi
    if ($negotiation->customer_id !== Auth::id()) {
        return back()->with('error', 'Anda tidak berhak membuat pesanan ini.');
    }
    if ($negotiation->status !== 'disetujui') {
        return back()->with('error', 'Negosiasi belum disetujui oleh vendor.');
    }
    if ($negotiation->order) {
        return redirect()->route('customer.orders')->with('info', 'Pesanan sudah dibuat sebelumnya.');
    }


$akadPath = null;
$ktpPath = null;
    

if ($request->payment_method === 'akad') {
    $akadPath = $request->file('akad_file')->store('akad_documents', 'public');
    $ktpPath = $request->file('ktp_document')->store('ktp_files', 'public');
}


    // Buat pesanan awal
    $order = Order::create([
        'negotiation_id'     => $negotiation->id,
        'product_id'         => $negotiation->product_id,
        'vendor_id'          => $negotiation->vendor_id,
        'customer_id'        => $negotiation->customer_id,
        'quantity' => $request->quantity,
        'agreed_price' => $negotiation->final_price ?? $negotiation->product->price,
        'status' => 'menunggu konfirmasi',
        'user_id'            => Auth::id(),
        'shipping_address'   => $negotiation->customer->address,
        'payment_method'     => $request->payment_method,
        'akad_document'      => $akadPath,
        'ktp_file'           => $ktpPath,
        'payment_status' => 'belum',
    ]);

    $negotiation->order_id = $order->id;
    $negotiation->save();

    Tracking::create([
        'order_id' => $order->id,
        'status' => Tracking::STATUS_DIPROSES,
        'estimated_delivery' => now()->addDays(3),
    ]);
   
    // Notifikasi
    Notification::create([
        'user_id' => Auth::id(),
        'title' => 'Pesanan Berhasil',
        'message' => 'Pesanan Anda dengan harga negosiasi berhasil dikirim ke vendor.',
        'is_read' => false,
        'type' => 'order',
        'product_id' => $negotiation->product_id,
        'negotiation_id' => $negotiation->id,
        'link_redirect' => route('customer.orders'),
    
    ]);

    Notification::create([
    'user_id' => $order->vendor_id,
    'title' => 'Pesanan Baru Masuk',
    'message' => 'Anda menerima pesanan dari ' . Auth::user()->name,
    'is_read' => false,
    'type' => 'order',
    'product_id' => $order->product_id,
    'negotiation_id' => $order->negotiation_id,
    'link_redirect' => route('vendor.orders.index'),
]);



// Buat dokumen akad jika metode pembayaran 'akad'
if ($request->payment_method === 'akad') {
    $akad = AkadDokumen::create([
        'order_id'        => $order->id,
        'negotiation_id'  => $negotiation->id,
        'akad_file' => $akadPath,
        'ktp_path' => $ktpPath,
        'is_valid'        => false,
    ]);

}

    return redirect()->route('customer.orders')->with('success', 'Pesanan berhasil dibuat.');
}



public function confirmation($orderId)
{
    $order = Order::findOrFail($orderId);
    return view('customer.confirmation', compact('order'));
}


private function handleAgreementUpload(Request $request, Order $order)
{
    if ($request->hasFile('agreement_document')) {
        $path = $request->file('agreement_document')->store('documents', 'public');
        if ($path) {
            $order->agreement_document = $path;
        }
    }
    if ($request->hasFile('ktp_document')) {
        $path = $request->file('ktp_document')->store('ktp', 'public');
        $order->ktp_file = $path; // Simpan ke kolom di tabel order
    }
}


public function directOrder(Request $request, $product_id)
{
    // Validasi awal
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'total_price' => 'required|numeric|min:1',
        'payment_method' => 'required|in:akad,cod',
        'akad_file' => 'required_if:payment_method,akad|file|mimes:pdf|max:2048',
        'ktp_document' => 'required_if:payment_method,akad|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $product = Product::findOrFail($product_id);
    $agreedPrice = $product->price * $request->quantity;

    // Siapkan path dokumen (jika ada)
    $ktpPath = null;
    $akadPath = null;

    if ($request->payment_method === 'akad') {
        if ($request->hasFile('ktp_document')) {
            $ktpPath = $request->file('ktp_document')->store('ktp_files', 'public');
        } else {
            return back()->with('error', 'Upload KTP wajib.');
        }

        if ($request->hasFile('akad_file')) {
            $akadPath = $request->file('akad_file')->store('akad_files', 'public');
        } else {
            return back()->with('error', 'Upload dokumen akad wajib.');
        }
    }

    // Simpan order
    $order = Order::create([
        'product_id'        => $product->id,
        'vendor_id'         => $product->vendor_id,
        'customer_id'       => Auth::id(),
        'quantity'          => $request->quantity,
        'agreed_price'      => $agreedPrice,
        'status'            => 'menunggu konfirmasi',
        'shipping_address'  => Auth::user()->address ?? 'Belum diisi',
        'user_id'           => Auth::id(),
        'payment_method'    => $request->payment_method,
        'payment_status'    => 'belum',
        'ktp_file'          => $ktpPath,
        'akad_document'     => $akadPath,
    ]);



    // Simpan dokumen akad jika metode 'akad'
    if ($request->payment_method === 'akad') {
        AkadDokumen::create([
            'order_id'      => $order->id,
            'akad_file'     => $akadPath,
            'ktp_path'      => $ktpPath,
            'is_valid'      => false,
        ]);
    }

    // Upload dokumen perjanjian jika ada
    $this->handleAgreementUpload($request, $order);

    // Simpan tracking
    Tracking::create([
        'order_id' => $order->id,
        'status' => Tracking::STATUS_DIPROSES,
        'estimated_delivery' => now()->addDays(3),
    ]);

    // Notifikasi ke customer
    Notification::create([
        'user_id' => Auth::id(),
        'title' => 'Pesanan Diterima',
        'message' => 'Pesanan Anda telah dikirim ke vendor.',
        'is_read' => false,
        'type' => 'order',
        'product_id' => $product->id,
    ]);

    // Notifikasi ke vendor
    Notification::create([
        'user_id' => $order->vendor_id,
        'title' => 'Pesanan Langsung Masuk',
        'message' => 'Anda menerima pesanan langsung dari ' . Auth::user()->name,
        'is_read' => false,
        'type' => 'order',
        'product_id' => $order->product_id,
        'negotiation_id' => null,
        'link_redirect' => route('vendor.orders.index'),
    ]);

    return redirect()->route('customer.orders')->with('success', 'Pesanan berhasil dibuat tanpa negosiasi.');
}


public function destroy(Order $order)
{
    if (auth()->id() !== $order->customer_id) {
        abort(403);
    }

    $order->delete();
    return redirect()->route('customer.orders')->with('success', 'Pesanan berhasil dihapus.');
}

public function confirmReceived(Order $order)
{
    if ($order->customer_id !== auth()->id()) {
        abort(403, 'Kamu tidak punya akses untuk mengkonfirmasi pesanan ini.');
    }

    $order->status = 'selesai';
    $order->payment_status = 'lunas'; // Update di sini

    if ($order->tracking) {
        $order->tracking->status = 'selesai';
        $order->tracking->save();
    }

    $order->save();

    return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi.');
}







public function monitoring()
{
    $orders = Order::with(['product', 'customer', 'tracking'])
        ->where('vendor_id', Auth::id())
        ->whereHas('tracking', function ($query) {
            $query->where('status', 'dikirim')
                  ->whereDate('updated_at', now()->toDateString()); // HANYA hari ini
        })
        ->orderByDesc('created_at')
        ->get();

    return view('vendor.monitoring', compact('orders'));
}


public function monitoringCustomer()
{
    $orders = Order::with(['product', 'vendor', 'tracking'])
        ->where('customer_id', Auth::id())
        ->whereHas('tracking', function ($q) {
            $q->whereIn('status', ['diproses', 'dikirim']);
        })
        ->orderByDesc('created_at')
        ->get();

    return view('customer.monitoring', compact('orders'));
}



public function orderHistory()
{
    $orders = Order::with(['product', 'vendor', 'tracking'])
        ->where('customer_id', auth()->id())
        ->whereIn('status', ['dikirim', 'selesai'])
        ->orderByDesc('created_at')
        ->get();

    return view('customer.history', compact('orders'));
}










    // Buat pesanan dari hasil negosiasi
    public function create($negotiation_id)
    {
        $negotiation = Negotiation::with('product')->findOrFail($negotiation_id);

        $order = Order::create([
            'negotiation_id' => $negotiation->id,
            'product_id' => $negotiation->product_id,
            'vendor_id' => $negotiation->vendor_id,
            'customer_id' => $negotiation->customer_id,
            'quantity' => 1,
            'agreed_price' => $negotiation->final_price ?? $negotiation->product->price,
            'status' => 'menunggu konfirmasi',
        ]);

        return redirect()->route('customer.orders')->with('success', 'Pesanan berhasil dibuat dari hasil negosiasi.');
    }
    

public function show($id)
{
    $order = Order::with(['product', 'customer', 'tracking', 'akadDokumen'])->findOrFail($id);

    if ($order->vendor_id !== Auth::id()) {
        return redirect()->route('vendor.pesanan-masuk')->with('error', 'Anda tidak berhak melihat pesanan ini.');
    }

    return view('vendor.orders.show', compact('order'));
}

    
    

   //ini untuk ubah status pesanan
    public function cancel($order_id)
    {
        $order = Order::findOrFail($order_id);

        if ($order->customer_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Hanya boleh dibatalkan jika status masih "menunggu konfirmasi"
        if ($order->status !== 'menunggu konfirmasi') {
            return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        $order->status = 'dibatalkan';
        $order->save();

        return redirect()->route('customer.orders')->with('success', 'Pesanan berhasil dibatalkan.');
    }

   

// Relasi dengan Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi dengan Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relasi dengan Tracking
    public function tracking()
    {
        return $this->hasOne(Tracking::class); // Pastikan ini sesuai dengan struktur relasi di database
    }
}
