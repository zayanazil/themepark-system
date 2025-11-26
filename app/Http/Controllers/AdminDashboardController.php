<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HotelBooking;
use App\Models\EventBooking;
use App\Models\ThemeParkEvent;
use App\Models\Hotel;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. User Stats
        $totalUsers = User::count();
        $newUsers = User::where('created_at', '>=', now()->subDays(7))->count();

        // 2. Financial Stats
        $hotelRevenue = HotelBooking::where('status', '!=', 'cancelled')->sum('total_price');
        $eventRevenue = EventBooking::where('status', '!=', 'cancelled')->sum('total_price');
        $totalRevenue = $hotelRevenue + $eventRevenue;

        // 3. Recent Activity (Merge Bookings)
        $recentBookings = HotelBooking::with(['user', 'hotel'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'newUsers',
            'totalRevenue', 'hotelRevenue', 'eventRevenue',
            'recentBookings'
        ));
    }

    public function reports()
    {
        // Detailed Report Data
        $hotels = Hotel::withCount(['rooms', 'rooms as booked_rooms_count' => function($query) {
             // Rough estimate of active bookings count
             // In real app, complex date logic goes here
        }])->get();

        $eventSales = ThemeParkEvent::withCount('bookings')->get();

        return view('admin.reports', compact('hotels', 'eventSales'));
    }
}
