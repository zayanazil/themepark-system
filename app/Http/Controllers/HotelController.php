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
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1'
        ]);
    
        // B. Get the specific room
        $room = Room::findOrFail($request->room_id);
    
        // C. Verify the room matches the selected type
        if ($room->type !== $request->room_type) {
            return back()->with('error', 'Invalid room selection.');
        }
    
        // D. Double-check this room is actually available for these dates
        $isBooked = HotelBooking::where('room_id', $room->id)
            ->where('status', 'confirmed')
            ->where('check_in', '<', $request->check_out)
            ->where('check_out', '>', $request->check_in)
            ->exists();
    
        if ($isBooked) {
            return back()->with('error', 'Sorry, this room was just booked. Please select another room.');
        }
    
        // E. Calculate Nights & Total Price
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        if ($nights < 1) $nights = 1;
        $totalPrice = $room->price * $nights;
    
        // F. Create Booking
        HotelBooking::create([
            'user_id' => Auth::id(),
            'hotel_id' => $request->hotel_id,
            'room_id' => $room->id,
            'room_type' => $room->type,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'total_price' => $totalPrice,
            'status' => 'confirmed'
        ]);
    
        return redirect('/hotels')->with('success', "Room #{$room->id} ({$room->type}) booked successfully! Total Cost: $" . number_format($totalPrice, 2));
    }
}
