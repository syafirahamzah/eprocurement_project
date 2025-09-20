<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestModel;
use App\Models\Negotiation;
use App\Models\NegotiationMessage;
use App\Models\Product;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Tracking;
use App\Models\Notification;
use App\Models\AkadDokumen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:vendor']);
    }

    // Dashboard
    public function dashboard()
{
    $vendorId = auth()->id();

    $totalProducts = Product::where('vendor_id', $vendorId)->count();
    $totalOrders = Order::where('vendor_id', $vendorId)->count();
    $processingOrders = Order::where('vendor_id', $vendorId)
                             ->whereHas('tracking', fn($q) => $q->where('status', 'dikirim'))
                             ->count();
    $completedOrders = Order::where('vendor_id', $vendorId)
                            ->whereHas('tracking', fn($q) => $q->where('status', 'selesai'))
                            ->count();

    $unreadCount = Notification::where('user_id', $vendorId)->where('is_read', false)->count();
    $recentNotifications = Notification::where('user_id', $vendorId)
                                   ->latest()
                                   ->take(5)
                                   ->get();



    return view('vendor.dashboard', compact(
        'totalProducts',
        'totalOrders',
        'processingOrders',
        'completedOrders',
        'recentNotifications',
        'unreadCount',
    ));
}

public function markPaid($orderId)
{
    $order = Order::findOrFail($orderId);
    $order->status = 'Lunas';
    $order->save();

    return redirect()->back()->with('success', 'Pesanan ditandai sebagai lunas.');
}

public function invoice($id)
{
    $order = Order::with('product', 'customer')->findOrFail($id);

    $akad = $order->akad; // Asumsikan relasinya benar

return view('vendor.orders.invoice', compact('order', 'akad'));
}



    // Profil
    public function profile()
{
    return view('vendor.profile');
}

public function edit()
{
    $vendor = Auth::user(); // atau sesuai logic login vendor
    return view('vendor.profile', compact('vendor'));
}



public function update(Request $request)
{
    $vendor = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $vendor->id,
        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'category' => 'nullable|string|max:255',
    ]);

    $vendor->update($validated);

    return back()->with('success', 'Profil berhasil diperbarui.');
}



