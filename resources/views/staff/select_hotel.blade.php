<!DOCTYPE html>
<html>
<head><title>Select Hotel</title></head>
<body style="font-family: sans-serif; padding: 40px; background: #f4f6f8; text-align: center;">

    <h1>🏨 Hotel Management Portal</h1>

    <div style="background: white; padding: 40px; border-radius: 8px; max-width: 400px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3>Select a Hotel to Manage</h3>

        <form action="/hotel/select" method="POST">
            @csrf
            <select name="hotel_id" style="width: 100%; padding: 10px; font-size: 16px; margin-bottom: 20px;">
                <option value="" disabled selected>-- Choose Hotel --</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                @endforeach
            </select>
            <br>
            <button type="submit" style="background: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; width: 100%;">
                Go to Dashboard
            </button>
        </form>
    </div>

    <br><br>
    <form method='POST' action='/logout'>@csrf <button>Logout</button></form>
</body>
</html>
