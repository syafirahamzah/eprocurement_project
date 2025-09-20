<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Kalau mau nanti bisa ditambah middleware global disini
    ];

    /**
     * Group middleware Web dan API.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\RedirectIfAuthenticated::class,

        ],

        'api' => [
            // Untuk API (bisa diisi throttle dan lain-lain kalau perlu)
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Middleware yang spesifik untuk route.
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class, 
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'redirect.role' => \App\Http\Middleware\RedirectIfAuthenticatedByRole::class,

    ];
}
