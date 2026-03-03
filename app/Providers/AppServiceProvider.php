<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;


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
        Paginator::useBootstrap();

        try {
        
            $settings = \App\Models\Setting::whereIn('key', [
                'payment_api_key', 
                'payment_api_secret'
            ])->pluck('value', 'key');

            if (isset($settings['payment_api_secret'])) {
                config(['services.stripe.secret' => $settings['payment_api_secret']]);
                config(['services.stripe.key' => $settings['payment_api_key'] ?? '']);
            }
        } catch (\Exception $e) {
            
        }

        $shopName = DB::table('settings')->where('key', 'shop_name')->value('value');
    
        // Udostępnia zmienną $shopName dla wszystkich plików .blade.php
        View::share('shopName', $shopName ?? 'Domyślna Nazwa');
    }
}