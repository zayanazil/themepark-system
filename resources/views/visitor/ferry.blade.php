<!DOCTYPE html>
<html>
<head>
    <title>Ferry Tickets</title>
</head>
<body style="padding: 20px; font-family: sans-serif;">
    <a href="/">← Back to Dashboard</a>
    <h1>Ferry Services</h1>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif
    @if(session('error')) <p style="color: red;">{{ session('error') }}</p> @endif

    @if($eligibleBooking)
        <div style="border: 1px solid #ccc; padding: 20px; background: #eef;">
            <h3>Issue Ferry Ticket</h3>
            <p>You are eligible because you have a booking at <strong>{{ $eligibleBooking->hotel->name }}</strong>.</p>
            <form action="/ferry" method="POST">
                @csrf
                <label>Select Time:</label>
                <select name="ferry_time">
                    <option>09:00 AM</option>
                    <option>12:00 PM</option>
                    <option>03:00 PM</option>
                    <option>06:00 PM</option>
                </select>
                <button type="submit">Get Ticket</button>
            </form>
        </div>
    @else
        <div style="padding: 20px; background: #fee;">
            <p><strong>Restricted:</strong> You must have a confirmed Hotel Booking to use the Ferry.</p>
            <a href="/hotels">Go Book a Hotel</a>
        </div>
    @endif

    <h3>My Ferry Tickets</h3>
    <ul>
        @foreach($myTickets as $ticket)
            <li>Time: {{ $ticket->ferry_time }} (Status: {{ $ticket->status }})</li>
        @endforeach
    </ul>
</body>
</html>
