@extends('layouts.admin')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Managing: {{ $hotel->name }}</h1>

        @if(auth()->user()->role === 'admin')
            <form action="/manage/hotels/{{ $hotel->id }}" method="POST" onsubmit="return confirm('Delete this entire hotel?');">
                @csrf @method('DELETE')
                <button style="background: red; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Delete Hotel</button>
            </form>
        @endif
    </div>

    <div class="card">
        <h2 style="border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-top:0;">🔑 Room Management</h2>
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
                <h3>Add New Room</h3>
                <form action="/manage/rooms" method="POST">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                    <label>Room Number:</label><br>
                    <input type="text" name="room_number" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

                    <label>Room Type:</label><br>
                    <select name="type" required style="width:100%; padding:8px; margin-bottom:10px;">
                        <option value="Single">Single</option>
                        <option value="Couple">Couple</option>
                        <option value="Family">Family</option>
                        <option value="Deluxe">Deluxe</option>
                    </select><br>

                    <label>Price ($):</label><br>
                    <input type="number" name="price" required style="width:100%; padding:8px; margin-bottom:20px;"><br>

                    <button style="width:100%; background: green; color: white; padding: 10px; border:none; cursor: pointer; border-radius:4px;">Add Room</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <h2 style="border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-top:0;">📅 Bookings</h2>
        @if($bookings->isEmpty())
            <p>No active bookings for this hotel.</p>
        @else
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
                        <td>{{ ucfirst($booking->status) }}</td>
                        <td>
                            <a href="/manage/booking/{{ $booking->id }}/edit" style="color: blue; text-decoration: underline;">Edit/Cancel</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
