<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferry Tickets</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="container">
        <a href="/visitor/dashboard" class="back-link">← Back</a>
        
        <h1>Ferry Services</h1>
        
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
        
        @if($eligibleBooking)
            <div class="card eligible-card">
                <h3>Book Ferry Transfer</h3>
                <p>You are eligible (Booking at: <strong>{{ $eligibleBooking->hotel->name }}</strong>)</p>
                
                <form action="/ferry" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Select Trip</label>
                        <select name="ferry_trip_id">
                        @foreach($upcomingTrips as $trip)
                            @php
                                $remaining = $trip->capacity - $trip->tickets()->count();
                            @endphp
                        
                            <option value="{{ $trip->id }}" @if($remaining <= 0) disabled @endif>
                                {{ $trip->route_name }} |
                                {{ \Carbon\Carbon::parse($trip->departure_time)->format('Y-m-d\TH:i') }}
                                @if($remaining > 0)
                                    ({{ $remaining }} seats left)
                                @else
                                    — FULL
                                @endif
                            </option>
                        @endforeach
                        </select>
                    </div>
                    
                    <button type="submit">Get Ticket</button>
                </form>
            </div>
        @else
            <div class="card restricted-card">
                <p><strong>Restricted:</strong> You must have a confirmed Hotel Booking to use the Ferry.</p>
                <a href="/hotels" class="link-button">Go Book a Hotel</a>
            </div>
        @endif
        
        <h3>My Ferry Tickets</h3>
        <ul class="tickets-list">
            @foreach($myTickets as $ticket)
                <li class="ticket-item">
                    <div>
                        <strong>{{ $ticket->trip->route_name }}</strong>
                        <div class="ticket-details">
                            {{ \Carbon\Carbon::parse($ticket->trip->departure_time)->format('M d, H:i') }}
                        </div>
                        <span class="ticket-status">{{ $ticket->status }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>