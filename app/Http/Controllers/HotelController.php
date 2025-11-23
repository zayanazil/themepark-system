<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    public function index()
    {
        // Get hotels with rooms to calculate pricing/availability
        $hotels = Hotel::with('rooms')->get();
        $myBookings = HotelBooking::where('user_id', Auth::id())->with('hotel')->get();

        return view('visitor.hotels', compact('hotels', 'myBookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required',
            'room_type' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1'
        ]);

        // Basic Check: Do rooms of this type exist?
        $countRooms = Room::where('hotel_id', $request->hotel_id)
                          ->where('type', $request->room_type)
                          ->count();

        if ($countRooms == 0) {
            return back()->with('error', 'Sorry, this hotel has no rooms of that type.');
        }

        // (Optional: In a real app, you would check date overlaps here)

        HotelBooking::create([
            'user_id' => Auth::id(),
            'hotel_id' => $request->hotel_id,
            'room_type' => $request->room_type,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'status' => 'confirmed'
        ]);

        return back()->with('success', 'Room booked successfully!');
    }
}
