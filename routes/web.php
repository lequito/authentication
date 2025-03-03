<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    //login routes
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    // register routes
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeUser'])->name('store_user');
    //new user confirmation
    Route::get('/new_user_confirmation/{token}', [AuthController::class, 'new_user_confirmation'])->name('new_user_confirmation');
});

Route::middleware('auth')->group(function(){
    Route::get('/', [MainController::class, 'home'])->name('home');
    //profile change password
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'changePassword'])->name('change-password');
    //logout
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});


