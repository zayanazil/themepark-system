<!DOCTYPE html>
<html>
<head>
    <title>Book a Hotel</title>
</head>
<body style="padding: 20px; font-family: sans-serif;">
    <a href="/">← Back to Dashboard</a>
    <h1>Book a Hotel Room</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <div style="border: 1px solid #ccc; padding: 20px; margin-bottom: 20px;">
        <h3>New Reservation</h3>
        <form action="/book-hotel" method="POST">
            @csrf
            <label>Select Hotel:</label>
            <select name="hotel_id">
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}">{{ $hotel->name }} (Total Rooms: {{ $hotel->room_count }})</option>
                @endforeach
            </select><br><br>

            <label>Check In:</label> <input type="date" name="check_in" required>
            <label>Check Out:</label> <input type="date" name="check_out" required><br><br>
            <label>Guests:</label> <input type="number" name="guests" min="1" value="1"><br><br>

            <button type="submit">Book Now</button>
        </form>
    </div>

    <h3>My Bookings</h3>
    <ul>
        @foreach($myBookings as $booking)
            <li>
                <strong>{{ $booking->hotel->name }}</strong> |
                {{ $booking->check_in }} to {{ $booking->check_out }} |
                Status: {{ $booking->status }}
            </li>
        @endforeach
    </ul>
</body>
</html>
