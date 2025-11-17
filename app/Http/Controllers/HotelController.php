<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\HotelBooking;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        // Get user's past/current bookings
        $myBookings = HotelBooking::where('user_id', Auth::id())->with('hotel')->get();

        return view('visitor.hotels', compact('hotels', 'myBookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1'
        ]);

        HotelBooking::create([
            'user_id' => Auth::id(),
            'hotel_id' => $request->hotel_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'status' => 'confirmed' // Auto-confirm for this demo
        ]);

        return back()->with('success', 'Hotel booked successfully!');
    }
}
