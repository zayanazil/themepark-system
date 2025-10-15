<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {return view('welcome');});
//guest routes
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);

//protected routes
// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return "Admin Dashboard";
    });
});

// Hotel Manager
Route::middleware(['auth', 'role:hotel_manager'])->group(function () {
    Route::get('/hotel/dashboard', function () {
        return "Hotel Manager Dashboard";
    });
});

// Ferry Staff
Route::middleware(['auth', 'role:ferry_staff'])->group(function () {
    Route::get('/ferry/dashboard', function () {
        return "Ferry Staff Dashboard";
    });
});

// Theme Park Staff
Route::middleware(['auth', 'role:theme_park_staff'])->group(function () {
    Route::get('/themepark/dashboard', function () {
        return "Theme Park Staff Dashboard";
    });
});

// Visitor
Route::middleware(['auth', 'role:visitor'])->group(function () {
    Route::get('/visitor/dashboard', function () {
        return "Visitor Dashboard";
    });
});

