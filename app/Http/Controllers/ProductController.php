<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    
public function show(Product $product)
{
    return view('customer.product_detail', compact('product'));
}

public function destroy($id)
{
    $product = Product::findOrFail($id);

    // Hapus relasi orders → negotiations → product
    foreach ($product->negotiations as $negotiation) {
        // Hapus semua order dari negosiasi ini
        $negotiation->orders()->delete();

        // Hapus negosiasinya
        $negotiation->delete();
    }

    // Setelah relasi bersih, hapus produknya
    $product->delete();

    return redirect()->route('vendor.products.index')->with('success', 'Produk berhasil dihapus.');
}





    
    
    
    public function showeCatalog()
{
    $products = Product::all();

    return view('customer.eCatalog', compact('products'));
}
public function index()
{
    $products = Product::where('vendor_id', Auth::id())->get();
    return view('vendor.products.index', compact('products'));
}

public function create()
{
    return view('vendor.products.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    $validated['vendor_id'] = Auth::id();

    Product::create($validated);

    return redirect()->route('vendor.products.index')->with('success', 'Produk berhasil ditambahkan.');
}


}
