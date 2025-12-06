<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThemeParkEvent;
use App\Models\EventBooking;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        // Get upcoming events with promotions, ordered by date
        $events = ThemeParkEvent::with('promotion')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->get();
        
        $myEvents = EventBooking::where('user_id', Auth::id())
            ->with('event')
            ->latest()
            ->get();
        
        return view('visitor.events', compact('events', 'myEvents'));
    }

    public function bookEvent(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:theme_park_events,id',
            'tickets' => 'required|integer|min:1|max:10'
        ]);

        $event = ThemeParkEvent::with('promotion')->findOrFail($request->event_id);

        // Check capacity using the model method
        if ($event->isSoldOut()) {
            return back()->with('error', 'This event is sold out.');
        }

        if ($request->tickets > $event->remaining_capacity) {
            return back()->with('error', "Not enough capacity! Only {$event->remaining_capacity} tickets left.");
        }

        // Calculate price with discount
        $basePrice = $event->price * $request->tickets;
        $totalPrice = $basePrice;
        $promotion = $event->promotion;

        if ($promotion) {
            $totalPrice = $promotion->applyDiscount($basePrice);
        }

        // Create booking
        EventBooking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'tickets' => $request->tickets,
            'total_price' => $totalPrice
        ]);

        // Build success message
        $message = "{$request->tickets} ticket(s) booked successfully for {$event->name}!";
        
        if ($promotion) {
            $savings = $basePrice - $totalPrice;
            $message .= " {$promotion->title}: {$promotion->discount_percent}% OFF applied! You saved $" . number_format($savings, 2) . "! Final: $" . number_format($totalPrice, 2);
        } else {
            $message .= " Total: $" . number_format($totalPrice, 2);
        }

        return back()->with('success', $message);
    }
}