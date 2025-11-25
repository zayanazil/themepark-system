<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisitorDashboardController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\FerryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminHotelController;
use App\Http\Controllers\AdminFerryController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\MapController;

// 1. THE SMART ROOT ROUTE
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $role = Auth::user()->role;

    return match($role) {
        'admin' => redirect('/admin/dashboard'),
        'hotel_manager' => redirect('/hotel/selection'), // <--- FIXED: Points to Selection Menu now
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
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');


// 3. PROTECTED ROUTES

// --- ADMIN ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
    Route::resource('manage/users', UserAdminController::class);
    Route::resource('manage/ads', AdController::class);
    Route::resource('manage/map', MapController::class);
});

// Hotel Manager
Route::middleware(['auth', 'role:hotel_manager'])->group(function () {
    Route::get('/hotel/selection', [AdminHotelController::class, 'index'])->name('hotel.select');

    // NEW: Handle Dropdown Submit
    Route::post('/hotel/select', [AdminHotelController::class, 'handleSelection']);

    Route::get('/manage/hotel/{id}', [AdminHotelController::class, 'showDashboard']);
    Route::post('/manage/rooms', [AdminHotelController::class, 'storeRoom']); // New Route

    Route::post('/manage/hotels', [AdminHotelController::class, 'store']); // Admin add hotel

    // ... bookings and promos routes ...
    Route::get('/manage/booking/{id}/edit', [AdminHotelController::class, 'editBooking']);
    Route::put('/manage/booking/{id}', [AdminHotelController::class, 'updateBooking']);
    Route::post('/manage/promotions', [AdminHotelController::class, 'storePromotion']);
});

// --- FERRY STAFF ---
Route::middleware(['auth', 'role:ferry_staff'])->group(function () {
    Route::get('/ferry/dashboard', [AdminFerryController::class, 'index']);
    Route::resource('manage/ferry', AdminFerryController::class);
});

// Theme Park Staff
Route::middleware(['auth', 'role:theme_park_staff'])->group(function () {
    Route::get('/themepark/dashboard', [AdminEventController::class, 'index']);
    Route::resource('manage/events', AdminEventController::class);

    // NEW ROUTES
    Route::post('/themepark/sell', [AdminEventController::class, 'manualSale']);
    Route::post('/themepark/validate', [AdminEventController::class, 'validateTicket']);
    Route::post('/themepark/promote', [AdminEventController::class, 'storePromotion']);
});

// --- VISITOR ---
Route::middleware(['auth', 'role:visitor'])->group(function () {
    Route::get('/visitor/dashboard', [VisitorDashboardController::class, 'index']);

    Route::get('/hotels', [HotelController::class, 'index']);
    Route::post('/book-hotel', [HotelController::class, 'store']);

    Route::get('/ferry', [FerryController::class, 'index']);
    Route::post('/ferry', [FerryController::class, 'store']);

    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events/book', [EventController::class, 'bookEvent']);
});
