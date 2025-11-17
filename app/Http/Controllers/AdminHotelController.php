<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\HotelBooking;

class AdminHotelController extends Controller
{
    // List all hotels and show a form to add new ones
    public function index()
    {
        $hotels = Hotel::all();
        // Get all bookings with user details
        $allBookings = HotelBooking::with(['user', 'hotel'])->latest()->get();

        return view('staff.hotel_manager', compact('hotels', 'allBookings'));
    }

    // Add a new Hotel
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'room_count' => 'required|integer'
        ]);

        Hotel::create($data);

        return back()->with('success', 'Hotel added successfully.');
    }

    // Delete a hotel
    public function destroy($id)
    {
        Hotel::destroy($id);
        return back()->with('success', 'Hotel deleted.');
    }
}
