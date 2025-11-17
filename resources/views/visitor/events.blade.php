<!DOCTYPE html>
<html>
<head>
    <title>Theme Park Events</title>
</head>
<body style="padding: 20px; font-family: sans-serif;">
    <a href="/">← Back to Dashboard</a>
    <h1>Upcoming Events</h1>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif
    @if(session('error')) <p style="color: red;">{{ session('error') }}</p> @endif

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>
                    <strong>{{ $event->name }}</strong><br>
                    <small>{{ $event->description }}</small>
                </td>
                <td>{{ $event->event_date }} at {{ $event->event_time }}</td>
                <td>${{ $event->price }}</td>
                <td>
                    <form action="/events/book" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <input type="number" name="tickets" placeholder="Qty" min="1" max="10" style="width: 50px;">
                        <button type="submit">Book</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>My Tickets</h3>
    <ul>
        @foreach($myEvents as $booking)
            <li>{{ $booking->event->name }} - {{ $booking->tickets }} tickets (Total: ${{ $booking->total_price }})</li>
        @endforeach
    </ul>
</body>
</html>
