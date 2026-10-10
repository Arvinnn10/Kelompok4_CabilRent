<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//route autentikasi (Login, Register, Logout)
//file auth ni khusus buat menangani seluruh route autentikasi (Login, Register, Logout) secara aman dari App\Http\Controllers\AuthController.

// route guest (Login & Register)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// route autentikasi (Logout)
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
});
