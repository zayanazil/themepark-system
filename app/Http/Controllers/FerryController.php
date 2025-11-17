<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use Illuminate\Support\Facades\Auth;

class FerryController extends Controller
{
    public function index()
    {
        // Check if user has a confirmed hotel booking
        $eligibleBooking = HotelBooking::where('user_id', Auth::id())
                                ->where('status', 'confirmed')
                                ->latest()
                                ->first();

        $myTickets = FerryTicket::where('user_id', Auth::id())->get();

        return view('visitor.ferry', compact('eligibleBooking', 'myTickets'));
    }

    public function store(Request $request)
    {
        $booking = HotelBooking::where('user_id', Auth::id())
                        ->where('status', 'confirmed')
                        ->first();

        if (!$booking) {
            return back()->with('error', 'You must have a confirmed hotel booking to book a ferry.');
        }

        FerryTicket::create([
            'user_id' => Auth::id(),
            'hotel_booking_id' => $booking->id,
            'ferry_time' => $request->ferry_time
        ]);

        return back()->with('success', 'Ferry ticket issued successfully!');
    }
}
