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
use App\Http\Controllers\AdminDashboardController;

// 1. THE SMART ROOT ROUTE
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $role = Auth::user()->role;

    return match($role) {
        'admin' => redirect('/admin/dashboard'),
        'hotel_manager' => redirect('/manage/hotels'),
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
    // Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

    // Reports
    Route::get('/admin/reports', [AdminDashboardController::class, 'reports']);

    // Management Resources
    Route::resource('manage/users', UserAdminController::class);
    Route::resource('manage/ads', AdController::class);

    Route::resource('manage/map', MapController::class);
});

// Hotel Manager (and Admin can access these too)
Route::middleware(['auth'])->group(function () {
    // Main hotel management dashboard
    Route::get('/manage/hotels', [AdminHotelController::class, 'index']);
    
    // Create hotel (admin only - checked in controller)
    Route::post('/manage/hotels', [AdminHotelController::class, 'store']);
    
    // Room management
    Route::post('/manage/rooms', [AdminHotelController::class, 'addRoom']);
    Route::delete('/manage/rooms/{room}', [AdminHotelController::class, 'deleteRoom']);
    
    // Booking management
    Route::put('/manage/bookings/{id}', [AdminHotelController::class, 'updateBooking']);
    
    // Promotions
    Route::post('/manage/promotions', [AdminHotelController::class, 'storePromotion']);
    Route::delete('/manage/promotions/{id}', [AdminHotelController::class, 'deletePromotion']);
});

// Ferry Staff
Route::middleware(['auth', 'role:ferry_staff'])->group(function () {
    Route::get('/ferry/dashboard', [AdminFerryController::class, 'index']);

    // Actions
    Route::post('/ferry/trips', [AdminFerryController::class, 'storeTrip']);
    Route::delete('/ferry/trips/{id}', [AdminFerryController::class, 'deleteTrip']);
    Route::post('/ferry/tickets', [AdminFerryController::class, 'storeTicket']);
    Route::put('/ferry/tickets/{id}', [AdminFerryController::class, 'updateStatus']);
});

// Theme Park Staff
Route::middleware(['auth', 'role:theme_park_staff'])->group(function () {
    Route::get('/themepark/dashboard', [AdminEventController::class, 'index']);
    // Add this route to your web.php file:
    Route::post('/manage/events', [AdminEventController::class, 'store'])->name('events.store');

    // Additional routes
    Route::post('/themepark/sell', [AdminEventController::class, 'manualSale']);
    Route::post('/themepark/validate', [AdminEventController::class, 'validateTicket']);
    Route::post('/themepark/promotions', [AdminEventController::class, 'storePromotion']);
    Route::delete('/themepark/promotions/{id}', [AdminEventController::class, 'deletePromotion']);
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