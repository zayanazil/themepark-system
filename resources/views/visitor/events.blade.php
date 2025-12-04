<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Park Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">← Back to Dashboard</a>
        
        <h1>Upcoming Events</h1>
        
        @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
        @endif
        
        <div class="events-table">
            <table>
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
                            <div class="event-name">{{ $event->name }}</div>
                            <div class="event-description">{{ $event->description }}</div>
                        </td>
                        <td>{{ $event->event_date }} at {{ $event->event_time }}</td>
                        <td><span class="event-price">${{ $event->price }}</span></td>
                        <td>
                            <form action="/events/book" method="POST" class="event-booking-form">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <input type="number" name="tickets" placeholder="Qty" min="1" max="10" value="1">
                                <button type="submit">Book</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <h3>My Tickets</h3>
        <ul class="tickets-list">
            @foreach($myEvents as $booking)
                <li class="ticket-item">
                    <strong>{{ $booking->event->name }}</strong> - {{ $booking->tickets }} tickets (Total: ${{ $booking->total_price }})
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>