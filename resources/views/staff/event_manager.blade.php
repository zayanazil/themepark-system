@extends('layouts.admin')

@section('content')
    <style>
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px; }
        .stat-box { background: #333; color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-box h3 { color: #ccc; font-size: 0.9em; margin: 0 0 5px 0; }
        .stat-box h2 { font-size: 2em; margin: 0; }
        .badge { padding: 3px 8px; border-radius: 10px; color: white; font-size: 0.8em; }
        .bg-valid { background: green; }
        .bg-redeemed { background: gray; }
        .bg-cancelled { background: red; }
    </style>

    <h1>🎡 Park Operations</h1>

    <div class="stat-grid">
        <div class="stat-box">
            <h3>Revenue</h3>
            <h2>${{ number_format($totalRevenue) }}</h2>
        </div>
        <div class="stat-box">
            <h3>Sold</h3>
            <h2>{{ $totalTicketsSold }}</h2>
        </div>
        <div class="stat-box">
            <h3>Events</h3>
            <h2>{{ $events->count() }}</h2>
        </div>
        <div class="stat-box">
            <h3>Promos</h3>
            <h2>{{ $promotions->count() }}</h2>
        </div>
    </div>

    <div class="grid">
        <div>
            <div class="card" style="border-top: 5px solid green;">
                <h3>✅ Ticket Scanner</h3>
                <form action="/themepark/validate" method="POST" style="display:flex; gap:10px;">
                    @csrf
                    <input type="number" name="booking_id" placeholder="Ticket ID #" required style="flex:1; padding:8px; border:1px solid #ccc;">
                    <button style="background: green; color: white; border: none; padding: 8px 15px; cursor: pointer;">Scan</button>
                </form>
            </div>

            <div class="card" style="border-top: 5px solid #007bff;">
                <h3>🎟️ Sell Ticket</h3>
                <form action="/themepark/sell" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="User Email" required style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc;">
                    <select name="event_id" style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc;">
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }} (${{ $event->price }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="tickets" value="1" min="1" style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ccc;">
                    <button style="width:100%; background: #007bff; color: white; border: none; padding: 10px; cursor: pointer;">Sell</button>
                </form>
            </div>
        </div>

        <div class="card">
            <h3>📅 Schedule Activity</h3>
            <form action="/manage/events" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Name" required style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc;">
                <select name="category" style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc;">
                    <option>Ride</option><option>Show</option><option>Beach Event</option><option>General</option>
                </select>
                <div style="display:flex; gap:10px;">
                    <input type="date" name="event_date" required style="flex:1; padding:8px; border:1px solid #ccc;">
                    <input type="time" name="event_time" required style="flex:1; padding:8px; border:1px solid #ccc;">
                </div>
                <div style="display:flex; gap:10px; margin-top:5px;">
                    <input type="number" name="capacity" placeholder="Cap" required style="flex:1; padding:8px; border:1px solid #ccc;">
                    <input type="number" name="price" placeholder="$" required style="flex:1; padding:8px; border:1px solid #ccc;">
                </div>
                <button style="width:100%; background: #007bff; color: white; border: none; padding: 10px; margin-top:10px; cursor: pointer;">Add</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h3>🧾 Recent Sales</h3>
        <table>
            <thead><tr><th>ID</th><th>User</th><th>Activity</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($bookings->take(10) as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->event->name }}</td>
                    <td><span class="badge bg-{{ $booking->status }}">{{ strtoupper($booking->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
