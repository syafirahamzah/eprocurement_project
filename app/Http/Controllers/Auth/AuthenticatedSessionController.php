<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    \Log::info('Login role: ' . $user->role);

    if ($user->role === 'vendor') {
        \Log::info('Redirecting to vendor dashboard');
        return redirect()->route('vendor.dashboard');
    } elseif ($user->role === 'customer') {
        \Log::info('Redirecting to customer dashboard');
        return redirect()->route('customer.dashboard');
    } elseif ($user->role === 'admin') {
        \Log::info('Redirecting to admin dashboard');
        return redirect()->route('admin.dashboard');
    }

    \Log::error('ROLE UNKNOWN: ' . $user->role);
    Auth::logout();
    return redirect('/')->with('error', 'Role tidak dikenali.');
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
