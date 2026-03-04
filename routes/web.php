<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\LogController;


Route::get('/about', function(){
    return view('about');
});

Route::get('/contact', function(){
    return view('contact');
});

Route::get('/', [WelcomeController::class, 'index']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::get('/users/list', [UserController::class, 'index']) -> middleware('auth') -> middleware('can:isAdmin');

Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/profile', [UserController::class, 'show'])->name('users.profile') -> middleware('auth');

Route::put('/profile/marketing', [ProfileController::class, 'updateMarketing']) ->name('profile.marketing.update') -> middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');

    Route::delete('/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('addresses.destroy');
});


Route::get('/products/list', [ProductController::class, 'index']) -> name('products.index') -> middleware('auth') -> middleware('can:isAdmin');

Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::post('/products', [ProductController::class, 'store'])->name('products.store');

Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products.show');




Route::get('/categories/list', [CategoryController::class, 'index'])->name('categories.index') -> middleware('auth') -> middleware('can:isAdmin');

Route::post('/categories', [CategoryController::class, 'store'])
    ->name('categories.store')
    ->middleware(['auth', 'can:isAdmin']);

Route::get('/categories/show/{name}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/categories/{gender}', [CategoryController::class, 'genderIndex'])->name('categories.gender');

Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
    ->name('categories.destroy')
    ->middleware(['auth', 'can:isAdmin']);

Route::patch('/inventory/update', [InventoryController::class, 'update'])->name('inventory.update');




// Route::get('/cart', function(){
//     return view('cart');
// });

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');

Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');

Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');

Route::post('/coupons/{coupon}/reset-usage', [CouponController::class, 'resetUsage'])->name('coupons.resetUsage');



Route::get('/inventories/list', [InventoryController::class, 'index'])->name('inventories.index') -> middleware('auth') -> middleware('can:isInventory');

Route::patch('/inventories/update', [InventoryController::class, 'update'])->name('inventory.update');

Route::post('/inventories', [InventoryController::class, 'store'])->name('inventory.store');

Route::delete('/inventories/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');



Route::get('/orders', [OrderController::class, 'userIndex'])->name('orders.user.index') -> middleware('auth');

Route::get('/orders/admin', [OrderController::class, 'index'])->name('orders.index') -> middleware('auth') -> middleware('can:isInventory');

Route::patch('/orders/{order}/status', [OrderController::class, 'update'])->name('orders.update');

Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show') -> middleware('auth');

Route::patch('/orders/{order}', [OrderController::class, 'noteUpdate']) ->name('note.update') -> middleware('auth');


Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index') -> middleware('can:isAdmin');

Route::patch('/settings/update', [SettingsController::class, 'update'])->name('settings.update')->middleware('can:isAdmin');

Route::post('/settings/shipping', [SettingsController::class, 'store'])->name('shipping.store');

Route::delete('/settings/shipping/{id}', [SettingsController::class, 'destroy'])->name('shipping.destroy');


Route::post('/products/{product}/opinions', [OpinionController::class, 'store'])->name('opinions.store')->middleware('auth');

Route::delete('/opinions/{opinion}', [OpinionController::class, 'destroy'])->name('opinions.destroy')->middleware('auth');



Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
Route::post('/cart/reorder/{order}', [CartController::class, 'reorder'])->name('cart.reorder');
Route::post('/checkout/repay/{order}', [CheckoutController::class, 'repay'])->name('checkout.repay');

Route::delete('/orders/{id}', [OrderController::class, 'destroyAdmin'])->name('orders.destroyAdmin');


Route::get('/logs', [LogController::class, 'index'])
    ->name('logs.index')
    ->middleware(['auth', 'can:isAdmin']);