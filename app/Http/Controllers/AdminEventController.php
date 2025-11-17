<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThemeParkEvent;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = ThemeParkEvent::all();
        return view('staff.event_manager', compact('events'));
    }

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

    public function destroy($id)
    {
        ThemeParkEvent::destroy($id);
        return back()->with('success', 'Event Deleted');
    }
}
