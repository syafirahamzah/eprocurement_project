<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\NegotiationMessage;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kirim otomatis $newMessageCount ke layout vendor
        View::composer('layouts.vendor', function ($view) {
            $vendor = Auth::user();
            $newMessageCount = 0;

            if ($vendor && $vendor->role === 'vendor') {
                $newMessageCount = NegotiationMessage::where('sender_role', 'customer')
                    ->whereHas('negotiation', function ($query) use ($vendor) {
                        $query->where('vendor_id', $vendor->id);
                    })
                    ->where('is_read', false)
                    ->count();
            }

            $view->with('newMessageCount', $newMessageCount);
        });
    }
}
