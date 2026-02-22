<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;


Route::get('/about', function(){
    return view('about');
});

Route::get('/contact', function(){
    return view('contact');
});

Route::get('/cart', function(){
    return view('cart');
});

// Route::get('/login', function(){
//     return view('login');
// });

Route::get('/hello', [HelloController::class, 'show']);

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/categories/show/{name}', [CategoryController::class, 'show'])->name('categories.show');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');




Route::get('/users/list', [UserController::class, 'index']) -> middleware('auth') -> middleware('can:isAdmin');

// Trasa do zmiany roli
Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

// Trasa do usuwania użytkownika
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');




Route::get('/products/list', [ProductController::class, 'index']) -> name('products.index') -> middleware('auth') -> middleware('can:isAdmin');

// Trasa do wyświetlenia formularza edycji
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

// Trasa do zapisania zmian
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Trasa do zapisu nowego produktu
Route::post('/products', [ProductController::class, 'store'])->name('products.store');


Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

Route::delete('/cart/remove/{id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::patch('/cart/update/{id}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');





















// // Trasy tylko dla admina
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/users/list', [AdminController::class, 'index']);
// });

// // Trasy tylko dla klientów
// // Route::middleware(['auth', 'role:client'])->group(function () {
// //     // Route::get('/dashboard', [UserController::class, 'index']);
// // });

