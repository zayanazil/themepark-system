<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Import this!
use App\Http\Controllers\AuthController;

// 1. THE SMART ROOT ROUTE
// This acts as a traffic controller.
// If logged in -> Go to specific dashboard.
// If NOT logged in -> Go to Login page.
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $role = Auth::user()->role;

    return match($role) {
        'admin' => redirect('/admin/dashboard'),
        'hotel_manager' => redirect('/hotel/dashboard'),
        'ferry_staff' => redirect('/ferry/dashboard'),
        'theme_park_staff' => redirect('/themepark/dashboard'),
        default => redirect('/visitor/dashboard'),
    };
});

// 2. GUEST ROUTES
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);

// 3. LOGOUT ROUTE (Essential)
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- 4. PROTECTED ROUTES ---

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard'); // Ensure you create this file or reuse the previous logic
    });
    // Admin Management Routes
    Route::resource('manage/users', App\Http\Controllers\UserAdminController::class);
    Route::resource('manage/ads', App\Http\Controllers\AdController::class);
    Route::resource('manage/map', App\Http\Controllers\MapController::class);
});

// Hotel Manager
Route::middleware(['auth', 'role:hotel_manager'])->group(function () {
    // We direct the dashboard directly to the controller index
    Route::get('/hotel/dashboard', [App\Http\Controllers\AdminHotelController::class, 'index']);

    // Resource route for create/delete
    Route::resource('manage/hotels', App\Http\Controllers\AdminHotelController::class);
});

// Ferry Staff
Route::middleware(['auth', 'role:ferry_staff'])->group(function () {
    Route::get('/ferry/dashboard', [App\Http\Controllers\AdminFerryController::class, 'index']);
    Route::resource('manage/ferry', App\Http\Controllers\AdminFerryController::class);
});

// Theme Park Staff
Route::middleware(['auth', 'role:theme_park_staff'])->group(function () {
    Route::get('/themepark/dashboard', [App\Http\Controllers\AdminEventController::class, 'index']);
    Route::resource('manage/events', App\Http\Controllers\AdminEventController::class);
});

// Visitor
Route::middleware(['auth', 'role:visitor'])->group(function () {
    Route::get('/visitor/dashboard', [App\Http\Controllers\VisitorDashboardController::class, 'index']);

    Route::get('/hotels', [App\Http\Controllers\HotelController::class, 'index']);
    Route::post('/book-hotel', [App\Http\Controllers\HotelController::class, 'store']);

    Route::get('/ferry', [App\Http\Controllers\FerryController::class, 'index']);
    Route::post('/ferry', [App\Http\Controllers\FerryController::class, 'store']);

    Route::get('/events', [App\Http\Controllers\EventController::class, 'index']);
    Route::post('/events/book', [App\Http\Controllers\EventController::class, 'bookEvent']);
});
