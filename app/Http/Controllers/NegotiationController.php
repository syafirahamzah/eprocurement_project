<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Negotiation;
use App\Models\NegotiationMessage;
use App\Models\Product;
use App\Models\Order;
use App\Models\Notification;
use App\Models\Agreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class NegotiationController extends Controller
{
    public function index()
    {
        $negotiations = Negotiation::where('vendor_id', Auth::id())->get();
        return view('vendor.negotiation.index', compact('negotiations'));
    }

    public function approve(Request $request, $id)
    {
        $negotiation = Negotiation::findOrFail($id);

        if ($negotiation->vendor_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Anda tidak berhak menyetujui negosiasi ini.');
    }

    $request->validate([
        'offered_price' => 'required|numeric|min:0',
      
        ]);

    $negotiation->offered_price = $request->offered_price;
    $negotiation->final_price   = $request->offered_price;
    $negotiation->status        = 'disetujui';
    $negotiation->is_approved   = true;
    $negotiation->save();


        //if (!$negotiation->order) {
            //$order = Order::create([
                //'negotiation_id' => $negotiation->id,
                //'product_id'     => $negotiation->product_id,
                //'vendor_id'      => $negotiation->vendor_id,
                //'customer_id'    => $negotiation->customer_id,
                //'agreed_price'   => $negotiation->offered_price,
                //'status'         => 'diproses',
              //  'user_id'        => Auth::id(),
                //'estimated_delivery' => $request->estimated_delivery,
           // ]);

            //$negotiation->order_id = $order->id;
       // }

       // $negotiation->save();

        Notification::create([
            'user_id'        => $negotiation->customer_id,
            'title'          => 'Negosiasi Disetujui',
            'message'        => 'Negosiasi Anda disetujui oleh vendor. Silakan lanjutkan pemesanan.',
            'product_id'     => $negotiation->product_id,
            'negotiation_id' => $negotiation->id,
            'is_read'        => false,
            'type'           => 'negotiation',
            'link_redirect' => route('customer.negotiation.form', $negotiation->product_id),
        ]);

        return redirect()->route('vendor.negotiation.index')->with('success', 'Negosiasi disetujui dan order dibuat.');
    }

    public function reject($id)
{
    $negotiation = Negotiation::findOrFail($id);
    $negotiation->status = 'ditolak';
    $negotiation->save();

    // Kirim notifikasi ke customer
    Notification::create([
        'user_id'        => $negotiation->customer_id,
        'title'          => 'Negosiasi Ditolak',
        'message'        => 'Negosiasi Anda ditolak oleh vendor.',
        'product_id'     => $negotiation->product_id,
        'negotiation_id' => $negotiation->id,
        'is_read'        => false,
        'type'           => 'negotiation',
        'link_redirect' => route('customer.negotiation.form', $negotiation->product_id),
    ]);

    return redirect()->route('vendor.negotiation.index')->with('success', 'Negosiasi ditolak.');
}


    public function showStatus($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('customer.orders')->with('error', 'Order tidak ditemukan.');
        }

        return view('customer.negotiation.status', compact('order'));
    }

    public function showNegotiationsForm($product_id)
    {
        $product = Product::findOrFail($product_id);

        $negotiation = Negotiation::where('product_id', $product_id)
    ->where('customer_id', Auth::id())
    ->whereDoesntHave('order') // hanya ambil negosiasi yang belum dibuatkan order
    ->latest()
    ->first();

if (!$negotiation) {
    $negotiation = Negotiation::create([
        'product_id'     => $product_id,
        'customer_id'    => Auth::id(),
        'vendor_id'      => $product->vendor_id,
        'status'         => 'menunggu',
        'offered_price'  => $product->price,
    ]);
}


       $messages = $negotiation->messages()->oldest()->get();


        return view('customer.negotiation.form', compact('negotiation', 'messages', 'product'));
    }


    public function sendMessage(Request $request, $negotiation_id)
    {
        $request->validate([
            'message' => 'required|string',
            'offered_price'  => 'nullable|numeric|min:1',
        ]);

        $negotiation = Negotiation::findOrFail($negotiation_id);

        if ($request->filled('offered_price') && Auth::user()->role === 'customer') {
        $negotiation->offered_price = $request->offered_price;
        $negotiation->save();
    }
    
        $negotiation->messages()->create([
            'user_id'        => Auth::id(),
            'negotiation_id' => $negotiation_id,
            'message'        => $request->message,
            'sender_role'    => Auth::user()->role,
        ]);

        $receiver_id = ($negotiation->customer_id == Auth::id())
            ? $negotiation->vendor_id
            : $negotiation->customer_id;

        // memastikan product dan negotiation ID ikut disimpan
        Notification::create([
            'user_id'        => $receiver_id,
            'title'          => 'Pesan Baru',
            'message'        => 'Ada pesan baru dalam negosiasi produk' . $negotiation->product->name, 
            'product_id'     => $negotiation->product_id,
            'negotiation_id' => $negotiation->id,
            'is_read'        => false,
            'type'           => 'negotiation',
            'link_redirect' => route('customer.negotiation.form', $negotiation->id),

]);
     

       return redirect()->route('customer.negotiation.form', $negotiation->id);

    }


