<!DOCTYPE html>
<html>
<head><title>Hotel Manager Dashboard</title></head>
<body style="padding:20px; font-family: sans-serif;">
    <h1>Hotel Management</h1>

    <form method='POST' action='/logout' style="margin-bottom: 20px;">
        @csrf <button>Logout</button>
    </form>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif

    <div style="border:1px solid #ccc; padding:15px; background:#f9f9f9;">
        <h3>Add New Hotel</h3>
        <form action="/manage/hotels" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Hotel Name" required>
            <input type="number" name="room_count" placeholder="Total Rooms" required>
            <button type="submit">Add Hotel</button>
        </form>
    </div>

    <hr>

    <h3>Guest Bookings List</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Hotel</th>
                <th>Check In/Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allBookings as $booking)
            <tr>
                <td>{{ $booking->user->name }}</td>
                <td>{{ $booking->hotel->name }}</td>
                <td>{{ $booking->check_in }} to {{ $booking->check_out }}</td>
                <td>{{ $booking->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
