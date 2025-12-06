<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use App\Models\FerryTrip; // New
use Illuminate\Support\Facades\Auth;

class FerryController extends Controller
{
    public function index()
    {
        $eligibleBooking = HotelBooking::where('user_id', Auth::id())
                                ->where('status', 'confirmed')
                                ->latest()
                                ->first();

        // Get future trips only
        $upcomingTrips = FerryTrip::where('departure_time', '>=', now())
                                  ->orderBy('departure_time')
                                  ->get();

        $myTickets = FerryTicket::where('user_id', Auth::id())
                                ->with('trip')
                                ->get();

        return view('visitor.ferry', compact('eligibleBooking', 'myTickets', 'upcomingTrips'));
    }

    public function store(Request $request)
    {
        $booking = HotelBooking::where('user_id', Auth::id())
                        ->where('status', 'confirmed')
                        ->first();
    
        if (!$booking) {
            return back()->with('error', 'No valid hotel booking found.');
        }
    
        $request->validate([
            'ferry_trip_id' => 'required|exists:ferry_trips,id'
        ]);
    
        // Get trip with current ticket count
        $trip = FerryTrip::withCount('tickets')->find($request->ferry_trip_id);
    
        if ($trip->tickets_count >= $trip->capacity) {
            return back()->with('error', 'This ferry is full. No tickets remaining.');
        }
    
        FerryTicket::create([
            'user_id' => Auth::id(),
            'hotel_booking_id' => $booking->id,
            'ferry_trip_id' => $trip->id,
            'status' => 'valid'
        ]);
    
        return back()->with('success', 'Ferry ticket booked!');
    }

}
