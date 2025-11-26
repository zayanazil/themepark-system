<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FerryTrip;
use App\Models\FerryTicket;
use App\Models\HotelBooking;
use App\Models\User;

class AdminFerryController extends Controller
{
    // 1. DASHBOARD
    public function index()
    {
        // Get all trips ordered by time
        $trips = FerryTrip::orderBy('departure_time')->withCount('tickets')->get();

        // Get all tickets for the "Passenger List" view
        $allTickets = FerryTicket::with(['user', 'trip', 'hotelBooking.hotel'])->latest()->get();

        return view('staff.ferry_manager', compact('trips', 'allTickets'));
    }

    // 2. CREATE TRIP (Schedule)
    public function storeTrip(Request $request)
    {
        FerryTrip::create($request->validate([
            'route_name' => 'required',
            'departure_time' => 'required|date',
            'capacity' => 'required|integer'
        ]));
        return back()->with('success', 'Trip added to schedule.');
    }

    // 3. DELETE TRIP
    public function deleteTrip($id)
    {
        FerryTrip::destroy($id);
        return back()->with('success', 'Trip cancelled.');
    }

    // 4. ISSUE TICKET (Manual Validation Logic)
    public function storeTicket(Request $request)
    {
        // A. Validate Input
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'ferry_trip_id' => 'required|exists:ferry_trips,id'
        ]);

        $user = User::where('email', $request->email)->first();

        // B. CHECK VALIDITY: Does this user have a CONFIRMED hotel booking?
        $validBooking = HotelBooking::where('user_id', $user->id)
                            ->where('status', 'confirmed')
                            ->where('check_out', '>=', now()) // Ensure stay isn't over
                            ->latest()
                            ->first();

        if (!$validBooking) {
            return back()->with('error', 'VALIDATION FAILED: This passenger does not have a confirmed/active hotel booking.');
        }

        // C. CHECK CAPACITY
        $trip = FerryTrip::withCount('tickets')->find($request->ferry_trip_id);
        if ($trip->tickets_count >= $trip->capacity) {
            return back()->with('error', 'TRIP FULL: Cannot issue ticket.');
        }

        // D. Issue Ticket
        FerryTicket::create([
            'user_id' => $user->id,
            'ferry_trip_id' => $trip->id,
            'hotel_booking_id' => $validBooking->id,
            'status' => 'valid'
        ]);

        return back()->with('success', "Ticket Issued! Validated against booking at {$validBooking->hotel->name}.");
    }

    // 5. VALIDATE/BOARD PASSENGER
    public function updateStatus(Request $request, $id)
    {
        $ticket = FerryTicket::findOrFail($id);
        $ticket->status = $request->status; // 'boarded' or 'cancelled'
        $ticket->save();
        return back()->with('success', 'Passenger status updated.');
    }
}