public function respond(Request $request, $id)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $negotiation = Negotiation::findOrFail($id);

    // Simpan pesan
    $negotiation->messages()->create([
        'message' => $request->message,
        'sender_role' => 'customer',
    ]);

    return redirect()->back()->with('success', 'Pesan berhasil dikirim.');
}



    public function vendorForm($product_id)
    {
        $product = Product::findOrFail($product_id);

        $negotiation = Negotiation::where('product_id', $product_id)
            ->where('vendor_id', Auth::id())
            ->latest()
            ->first();

        if (!$negotiation) {
            return redirect()->back()->with('error', 'Belum ada negosiasi dari customer untuk produk ini.');
        }

        $messages = $negotiation->messages()->orderBy('created_at', 'asc')->get();

        return view('vendor.negotiation.chat', compact('product', 'negotiation', 'messages'));
    }

    

public function agree(Request $request, $id)
{
    $nego = Negotiation::findOrFail($id);

    $request->validate([
        'payment_method' => 'required|in:akad,cod',
        'agree_check'    => 'accepted',
        'quantity'       => 'required|integer|min:1',
        'agreement_document' => 'nullable|file|mimes:pdf|max:2048',
        'ktp_document'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    // Validasi hak akses
    if ($nego->customer_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Akses tidak sah.');
    }

    // Cegah jika pesanan sudah pernah dibuat
    if ($nego->order) {
        return redirect()->route('customer.orders')->with('info', 'Pesanan sudah pernah dibuat sebelumnya.');
    }

    $akadPath = null;
    $ktpPath = null;

    if ($request->hasFile('agreement_document')) {
        $akadPath = $request->file('agreement_document')->store('akad_documents', 'public');
    }

    if ($request->hasFile('ktp_document')) {
        $ktpPath = $request->file('ktp_document')->store('ktp_documents', 'public');
    }

    // Simpan persetujuan ke tabel agreements
    Agreement::create([
        'user_id'        => auth()->id(),
        'negotiation_id' => $nego->id,
        'agreement_text' => 'Saya menyetujui pembayaran saat akad sesuai negosiasi.',
        'ip_address'     => $request->ip(),
        'agreed_at'      => now(),
        'akad_file'      => $akadPath,
        'ktp_path'       => $ktpPath, 
    ]);
   

    // Buat pesanan dengan jumlah yang dipilih customer
    $order = Order::create([
        'vendor_id'        => $nego->vendor_id,
        'customer_id'      => auth()->id(),
        'product_id'       => $nego->product_id,
        'quantity'         => $request->quantity, 
        'status' => 'menunggu konfirmasi',
        'payment_status'   => 'belum',
        'agreed_price'     => $nego->final_price * $request->quantity, 
        'negotiation_id'   => $nego->id,
        'shipping_address' => $nego->customer->address,
        'payment_method'   => $request->payment_method,
    ]);

    // Simpan ID pesanan ke negosiasi
    $nego->order_id = $order->id;
    $nego->save();

    return redirect()->route('customer.orders')->with('success', 'Pesanan berhasil dibuat. Pembayaran dilakukan saat akad.');
}



public function vendorRespond(Request $request, $negotiation_id)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $negotiation = Negotiation::findOrFail($negotiation_id);

    // Simpan pesan chat
    NegotiationMessage::create([
        'negotiation_id' => $negotiation_id,
        'sender_role' => 'vendor',
        'message' => $request->message,
    ]);

    // 🔔 Buat notifikasi untuk customer
    Notification::create([
    'user_id'        => $negotiation->customer_id,
    'title'          => 'Pesan Baru dalam Negosiasi',
    'message'        => 'Vendor mengirim pesan baru untuk produk ' . $negotiation->product->name,
    'product_id'     => $negotiation->product_id,
    'negotiation_id' => $negotiation->id,
    'type'           => 'negotiation',
    'link_redirect'  => route('customer.negotiation.form', $negotiation->id),
    'is_read'        => false,
]);

    return back()->with('success', 'Pesan terkirim.');
}


}
