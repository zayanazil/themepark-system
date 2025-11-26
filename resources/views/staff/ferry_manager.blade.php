<!DOCTYPE html>
<html>
<head>
    <title>Ferry Operations</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f6f8; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h2 { margin-top: 0; border-bottom: 2px solid #007bff; padding-bottom: 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        input, select { padding: 8px; width: 100%; margin: 5px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        .badge { padding: 3px 8px; border-radius: 10px; color: white; font-size: 0.8em; }
        .status-valid { background: green; }
        .status-boarded { background: gray; }
        .status-cancelled { background: red; }
    </style>
</head>
<body>

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>⚓ Ferry Operations Dashboard</h1>
        <form method='POST' action='/logout'>@csrf <button style="background:#dc3545; width:auto;">Logout</button></form>
    </div>

    @if(session('success')) <div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:15px;">{{ session('success') }}</div> @endif
    @if(session('error')) <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px;">{{ session('error') }}</div> @endif

    <div class="grid">

        <div>
            <div class="card">
                <h2>📅 Manage Schedule</h2>
                <form action="/ferry/trips" method="POST">
                    @csrf
                    <label>Route Name:</label>
                    <input type="text" name="route_name" placeholder="e.g. Male' to Resort" required>

                    <label>Departure Time:</label>
                    <input type="datetime-local" name="departure_time" required>

                    <label>Capacity:</label>
                    <input type="number" name="capacity" value="50" required>

                    <button>Add Trip</button>
                </form>

                <h3>Upcoming Trips</h3>
                <table>
                    <thead><tr><th>Route</th><th>Time</th><th>Sold</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($trips as $trip)
                        <tr>
                            <td>{{ $trip->route_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($trip->departure_time)->format('M d, H:i') }}</td>
                            <td>{{ $trip->tickets_count }} / {{ $trip->capacity }}</td>
                            <td>
                                <form action="/ferry/trips/{{ $trip->id }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button style="background:red; padding:2px; font-size:0.8em;">X</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <div class="card" style="border-left: 5px solid green;">
                <h2>🎫 Issue Ticket / Validation</h2>
                <p>Verify if a customer has a hotel booking and issue a ferry pass.</p>

                <form action="/ferry/tickets" method="POST">
                    @csrf
                    <label>Customer Email:</label>
                    <input type="email" name="email" required placeholder="customer@example.com">

                    <label>Assign Trip:</label>
                    <select name="ferry_trip_id">
                        @foreach($trips as $trip)
                            <option value="{{ $trip->id }}">
                                {{ $trip->route_name }} - {{ \Carbon\Carbon::parse($trip->departure_time)->format('M d, H:i') }}
                            </option>
                        @endforeach
                    </select>

                    <button style="background: green;">Check Eligibility & Issue Ticket</button>
                </form>
            </div>
        </div>

    </div>

    <div class="card">
        <h2>📋 Passenger Lists (All Trips)</h2>
        <table>
            <thead>
                <tr>
                    <th>Passenger</th>
                    <th>Trip Details</th>
                    <th>Linked Hotel</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allTickets as $ticket)
                <tr>
                    <td>{{ $ticket->user->name }}</td>
                    <td>
                        {{ $ticket->trip->route_name }}<br>
                        <small>{{ \Carbon\Carbon::parse($ticket->trip->departure_time)->format('M d, H:i') }}</small>
                    </td>
                    <td>{{ $ticket->hotelBooking->hotel->name ?? 'Unknown' }}</td>
                    <td><span class="badge status-{{ $ticket->status }}">{{ strtoupper($ticket->status) }}</span></td>
                    <td>
                        @if($ticket->status === 'valid')
                            <form action="/ferry/tickets/{{ $ticket->id }}" method="POST" style="display:inline;">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="boarded">
                                <button style="background: #333; width: auto; font-size: 0.8em; padding: 5px;">Mark Boarded</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
