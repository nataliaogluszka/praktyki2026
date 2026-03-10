<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\Setting;
use App\Models\ShippingMethod;
use App\Models\Inventory;
use App\Models\User;
use App\Observers\AuditObserver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;


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

        Product::observe(AuditObserver::class);
        Order::observe(AuditObserver::class);
        Setting::observe(AuditObserver::class);
        ShippingMethod::observe(AuditObserver::class);
        Inventory::observe(AuditObserver::class);
        User::observe(AuditObserver::class);
        Coupon::observe(AuditObserver::class);
        Category::observe(AuditObserver::class);

        // try {
        
        //     $settings = Setting::whereIn('key', [
        //         'payment_api_key', 
        //         'payment_api_secret'
        //     ])->pluck('value', 'key');

        //     if (isset($settings['payment_api_secret'])) {
        //         config(['services.stripe.secret' => $settings['payment_api_secret']]);
        //         config(['services.stripe.key' => $settings['payment_api_key'] ?? '']);
        //     }
        // } catch (\Exception $e) {
            
        // }

        if (Schema::hasTable('settings')) {
            $settings = Setting::pluck('value', 'key')->all();

            if (isset($settings['payment_api_key'])) {
                Config::set('services.stripe.key', $settings['payment_api_key']);
                Config::set('services.stripe.secret', $settings['payment_api_secret']);
            }
        }

        $shopName = DB::table('settings')->where('key', 'shop_name')->value('value');
    
        View::share('shopName', $shopName ?? 'Domyślna Nazwa');
    }
}