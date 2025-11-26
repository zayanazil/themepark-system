<!DOCTYPE html>
<html>
<head><title>Ferry Tickets</title></head>
<body style="padding: 20px; font-family: sans-serif;">
    <a href="/visitor/dashboard">← Back</a>
    <h1>Ferry Services</h1>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif
    @if(session('error')) <p style="color: red;">{{ session('error') }}</p> @endif

    @if($eligibleBooking)
        <div style="border: 1px solid #007bff; padding: 20px; background: #eef; border-radius: 8px;">
            <h3>Book Ferry Transfer</h3>
            <p>✅ You are eligible (Booking at: <strong>{{ $eligibleBooking->hotel->name }}</strong>)</p>

            <form action="/ferry" method="POST">
                @csrf
                <label>Select Trip:</label><br>
                <select name="ferry_trip_id" style="padding: 8px; width: 300px; margin-top: 5px;">
                    @foreach($upcomingTrips as $trip)
                        <option value="{{ $trip->id }}">
                            {{ $trip->route_name }} | {{ \Carbon\Carbon::parse($trip->departure_time)->format('M d, H:i A') }}
                        </option>
                    @endforeach
                </select>
                <br><br>
                <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">Get Ticket</button>
            </form>
        </div>
    @else
        <div style="padding: 20px; background: #fee; border: 1px solid red; border-radius: 8px;">
            <p><strong>Restricted:</strong> You must have a confirmed Hotel Booking to use the Ferry.</p>
            <a href="/hotels">Go Book a Hotel</a>
        </div>
    @endif

    <h3>My Ferry Tickets</h3>
    <ul>
        @foreach($myTickets as $ticket)
            <li>
                <strong>{{ $ticket->trip->route_name }}</strong>
                ({{ \Carbon\Carbon::parse($ticket->trip->departure_time)->format('M d, H:i') }})
                - Status: <span style="font-weight: bold;">{{ $ticket->status }}</span>
            </li>
        @endforeach
    </ul>
</body>
</html>
