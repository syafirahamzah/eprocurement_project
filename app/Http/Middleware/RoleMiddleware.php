<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
{
    \Log::info('Role Middleware Triggered');
    \Log::info('User ID: ' . Auth::id());
    \Log::info('User Role: ' . (Auth::user()->role ?? 'null'));
    \Log::info('Required Roles: ' . implode(', ', $roles));

    if (Auth::check() && in_array(Auth::user()->role, $roles)) {
        return $next($request);
    }

    \Log::warning('Access denied: role mismatch');
    return redirect('/')->with('error', 'You do not have access to this page');
}


}
