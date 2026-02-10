<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
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
        // ✅ GLOBAL DB SCHEMA FIX (must be first)
        Schema::defaultStringLength(191);

        // ✅ Share cart count with all views
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())
                    ->distinct('product_id')
                    ->count('product_id');
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
