<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloControler;

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

Route::get('/hello', [HelloControler::class, 'show']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
