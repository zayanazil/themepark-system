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
        $events = ThemeParkEvent::with('promotion')->withCount('bookings')->get();
        
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
        try {
            $data = $request->validate([
                'name' => 'required|max:255',
                'category' => 'required',
                'description' => 'required',
                'capacity' => 'required|integer|min:1',
                'event_date' => 'required|date|after_or_equal:today',
                'event_time' => 'required',
                'price' => 'required|numeric|min:0'
            ]);
        
            $event = ThemeParkEvent::create($data);
            
            return back()->with('success', 'Activity scheduled successfully! Event ID: ' . $event->id);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to schedule event: ' . $e->getMessage());
        }
    }

    // 3. SELL TICKET AT ENTRANCE (Manual Sale)
    public function manualSale(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'event_id' => 'required|exists:theme_park_events,id',
            'tickets' => 'required|integer|min:1'
        ]);

        $user = User::where('email', $request->email)->first();
        $event = ThemeParkEvent::with('promotion')->findOrFail($request->event_id);

        // Check capacity using model method
        if ($request->tickets > $event->remaining_capacity) {
            return back()->with('error', "Not enough capacity! Only {$event->remaining_capacity} tickets remaining.");
        }

        // Calculate price with discount
        $basePrice = $event->price * $request->tickets;
        $totalPrice = $basePrice;
        $promotion = $event->promotion;

        if ($promotion) {
            $totalPrice = $promotion->applyDiscount($basePrice);
        }

        EventBooking::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'tickets' => $request->tickets,
            'total_price' => $totalPrice,
            'status' => 'valid'
        ]);

        $message = "Ticket sold at entrance!";
        if ($promotion) {
            $message .= " (Discount applied: {$promotion->discount_percent}%)";
        }

        return back()->with('success', $message);
    }

    // 4. VALIDATE TICKET (Scanner Interface)
    public function validateTicket(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:event_bookings,id'
        ]);

        $booking = EventBooking::with('user')->findOrFail($request->booking_id);

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
        $request->validate([
            'event_id' => 'required|exists:theme_park_events,id',
            'title' => 'required|string|max:255',
            'discount_percent' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string'
        ]);

        // Check if event already has promotion
        $existingPromo = EventPromotion::where('event_id', $request->event_id)->first();
        
        if ($existingPromo) {
            return back()->with('error', 'This event already has an active promotion. Remove it first.');
        }

        EventPromotion::create([
            'event_id' => $request->event_id,
            'title' => $request->title,
            'discount_percent' => $request->discount_percent,
            'description' => $request->description
        ]);

        return back()->with('success', 'Promotion created successfully!');
    }

    // 6. DELETE PROMOTION
    public function deletePromotion($id)
    {
        $promotion = EventPromotion::findOrFail($id);
        $promotion->delete();

        return back()->with('success', 'Promotion deleted successfully!');
    }

    // 7. DELETE EVENT
    public function destroy($id)
    {
        $event = ThemeParkEvent::findOrFail($id);
        
        // Check if event has bookings
        if ($event->bookings()->count() > 0) {
            return back()->with('error', 'Cannot delete event with existing bookings.');
        }

        $event->delete();
        return back()->with('success', 'Event deleted successfully!');
    }
}