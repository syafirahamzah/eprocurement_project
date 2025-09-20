<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;     
use App\Models\Order;
use App\Observers\OrderObserver;



class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        Order::observe(OrderObserver::class);

        //  Ini akan membuat $unreadCount tersedia di semua view
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $unreadCount = Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                $view->with('unreadCount', $unreadCount);
            }
        });
    }
}
