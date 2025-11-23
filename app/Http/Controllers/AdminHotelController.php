<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\HotelPromotion;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class AdminHotelController extends Controller
{
    // 1. SELECTION SCREEN (Dropdown)
    public function index()
    {
        $hotels = Hotel::all();
        return view('staff.select_hotel', compact('hotels'));
    }

    // Process the dropdown selection
    public function handleSelection(Request $request)
    {
        $request->validate(['hotel_id' => 'required']);
        return redirect('/manage/hotel/' . $request->hotel_id);
    }

    // 2. MAIN DASHBOARD (For a specific hotel)
    public function showDashboard($id)
    {
        $hotel = Hotel::with('rooms')->findOrFail($id);

        $bookings = HotelBooking::where('hotel_id', $id)
                        ->with(['user'])
                        ->latest()
                        ->get();

        $promotions = HotelPromotion::where('hotel_id', $id)->get();

        return view('staff.hotel_dashboard', compact('hotel', 'bookings', 'promotions'));
    }

    // 3. ADD INDIVIDUAL ROOM
    public function storeRoom(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => 'required',
            'room_number' => 'required', // e.g. "101"
            'type' => 'required|in:Single,Couple,Family,Deluxe',
            'price' => 'required|numeric'
        ]);

        Room::create($data);
        return back()->with('success', 'Room added successfully');
    }

    // 4. BOOKING MANAGEMENT
    public function editBooking($id)
    {
        $booking = HotelBooking::with('hotel')->findOrFail($id);
        return view('staff.edit_booking', compact('booking'));
    }

    public function updateBooking(Request $request, $id)
    {
        $booking = HotelBooking::findOrFail($id);

        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'room_type' => 'required|in:Single,Couple,Family,Deluxe', // Updated
            'status' => 'required'
        ]);

        $booking->update([
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'room_type' => $request->room_type,
            'status' => $request->status
        ]);

        return redirect('/manage/hotel/'.$booking->hotel_id)->with('success', 'Booking updated successfully');
    }

    // Admin Only: Create Hotel
    public function store(Request $request) {
        if (Auth::user()->role !== 'admin') abort(403);
        Hotel::create($request->validate(['name'=>'required', 'room_count'=>'nullable'])); // room_count dummy
        return back()->with('success', 'Hotel created');
    }
}
