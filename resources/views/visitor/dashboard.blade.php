<!DOCTYPE html>
<html>
<head>
    <title>Theme Park Home</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f0f8ff; }
        .nav { background: white; padding: 15px; border-radius: 8px; display: flex; gap: 20px; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .nav a { text-decoration: none; color: #007bff; font-weight: bold; }
        .section { margin-top: 30px; }
        .ad-card { background: white; padding: 20px; margin-bottom: 20px; border-left: 5px solid #ff9800; border-radius: 5px; }
        .map-pin { background: #e3f2fd; padding: 10px; margin-bottom: 5px; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="nav">
        <h2>🎡 Theme Park App</h2>
        <a href="/hotels">🏨 Book Hotel</a>
        <a href="/ferry">⛴️ Ferry Tickets</a>
        <a href="/events">🎫 Events</a>
        <form method='POST' action='/logout' style="margin-left:auto;">@csrf<button>Logout</button></form>
    </div>

    <div class="section">
        <h3>📢 Latest News & Offers</h3>
        @if($ads->count() == 0)
            <p>No news at the moment.</p>
        @endif

        @foreach($ads as $ad)
            <div class="ad-card">
                <h3>{{ $ad->title }}</h3>
                <p>{{ $ad->content }}</p>
                @if($ad->image_url)
                    <img src="{{ $ad->image_url }}" style="max-width: 200px; border-radius: 5px;">
                @endif
            </div>
        @endforeach
    </div>

    <div class="section">
        <h3>📍 Park Map Locations</h3>
        <div style="background: white; padding: 20px; border-radius: 8px;">
            @foreach($locations as $loc)
                <div class="map-pin">
                    <strong>{{ $loc->name }}</strong>
                    <small>({{ $loc->latitude }}, {{ $loc->longitude }})</small>
                    <br>
                    {{ $loc->description }}
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
