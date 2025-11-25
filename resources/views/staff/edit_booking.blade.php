<!DOCTYPE html>
<html>
<head><title>Edit Booking</title></head>
<body style="font-family: sans-serif; padding: 30px; background: #f4f6f8;">

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2>Edit Booking #{{ $booking->id }}</h2>
        <p>Guest: <strong>{{ $booking->user->name }}</strong></p>
        <p>Hotel: <strong>{{ $booking->hotel->name }}</strong></p>

        <form action="/manage/booking/{{ $booking->id }}" method="POST">
            @csrf
            @method('PUT')

            <label><strong>Check In:</strong></label><br>
            <input type="date" name="check_in" value="{{ $booking->check_in }}" required style="width:100%; padding: 8px; margin-bottom: 10px;"><br>

            <label><strong>Check Out:</strong></label><br>
            <input type="date" name="check_out" value="{{ $booking->check_out }}" required style="width:100%; padding: 8px; margin-bottom: 10px;"><br>

            <label><strong>Room Type:</strong></label><br>
            <select name="room_type" style="width:100%; padding: 8px; margin-bottom: 10px;">
                @foreach(['Single', 'Couple', 'Family', 'Deluxe'] as $type)
                    <option value="{{ $type }}" {{ $booking->room_type == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select><br>

            <label><strong>Status:</strong></label><br>
            <select name="status" style="width:100%; padding: 8px; margin-bottom: 20px;">
                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select><br>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Save Changes</button>
                <a href="/manage/hotel/{{ $booking->hotel_id }}" style="background: #ccc; color: black; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Cancel</a>
            </div>
        </form>
    </div>

</body>
</html>
