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
</body>
</html>
