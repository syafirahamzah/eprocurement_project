<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\RequestModel;
use App\Models\User;
use App\Models\Notification;
use App\Models\Tracking;
use App\Models\AkadDokumen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\OrderReceivedNotification;
use Barryvdh\DomPDF\Facade\Pdf;


class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:customer']);
    }

    // Halaman Dashboard Customer

public function dashboard()
{
    $userId = auth()->id();

    $totalOrders = Order::where('customer_id', $userId)->count();

    $shippedOrders = Order::where('customer_id', $userId)
        ->whereHas('tracking', fn($q) => $q->where('status', 'dikirim'))
        ->count();

    $completedOrders = Order::where('customer_id', $userId)
        ->whereHas('tracking', fn($q) => $q->where('status', 'selesai'))
        ->count();

    return view('customer.dashboard', compact(
        'totalOrders',
        'shippedOrders',
        'completedOrders'
    ));
}


// Menampilkan Profil Customer
    public function showProfile()
{
    $customer = Auth::user();
    return view('customer.profile', compact('customer'));
}

public function update(Request $request)
{
    $customer = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $customer->id,
        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
    ]);

    $customer->update($validated);

    return back()->with('success', 'Profil berhasil diperbarui.');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $customer = Auth::user();

    if (!Hash::check($request->current_password, $customer->password)) {
        return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
    }

    $customer->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('password_success', 'Password berhasil diperbarui.');
}



// Menampilkan Daftar Vendor untuk Pemilihan Vendor
    public function showVendors()
    {
        $vendors = User::where('role', 'vendor')->get();
        
        return view('customer.vendor_select', compact('vendors'));
    }

    // Menyimpan Pemilihan Vendor
    public function selectVendor(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
        ]);

        // Simpan vendor_id ke session
        session(['selected_vendor_id' => $request->vendor_id]);

        return redirect()->route('customer.eCatalog', ['vendor_id' => $request->vendor_id]);
    }


    
    public function eCatalog(Request $request)
{
    // Ambil ID vendor dari request atau session
    $vendor_id = $request->vendor_id ?? session('selected_vendor_id');

    // Validasi: pastikan ID valid dan role-nya 'vendor'
    if (!$vendor_id || !User::where('id', $vendor_id)->where('role', 'vendor')->exists()) {
        return redirect()->route('customer.vendor.select')->with('error', 'Vendor tidak valid.');
    }

    // Ambil data vendor beserta produknya
    $vendor = User::find($vendor_id);
    $products = $vendor->products ?? [];

    // Simpan ID vendor ke session biar persist
    session(['selected_vendor_id' => $vendor_id]);

    return view('customer.eCatalog', compact('vendor', 'products'));
}




    // Menampilkan Daftar Pesanan Customer
    public function orders()
{
    $orders = Order::with(['product', 'vendor'])  // include relasi
                ->where('customer_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

    return view('customer.orders', compact('orders'));
}


    public function showProductDetail($id)
{
    $product = Product::with('vendor')->findOrFail($id);
    return view('customer.product_detail', compact('product'));
}


    // Mengonfirmasi Pesanan sebagai Selesai
    public function confirmReceive($order_id)
{
    $order = Order::findOrFail($order_id);

    // Pastikan status pengiriman adalah 'Dikirim'
    if ($order->status_pengiriman === 'Dikirim') {
        $order->status_pengiriman = 'Diterima';  // Ubah status menjadi Diterima
        $order->save();

        // Kirim notifikasi ke vendor
        $vendor = $order->vendor;
        $vendor->notify(new OrderReceivedNotification($order)); // Notifikasi vendor

        // Redirect ke halaman pesanan dengan pesan sukses
        return redirect()->route('customer.orders')->with('success', 'Barang telah diterima');
    } else {
        return redirect()->route('customer.orders')->with('error', 'Barang belum dikirim');
    }
}

public function showECatalog()
{
    $products = Product::all();
    return view('customer.eCatalog', compact('products'));
}





    // Menampilkan Halaman Monitoring Pengiriman Barang
   public function monitoring(Request $request)
{
    $customer = Auth::user();

    $trackings = Tracking::with('order.product') // Pastikan relasi dengan produk sudah benar
        ->whereHas('order', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        });

    // Filter berdasarkan status pengiriman
    if ($request->has('status') && in_array($request->status, ['dikirim', 'dalam perjalanan', 'diterima'])) {
        $trackings->where('status', $request->status);
    }

    // Mengambil data setelah filter
    $trackings = $trackings->get();

    \Log::info('Trackings:', $trackings->toArray()); // Log data tracking untuk debugging

    return view('customer.monitoring', compact('trackings'));
}

 public function uploadKtp(Request $request)
{
    $request->validate([
        'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $file = $request->file('ktp');
    $path = $file->store('ktp', 'public');

    $user = auth()->user();
    $user->ktp_path = $path;
    $user->save();

    return back()->with('success', 'KTP berhasil diunggah.');
}

public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required',
        'quantity' => 'required|integer|min:1',
        'payment_method' => 'required',
        'agree' => 'accepted', // checkbox persetujuan
    ]);

    $product = Product::findOrFail($request->product_id);

    $order = Order::create([
        'customer_id' => auth()->id(),
        'product_id' => $product->id,
        'vendor_id'     => $product->vendor_id, 
        'quantity' => $request->quantity,
        'total_price' => $product->price * $request->quantity,
        'payment_method' => $request->payment_method,
        'payment_status' => 'belum',
        'status' => 'Menunggu Akad',
    ]);

    if ($request->payment_method === 'akad_kasbon') {
    $pdf = Pdf::loadView('pdf.akad', [
        'order' => $order,
        'customer' => auth()->user(),
        'vendor' => $product->vendor,
    ]);

    $filename = 'akad_kasbon_'.$order->id.'.pdf';
    $filePath = 'agreements/'.$filename;

    // Simpan ke storage/public/agreements
    \Storage::disk('public')->put($filePath, $pdf->output());

    // Simpan path ke database
    $order->kasbon_agreement_path = $filePath;
    $order->save();
}

    return redirect()->route('customer.orders.index')->with('success', 'Pesanan berhasil dibuat.');
}

public function printAkad($orderId)
    {
        
        $order = Order::with(['customer', 'product.vendor'])->findOrFail($orderId);

        $product = $order->product; // pastikan relasi ada di model Order

$pdf = Pdf::loadView('pdf.akad', [
    'order' => $order,
    'customer' => auth()->user(),
    'vendor' => $product->vendor,
    'product' => $product,
]);


        return $pdf->download('Surat-Perjanjian-Akad-' . $order->id . '.pdf');
    }

public function uploadAkadDokumen(Request $request, $orderId)
{
    $request->validate([
        'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akad' => 'required|file|mimes:pdf|max:2048',
    ]);

    $ktpPath = $request->file('ktp')->store('dokumen', 'public');
    $akadPath = $request->file('akad')->store('dokumen', 'public');

    AkadDokumen::updateOrCreate(
        ['order_id' => $orderId],
        [
            'ktp_path' => $ktpPath,
            'akad_file' => $akadPath
        ]
    );

    return back()->with('success', 'Dokumen berhasil diupload.');
}


}
