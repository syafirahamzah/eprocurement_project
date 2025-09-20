<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ========================
    // Login untuk CUSTOMER
    // ========================
    public function showCustomerLogin()
    {
        return view('auth.login-customer');
    }

    public function loginCustomer(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->hasRole('customer')) {
                $request->session()->regenerate();
                return redirect()->route('customer.dashboard');
            }

            Auth::logout();
            return back()->withErrors(['email' => 'Akun ini bukan akun customer.']);
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }


    // ========================
    // Login untuk VENDOR
    // ========================
    public function showVendorLogin()
    {
        return view('auth.login-vendor');
    }

    public function loginVendor(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->role === 'vendor') {
            $request->session()->regenerate();
            return redirect()->route('vendor.dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'Akun ini bukan akun vendor.']);
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
}

    // ========================
    // Login untuk ADMIN
    // ========================
    public function showAdminLogin()
    {
        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return back()->withErrors(['email' => 'Akun ini bukan akun admin.']);
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }
}
