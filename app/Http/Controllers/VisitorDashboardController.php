<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ad;
use App\Models\MapLocation;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use App\Models\EventBooking;

class VisitorDashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        // 1. Fetch Ads & Maps (Existing)
        $ads = Ad::latest()->get();
        $locations = MapLocation::all();

        // 2. Fetch User's Purchases (New)
        $myHotelBookings = HotelBooking::where('user_id', $user_id)
                            ->with('hotel')
                            ->latest()
                            ->get();

        $myFerryTickets = FerryTicket::where('user_id', $user_id)
                            ->with('hotelBooking.hotel') // Get hotel info via the booking link
                            ->latest()
                            ->get();

        $myEventBookings = EventBooking::where('user_id', $user_id)
                            ->with('event')
                            ->latest()
                            ->get();

        return view('visitor.dashboard', compact(
            'ads',
            'locations',
            'myHotelBookings',
            'myFerryTickets',
            'myEventBookings'
        ));
    }
}
