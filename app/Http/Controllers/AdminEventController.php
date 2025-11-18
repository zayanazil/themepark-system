<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThemeParkEvent;
use App\Models\EventBooking;

class AdminEventController extends Controller
{
    // 1. Show the Dashboard (Events + Guest List)
    public function index()
    {
        $events = ThemeParkEvent::all();

        // Fetch bookings to show the guest list
        $bookings = EventBooking::with(['user', 'event'])->latest()->get();

        return view('staff.event_manager', compact('events', 'bookings'));
    }

    // 2. Create a new Event
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'capacity' => 'required|integer',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'price' => 'required|integer'
        ]);

        ThemeParkEvent::create($data);
        return back()->with('success', 'Event Created');
    }

    // 3. Delete an Event
    public function destroy($id)
    {
        ThemeParkEvent::destroy($id);
        return back()->with('success', 'Event Deleted');
    }
}
