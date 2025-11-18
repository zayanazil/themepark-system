<!DOCTYPE html>
<html>
<head><title>Event Manager</title></head>
<body style="padding:20px; font-family: sans-serif;">
    <h1>Theme Park Event Management</h1>
    <form method='POST' action='/logout'>@csrf <button>Logout</button></form>

    <div style="margin-top:20px; border:1px solid #ccc; padding:20px; background:#eef;">
        <h3>Create New Event</h3>
        <form action="/manage/events" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Event Name" required><br><br>
            <textarea name="description" placeholder="Description"></textarea><br><br>
            <label>Capacity:</label> <input type="number" name="capacity" required>
            <label>Price ($):</label> <input type="number" name="price" required><br><br>
            <label>Date:</label> <input type="date" name="event_date" required>
            <label>Time:</label> <input type="time" name="event_time" required><br><br>
            <button type="submit">Create Event</button>
        </form>
    </div>

    <h3>Existing Events</h3>
    <ul>
        @foreach($events as $event)
            <li>
                <strong>{{ $event->name }}</strong> on {{ $event->event_date }}
                (Sold: {{ $event->bookings->sum('tickets') }}/{{ $event->capacity }})
                <form action="/manage/events/{{ $event->id }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button style="color:red">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
    <hr>
    <h3>🎟️ Guest List (Ticket Sales)</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Event</th>
                <th>Tickets Qty</th>
                <th>Total Paid</th>
                <th>Purchase Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->user->name }}</td>
                <td>{{ $booking->event->name }}</td>
                <td>{{ $booking->tickets }}</td>
                <td>${{ $booking->total_price }}</td>
                <td>{{ $booking->created_at->format('M d, H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
