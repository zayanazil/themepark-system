<!DOCTYPE html>
<html>
<head>
    <title>Hotel Manager Dashboard</title>
</head>
<body style="padding:20px; font-family: sans-serif;">

    @if(auth()->user()->role === 'admin')
        <a href="/admin/dashboard" style="display:inline-block; margin-bottom: 20px; text-decoration:none; color: #007bff; font-weight: bold;">
            ← Back to Admin Dashboard
        </a>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Hotel Management</h1>

        <form method='POST' action='/logout'>
            @csrf <button style="cursor: pointer;">Logout</button>
        </form>
    </div>

    @if(session('success'))
        <p style="color: green; background: #eaffea; padding: 10px; border: 1px solid green;">
            {{ session('success') }}
        </p>
    @endif

    <div style="border:1px solid #ccc; padding:15px; background:#f9f9f9; margin-bottom: 30px;">
        <h3>Add New Hotel</h3>
        <form action="/manage/hotels" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Hotel Name" required style="padding: 5px;">
            <input type="number" name="room_count" placeholder="Total Rooms" required style="padding: 5px;">
            <button type="submit" style="padding: 5px 10px; cursor: pointer;">Add Hotel</button>
        </form>
    </div>

    <hr>

    <h3>Guest Bookings List</h3>
    @if($allBookings->isEmpty())
        <p>No bookings found.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr style="background-color: #eee;">
                    <th>Guest Name</th>
                    <th>Hotel</th>
                    <th>Check In/Out</th>
                    <th>Guests</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allBookings as $booking)
                <tr>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->hotel->name }}</td>
                    <td>{{ $booking->check_in }} to {{ $booking->check_out }}</td>
                    <td>{{ $booking->guests }}</td>
                    <td>
                        <span style="font-weight: bold; color: {{ $booking->status == 'confirmed' ? 'green' : 'orange' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
