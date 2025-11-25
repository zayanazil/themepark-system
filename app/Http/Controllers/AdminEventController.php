<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThemeParkEvent;
use App\Models\EventBooking;
use App\Models\EventPromotion;
use App\Models\User;

class AdminEventController extends Controller
{
    // 1. MAIN DASHBOARD (Reports + Overview)
    public function index()
    {
        $events = ThemeParkEvent::withCount('bookings')->get();

        // Reports Logic
        $totalRevenue = EventBooking::where('status', '!=', 'cancelled')->sum('total_price');
        $totalTicketsSold = EventBooking::where('status', '!=', 'cancelled')->sum('tickets');
        $bookings = EventBooking::with(['user', 'event'])->latest()->get();
        $promotions = EventPromotion::with('event')->get();

        return view('staff.event_manager', compact('events', 'bookings', 'totalRevenue', 'totalTicketsSold', 'promotions'));
    }

    // 2. CREATE EVENT (With Category)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
            'capacity' => 'required|integer',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'price' => 'required|integer'
        ]);

        ThemeParkEvent::create($data);
        return back()->with('success', 'Activity scheduled successfully');
    }

    // 3. SELL TICKET AT ENTRANCE (Manual Sale)
    public function manualSale(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email', // Must sell to a registered user
            'event_id' => 'required',
            'tickets' => 'required|integer|min:1'
        ]);

        $user = User::where('email', $request->email)->first();
        $event = ThemeParkEvent::find($request->event_id);

        // Check Capacity
        $sold = $event->bookings()->where('status', '!=', 'cancelled')->sum('tickets');
        if (($sold + $request->tickets) > $event->capacity) {
            return back()->with('error', 'Sold out! Cannot process ticket.');
        }

        EventBooking::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'tickets' => $request->tickets,
            'total_price' => $event->price * $request->tickets,
            'status' => 'valid'
        ]);

        return back()->with('success', 'Ticket sold at entrance!');
    }

    // 4. VALIDATE TICKET (Scanner Interface)
    public function validateTicket(Request $request)
    {
        // Staff enters Booking ID to "Scan" it
        $booking = EventBooking::find($request->booking_id);

        if (!$booking) {
            return back()->with('error', 'Invalid Ticket ID.');
        }

        if ($booking->status === 'redeemed') {
            return back()->with('error', 'Ticket already used!');
        }

        if ($booking->status === 'cancelled') {
            return back()->with('error', 'This ticket was cancelled.');
        }

        // Mark as Redeemed (Used)
        $booking->status = 'redeemed';
        $booking->save();

        return back()->with('success', "Ticket #{$booking->id} Validated. Welcome, {$booking->user->name}!");
    }

    // 5. CREATE PROMOTION
    public function storePromotion(Request $request)
    {
        EventPromotion::create($request->validate([
            'event_id' => 'required',
            'title' => 'required',
            'discount_details' => 'required'
        ]));
        return back()->with('success', 'Promotion Active');
    }

    // 6. DELETE EVENT
    public function destroy($id)
    {
        ThemeParkEvent::destroy($id);
        return back()->with('success', 'Event Removed');
    }
}
