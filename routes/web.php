<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/about', function(){
//     return 'About page';
// });

Route::get('/about', function(){
    return view('about');
});

Route::get('/contact', function(){
    return view('contact');
});

// Route::get('/login', function(){
//     return view('login');
// });

Route::get('/hello', [HelloController::class, 'show']);

Route::get('/users/list', [UserController::class, 'index']) -> middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
