<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

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
        // Bagikan variabel hitungan keranjang ke SELURUH View secara otomatis & aman
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (Auth::guard('customer')->check()) {
                // JIKA LOGIN: Hitung total dari Database MySQL
                $cartCount = Cart::where('customer_id', Auth::guard('customer')->id())->sum('jumlah');
            } else {
                // JIKA GUEST: Hitung dari Session (Aman dari NULL)
                $sessionCart = session()->get('cart', []);
                if (is_array($sessionCart)) {
                    $cartCount = count($sessionCart);
                }
            }

            $view->with('cartCount', $cartCount);
        });
    }
}