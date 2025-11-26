<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HotelController extends Controller
{
    // 1. Show Hotels Page to Visitor
    public function index()
    {
        // Get hotels with rooms to display options
        $hotels = Hotel::with('rooms')->get();

        // Get user's purchase history
        $myBookings = HotelBooking::where('user_id', Auth::id())
                        ->with('hotel')
                        ->latest()
                        ->get();

        return view('visitor.hotels', compact('hotels', 'myBookings'));
    }

    // 2. Process Booking
    public function store(Request $request)
    {
        // A. Validate Inputs
        $request->validate([
            'hotel_id' => 'required',
            'room_type' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1'
        ]);

        // B. Find a room of this type to get the price
        $room = Room::where('hotel_id', $request->hotel_id)
                    ->where('type', $request->room_type)
                    ->first();

        if (!$room) {
            return back()->with('error', 'Sorry, this hotel does not have rooms of that type available.');
        }

        // C. Calculate Nights & Total Price
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);

        // Safety check: ensure at least 1 night
        if ($nights < 1) $nights = 1;

        $totalPrice = $room->price * $nights;

        // D. Create Booking
        HotelBooking::create([
            'user_id' => Auth::id(),
            'hotel_id' => $request->hotel_id,
            'room_type' => $request->room_type,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'total_price' => $totalPrice, // Save calculated price for Analytics
            'status' => 'confirmed'
        ]);

        return back()->with('success', "Room booked successfully! Total Cost: $" . number_format($totalPrice));
    }
}
