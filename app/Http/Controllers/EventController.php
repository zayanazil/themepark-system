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
        $events = ThemeParkEvent::all();
        $myEvents = EventBooking::where('user_id', Auth::id())->with('event')->get(); // requires EventBooking model to have event() relationship

        return view('visitor.events', compact('events', 'myEvents'));
    }

    public function bookEvent(Request $request)
    {
        $request->validate([
            'tickets' => 'required|integer|min:1'
        ]);

        $event = ThemeParkEvent::find($request->event_id);

        // Check Capacity
        $alreadyBooked = $event->bookings->sum('tickets');
        if ($alreadyBooked + $request->tickets > $event->capacity) {
            return back()->with('error', 'Not enough capacity! Only ' . ($event->capacity - $alreadyBooked) . ' tickets left.');
        }

        EventBooking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'tickets' => $request->tickets,
            'total_price' => $event->price * $request->tickets
        ]);

        return back()->with('success', 'Event booked successfully!');
    }
}
