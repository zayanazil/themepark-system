<!DOCTYPE html>
<html>
<head>
    <title>Managing: {{ $hotel->name }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f8f9fa; }
        .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h2 { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-top:0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    </style>
</head>
<body>

    <a href="/hotel/selection">← Back to Selection</a>
    <h1>Managing: {{ $hotel->name }}</h1>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif

    <div class="section">
        <h2>🔑 Room Management</h2>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">

            <div>
                <h3>Room List</h3>
                <table>
                    <thead>
                        <tr><th>Room #</th><th>Type</th><th>Price</th></tr>
                    </thead>
                    <tbody>
                        @foreach($hotel->rooms as $room)
                        <tr>
                            <td><strong>{{ $room->room_number }}</strong></td>
                            <td>{{ $room->type }}</td>
                            <td>${{ $room->price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="background: #eef; padding: 15px; border-radius: 5px; height: fit-content;">
                <h3>Add Room</h3>
                <form action="/manage/rooms" method="POST">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                    <label>Room Number (e.g. 101):</label><br>
                    <input type="text" name="room_number" required style="width:90%"><br><br>

                    <label>Room Type:</label><br>
                    <select name="type" required style="width:90%; padding: 5px;">
                        <option value="Single">Single</option>
                        <option value="Couple">Couple</option>
                        <option value="Family">Family</option>
                        <option value="Deluxe">Deluxe</option>
                    </select><br><br>

                    <label>Price ($):</label><br>
                    <input type="number" name="price" required style="width:90%"><br><br>

                    <button style="width:100%; background: green; color: white; padding: 10px; border:none;">Add Room</button>
                </form>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>📅 Bookings</h2>
        <table>
            <thead>
                <tr><th>Guest</th><th>Type</th><th>Dates</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->room_type }}</td>
                    <td>{{ $booking->check_in }} - {{ $booking->check_out }}</td>
                    <td>{{ $booking->status }}</td>
                    <td><a href="/manage/booking/{{ $booking->id }}/edit">Edit</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
