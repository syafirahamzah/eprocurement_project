<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class AdminController extends Controller
{
    public function dashboard()
{
    $totalOrders = Order::count();
    $totalProducts = Product::count();
    $totalVendors = User::where('role', 'vendor')->count();
    $totalCustomers = User::where('role', 'customer')->count();

    return view('admin.dashboard', compact('totalOrders', 'totalProducts', 'totalVendors', 'totalCustomers'));
}

 

public function monitoring()
{
    $orders = Order::with(['product', 'customer', 'vendor', 'tracking'])
        ->latest()
        ->get();

    return view('admin.monitoring.index', compact('orders'));
}



public function listCustomers()
{
    $customers = User::where('role', 'customer')->get();
    return view('admin.customers.index', compact('customers'));
}

public function editCustomer($id)
{
    $customer = User::where('role', 'customer')->findOrFail($id);
    return view('admin.customers.edit', compact('customer'));
}

public function updateCustomer(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
    ]);

    $customer = User::where('role', 'customer')->findOrFail($id);
    $customer->update($request->only('name', 'email', 'phone', 'address'));

    return redirect()->route('admin.customers.index')->with('success', 'Customer berhasil diperbarui.');
}

public function destroyCustomer($id)
{
    $customer = User::where('role', 'customer')->findOrFail($id);
    $customer->delete();

    return redirect()->route('admin.customers.index')->with('success', 'Customer berhasil dihapus.');
}



public function listVendors()
{
    $vendors = User::with('products')->where('role', 'vendor')->get();
    return view('admin.vendors.index', compact('vendors'));
}


public function editVendor($id)
{
    $vendor = User::findOrFail($id);
    return view('admin.vendors.edit', compact('vendor'));
}

public function updateVendor(Request $request, $id)
{
    $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'phone'   => 'nullable|string',
        'address' => 'nullable|string',
    ]);

    $vendor = User::findOrFail($id);
    $vendor->update($request->only('name', 'email', 'phone', 'address'));

    return redirect()->route('admin.vendors.index')->with('success', 'Data vendor berhasil diperbarui.');
}

public function destroyVendor($id)
{
    $vendor = User::findOrFail($id);
    $vendor->delete();

    return redirect()->route('admin.vendors.index')->with('success', 'Vendor berhasil dihapus.');
}

public function statistics()
{
    $statusCounts = \App\Models\Order::selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    return view('admin.statistics', compact('statusCounts'));
}

public function profile()
{
    $admin = Auth::user();
    return view('admin.profile', compact('admin'));
}

public function updateProfile(Request $request)
{
    $admin = Auth::user();
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $admin->id,
    ]);
    $admin->update($request->only('name', 'email'));
    return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
}

}
