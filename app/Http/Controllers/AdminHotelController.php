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
    // 1. MAIN DASHBOARD (Shows all hotels and management)
    public function index()
    {
        $hotels = Hotel::with('rooms')->get();
        $allBookings = HotelBooking::with(['user', 'hotel', 'room'])->latest()->get();
        $promotions = HotelPromotion::with('hotel')->latest()->get();
        
        return view('staff.hotel_manager', compact('hotels', 'allBookings', 'promotions'));
    }

    // 2. CREATE HOTEL (Admin Only)
    public function store(Request $request) 
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:hotels,name'
        ]);

        Hotel::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Hotel created successfully!');
    }

    // 3. ADD INDIVIDUAL ROOM
    public function addRoom(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'type' => 'required|string|in:Single,Double,Deluxe,Suite,Family',
            'price' => 'required|numeric|min:0'
        ]);

        Room::create([
            'hotel_id' => $request->hotel_id,
            'type' => $request->type,
            'price' => $request->price
        ]);

        return back()->with('success', 'Room added successfully!');
    }

    // 4. DELETE ROOM
    public function deleteRoom(Room $room)
    {
        // Check if room has any confirmed bookings
        $hasActiveBookings = HotelBooking::where('room_id', $room->id)
            ->where('status', 'confirmed')
            ->where('check_out', '>=', now())
            ->exists();

        if ($hasActiveBookings) {
            return back()->with('error', 'Cannot delete room with active bookings.');
        }

        $room->delete();
        return back()->with('success', 'Room deleted successfully!');
    }

    // 5. UPDATE BOOKING STATUS
    public function updateBooking(Request $request, $id)
    {
        $booking = HotelBooking::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Booking status updated successfully!');
    }

    // 6. CREATE PROMOTION
    public function storePromotion(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'title' => 'required|string|max:255',
            'discount_percent' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        HotelPromotion::create([
            'hotel_id' => $request->hotel_id,
            'title' => $request->title,
            'discount_percent' => $request->discount_percent,
            'description' => $request->description
        ]);

        return back()->with('success', 'Promotion created successfully!');
    }

    // 7. DELETE PROMOTION
    public function deletePromotion($id)
    {
        $promotion = HotelPromotion::findOrFail($id);
        $promotion->delete();

        return back()->with('success', 'Promotion deleted successfully!');
    }
}