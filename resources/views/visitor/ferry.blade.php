<!DOCTYPE html>
<html>
<head>
    <title>Ferry Tickets</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #f7fafc;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .back-link {
            display: inline-block;
            color: #2d3748;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        h1 {
            color: #1a202c;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        
        h3 {
            color: #1a202c;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 32px;
        }
        
        .eligible-card {
            border-color: #2d3748;
        }
        
        .restricted-card {
            background: #fee;
            border-color: #fcc;
        }
        
        .card p {
            color: #4a5568;
            margin-bottom: 16px;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #2d3748;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        select {
            width: 100%;
            max-width: 500px;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            background: white;
            cursor: pointer;
        }
        
        select:focus {
            outline: none;
            border-color: #4a5568;
        }
        
        button {
            background: #2d3748;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        button:hover {
            background: #1a202c;
        }
        
        .link-button {
            display: inline-block;
            color: #2d3748;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            border: 2px solid #2d3748;
            border-radius: 6px;
            transition: all 0.2s;
        }
        
        .link-button:hover {
            background: #2d3748;
            color: white;
        }
        
        .tickets-list {
            list-style: none;
        }
        
        .ticket-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }
        
        .ticket-item strong {
            color: #1a202c;
            font-size: 16px;
        }
        
        .ticket-details {
            color: #718096;
            margin-top: 4px;
        }
        
        .ticket-status {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 12px;
            background: #e2e8f0;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            color: #2d3748;
        }
    </style>
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
                                <option value="{{ $trip->id }}">
                                    {{ $trip->route_name }} | {{ \Carbon\Carbon::parse($trip->departure_time)->format('M d, H:i A') }}
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