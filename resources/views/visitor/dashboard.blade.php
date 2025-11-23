<!DOCTYPE html>
<html>
<head>
    <title>Theme Park Home</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f0f8ff; }
        .nav { background: white; padding: 15px; border-radius: 8px; display: flex; gap: 20px; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .nav a { text-decoration: none; color: #007bff; font-weight: bold; }
        .section { margin-top: 30px; }
        .container { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }

        .card { background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card h3 { margin-top: 0; color: #333; border-bottom: 1px solid #eee; padding-bottom: 5px; }

        table { width: 100%; border-collapse: collapse; font-size: 0.9em; }
        th, td { padding: 8px; border-bottom: 1px solid #eee; text-align: left; }
        th { color: #666; }

        .status-confirmed, .status-valid { color: green; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>

    <div class="nav">
        <h2>🎡 Theme Park App</h2>
        <a href="/hotels">🏨 Book Hotel</a>
        <a href="/ferry">⛴️ Ferry Tickets</a>
        <a href="/events">🎫 Events</a>
        <form method='POST' action='/logout' style="margin-left:auto;">@csrf<button>Logout</button></form>
    </div>

    @if(session('success')) <p style="color: green; font-weight: bold;">{{ session('success') }}</p> @endif

    <div class="container">

        <!-- LEFT COLUMN: PURCHASES -->
        <div>
            <div class="section">
                <h2>🎒 My Activity & Purchases</h2>

                <!-- 1. HOTELS -->
                <div class="card">
                    <h3>🏨 Hotel Stays</h3>
                    @if($myHotelBookings->isEmpty())
                        <p>No bookings yet.</p>
                    @else
                        <table>
                            <tr><th>Hotel</th><th>Room</th><th>Dates</th><th>Status</th></tr>
                            @foreach($myHotelBookings as $booking)
                            <tr>
                                <td>{{ $booking->hotel->name }}</td>
                                <td>{{ $booking->room_type }}</td>
                                <td>{{ $booking->check_in }} <br>to {{ $booking->check_out }}</td>
                                <td class="status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                </div>

                <!-- 2. FERRY -->
                <div class="card">
                    <h3>⛴️ Ferry Tickets</h3>
                    @if($myFerryTickets->isEmpty())
                        <p>No tickets yet.</p>
                    @else
                        <table>
                            <tr><th>Time</th><th>Linked Hotel</th><th>Status</th></tr>
                            @foreach($myFerryTickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ferry_time }}</td>
                                <td>{{ $ticket->hotelBooking->hotel->name ?? 'N/A' }}</td>
                                <td class="status-{{ $ticket->status }}">{{ ucfirst($ticket->status) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                </div>

                <!-- 3. EVENTS -->
                <div class="card">
                    <h3>🎫 Event Tickets</h3>
                    @if($myEventBookings->isEmpty())
                        <p>No events booked.</p>
                    @else
                        <table>
                            <tr><th>Event</th><th>Date</th><th>Qty</th><th>Total</th></tr>
                            @foreach($myEventBookings as $booking)
                            <tr>
                                <td>{{ $booking->event->name }}</td>
                                <td>{{ $booking->event->event_date }}</td>
                                <td>{{ $booking->tickets }}</td>
                                <td>${{ $booking->total_price }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ADS & MAP -->
        <div>
            <div class="section">
                <h3>📢 News</h3>
                @foreach($ads as $ad)
                    <div class="card" style="border-left: 4px solid #ff9800;">
                        <h3>{{ $ad->title }}</h3>
                        <p>{{ $ad->content }}</p>
                    </div>
                @endforeach
            </div>

            <div class="section">
                <h3>📍 Map Points</h3>
                <div class="card">
                    @foreach($locations as $loc)
                        <div style="margin-bottom: 10px;">
                            <strong>{{ $loc->name }}</strong><br>
                            <small class="text-muted">{{ $loc->description }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

</body>
</html>