public function updatePassword(Request $request)
{
    $vendor = Auth::user();

    $request->validate([
        'current_password' => ['required', 'current_password'],
        'new_password' => ['required', 'min:8', 'confirmed'],
    ]);

    $vendor->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('password_success', 'Password berhasil diperbarui.');
}


   

    public function createProduct()
    {
        return view('vendor.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'vendor_id' => Auth::id(),
        ]);

        return redirect()->route('vendor.products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function editProduct(Product $product)
    {
        return view('vendor.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($request->only(['name', 'description', 'price']));

        return redirect()->route('vendor.products')->with('success', 'Produk berhasil diperbarui.');
    }

    
public function destroy($id)
{
    $product = Product::findOrFail($id);

    // Hapus semua orders langsung (jika ada relasi langsung ke produk)
    $product->orders()->delete();

    // Hapus semua negosiasi dan order dalam negosiasi
    foreach ($product->negotiations as $negotiation) {
        $negotiation->orders()->delete();  // pastikan relasi orders() ada di Negotiation
        $negotiation->delete();
    }

    $product->delete();

    return redirect()->route('vendor.products.index')
        ->with('success', 'Produk berhasil dihapus beserta data terkaitnya.');
}


public function requestHistory(Request $request)

{
    $query = Order::with(['product', 'customer', 'tracking', 'akadDokumen'])
        ->where('vendor_id', auth()->id());

    if ($request->filled('search')) {
        $query->whereHas('product', fn($q) =>
            $q->where('name', 'like', '%' . $request->search . '%'))
            ->orWhereHas('customer', fn($q) =>
            $q->where('name', 'like', '%' . $request->search . '%'));
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $orders = $query->orderByDesc('created_at')->get();

    return view('vendor.history', compact('orders'));
}



    // Daftar permintaan
    public function requests()
    {
        $vendor = Auth::user();

        $requests = RequestModel::with('product', 'customer')
            ->where('vendor_id', $vendor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('vendor.requests', compact('requests'));
    }

   

// Negosiasi
    public function negotiations()
    {
        $vendor = Auth::user();

        $negotiations = Negotiation::with('product', 'customer')
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->get();

        $newMessageCount = NegotiationMessage::where('sender_role', 'customer')
            ->whereHas('negotiation', function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->where('is_read', false)
            ->count();

        return view('vendor.negotiation.index', compact('negotiations', 'newMessageCount'));
    }


public function approveNegotiation(Request $request, $id)
{
    $request->validate([
        'final_price' => 'required|numeric|min:0',
    ]);

    $negotiation = Negotiation::findOrFail($id);

    $negotiation->final_price = $request->final_price;
    $negotiation->is_approved = true;
    $negotiation->save();

    // Buat order otomatis
    Order::create([
        'negotiation_id' => $negotiation->id,
        'product_id' => $negotiation->product_id,
        'vendor_id' => $negotiation->vendor_id,
        'customer_id' => $negotiation->customer_id,
        'agreed_price' => $negotiation->final_price,
    ]);

    return redirect()->back()->with('success', 'Harga disetujui dan PO telah dibuat.');
}

public function approvePrice(Request $request, $negotiation_id)
{
    $request->validate([
        'final_price' => 'required|numeric|min:0',
    ]);

    $negotiation = Negotiation::findOrFail($negotiation_id);
    $negotiation->update([
        'final_price' => $request->final_price,
        'is_approved' => true,
        'status' => 'approved',
    ]);

    return redirect()->back()->with('success', 'Harga telah disetujui dan negosiasi selesai.');
}


public function index(Request $request)
{
    $query = Order::with(['product', 'customer', 'tracking'])
        ->where('vendor_id', Auth::id());

    if ($request->type === 'negotiation') {
        $query->whereNotNull('negotiation_id');
    } elseif ($request->type === 'regular') {
        $query->whereNull('negotiation_id');
    }

    $orders = $query->orderByDesc('created_at')->get();

    return view('vendor.orders.index', compact('orders'));
}



public function showShippingForm(Order $order)
{
    // Pastikan vendor hanya bisa mengelola pesanan miliknya
    if ($order->vendor_id !== auth()->id()) {
        return redirect()->route('vendor.orders.index')->with('error', 'Anda tidak berhak mengatur pengiriman pesanan ini.');
    }

    // Jika tracking sudah ada, tetap tampilkan form untuk update estimasi
    return view('vendor.orders.shipping', compact('order'));
}

    

public function show($id)
{
    // Misalnya: ambil vendor dan tampilkan detailnya
    $vendor = Vendor::findOrFail($id);
    return view('vendor.show', compact('vendor'));
}

public function updateShipping(Request $request, Order $order)
{
    $request->validate([
        'estimated_date' => 'required|date',
        'estimated_time' => 'required|date_format:H:i',
    ]);

    $datetime = $request->estimated_date . ' ' . $request->estimated_time;

    Tracking::updateOrCreate(
        ['order_id' => $order->id],
        [
            'status' => 'dikirim',
            'estimated_delivery' => $datetime,
        ]
    );

    // Update juga status di table `orders` kalau kamu pakai kolom `status` di sana
    $order->status = 'dikirim';
    $order->save();

    return redirect()->route('vendor.orders.index')->with('success', 'Pengiriman berhasil diatur.');
}


public function markAsShipped(Request $request, $id)
{
    $order = Order::findOrFail($id);

    if ($order->vendor_id !== Auth::id()) {
        abort(403);
    }

    $request->validate([
        'estimated_delivery' => 'required|date|after_or_equal:today',
    ]);

    if (!$order->tracking) {
        $order->tracking()->create([
            'status' => 'dikirim',
            'estimated_delivery' => now()->addDays(2),
    ]);

    } else {
        $order->tracking->update([
            'status' => 'dikirim',
            'estimated_delivery' => now()->addDays(2),
        ]);
    }

    return back()->with('success', 'Pesanan berhasil dikirim dan mulai dimonitor.');
}

public function printAgreement(Order $order)
{
    // Cek apakah order kasbon dan punya file
    if ($order->payment_method === 'akad_kasbon' && $order->kasbon_agreement_path) {
        $pdf = Pdf::loadView('pdf.akad', [
            'order' => $order,
            'customer' => $order->customer, // asumsi relasi exists
        ]);

        return $pdf->stream('Surat-Perjanjian-Kasbon-'.$order->id.'.pdf');
    }

    abort(404, 'Perjanjian tidak ditemukan.');
}
public function process(Request $request, Order $order)
    {
        // Pastikan pesanan ini milik vendor yang sedang login
        if ($order->vendor_id !== Auth::id()) {
            return redirect()->route('vendor.orders.index')->with('error', 'Anda tidak berhak memproses pesanan ini.');
        }

        // Perbarui status pesanan menjadi 'diproses'
        $order->update(['status' => 'diproses']);

        return redirect()->route('vendor.orders.index')->with('success', 'Pesanan berhasil diproses.');
    }


public function viewAkadDokumen($id)
{
      $order = Order::findOrFail($id);

       $akad = $order->akadDokumen ?? $order->fallbackAkadDokumen;

   if (!$akad) {
        return view('vendor.orders.view_akad_dokumen', [
            'order' => $order,
            'akadDokumen' => null,
            'message' => 'Dokumen akad belum tersedia untuk pesanan ini.',
        ]);
    }

    return view('vendor.orders.view_akad_dokumen', [
    'order' => $order,
    'akadDokumen' => $akad
]);

}



public function validateDokumen($id, Request $request)
{
    $akad = AkadDokumen::where('order_id', $id)->first();

    if (!$akad) {
        return redirect()->back()->with('error', 'Dokumen akad tidak ditemukan.');
    }

    $akad->is_valid = true;
    $akad->validated_at = now(); // jika kamu punya kolom ini
    $akad->save();

    return redirect()->back()->with('success', 'Dokumen akad berhasil divalidasi.');
}

public function uploadAkad(Request $request, $id)
{
    $request->validate([
        'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'akad_file' => 'required|file|mimes:pdf|max:4096',
    ]);

    $ktpPath = $request->file('ktp')->store('akad_dokumen', 'public');
    $akadPath = $request->file('akad_file')->store('akad_dokumen', 'public');

    AkadDokumen::updateOrCreate(
    ['order_id' => $id], // cari berdasarkan order_id
    [
        'ktp_path' => $ktpPath,
        'akad_file' => $akadPath,
        'is_valid' => false,
    ]
);

}


public function approveAkad(Order $order)
{
    // Pastikan hanya vendor yang bersangkutan yang bisa proses
    if ($order->vendor_id !== auth()->id()) {
        abort(403);
    }

    // Ubah status pesanan
    $order->update([
        'status' => 'diproses' // atau status lain sesuai flow kamu
    ]);

    return redirect()->route('vendor.orders.index')->with('success', 'Pesanan telah diproses.');
}

public function showAgreementDocument(Order $order)
{
    // Cek otorisasi vendor
    if ($order->vendor_id !== auth()->id()) {
        abort(403);
    }

    return view('vendor.orders.view_akad_dokumen', compact('order'));
}

public function showFullInvoice(Order $order)
{
    // Validasi hak akses
    if ($order->vendor_id !== auth()->id()) {
        abort(403);
    }

    return view('vendor.orders.view_invoice', compact('order'));
}

public function viewAkad(Order $order)
{
    $customer = $order->customer;
    $vendor = $order->product->vendor;
    $product = $order->product;

    $pdf = Pdf::loadView('pdf.akad', compact('order', 'customer', 'vendor', 'product'))
              ->setPaper('A4');

    $pdfPath = 'agreements/akad-kasbon-' . $order->id . '.pdf';
Storage::put('public/' . $pdfPath, $pdf->output());

// Simpan ke database jika perlu
$order->update(['kasbon_agreement_path' => $pdfPath]);

    return $pdf->stream('akad-kasbon-' . $order->id . '.pdf');
}

public function accept(Order $order)
{
    // Validasi status dan payment_method dulu kalau perlu
    $order->status = Order::STATUS_DIPROSES;
    $order->save();

    return redirect()->back()->with('success', 'Pesanan telah diproses.');
}

public function invoiceStruk(Order $order)
{
    return view('vendor.orders.invoice', compact('order'));
}
}
