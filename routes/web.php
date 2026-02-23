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

// Trasa do zmiany roli
Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

// Trasa do usuwania użytkownika
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/profile', [UserController::class, 'show'])->name('users.profile') -> middleware('auth');




Route::get('/products/list', [ProductController::class, 'index']) -> name('products.index') -> middleware('auth') -> middleware('can:isAdmin');

// Trasa do wyświetlenia formularza edycji
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

// Trasa do zapisania zmian
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Trasa do zapisu nowego produktu
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

Route::delete('/cart/remove/{id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::patch('/cart/update/{id}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');



Route::get('/inventories/list', [InventoryController::class, 'index'])->name('inventories.index') -> middleware('auth') -> middleware('can:isInventory');

Route::patch('/inventories/update', [InventoryController::class, 'update'])->name('inventory.update');

















// // Trasy tylko dla admina
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/users/list', [AdminController::class, 'index']);
// });

// // Trasy tylko dla klientów
// // Route::middleware(['auth', 'role:client'])->group(function () {
// //     // Route::get('/dashboard', [UserController::class, 'index']);
// // });

