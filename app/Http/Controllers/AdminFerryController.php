<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FerryTicket;

class AdminFerryController extends Controller
{
    public function index()
    {
        // Get all tickets with User info and the related Hotel Booking
        $tickets = FerryTicket::with(['hotelBooking.user', 'hotelBooking.hotel'])->latest()->get();
        return view('staff.ferry_manager', compact('tickets'));
    }

    // Allow staff to Cancel/Invalidate a ticket
    public function update(Request $request, $id)
    {
        $ticket = FerryTicket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        return back()->with('success', 'Ticket status updated.');
    }
}
