<!DOCTYPE html>
<html>
<head>
    <title>Theme Park Events</title>
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
            max-width: 1000px;
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
            margin-top: 40px;
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
        
        .events-table {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 32px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        
        th {
            padding: 16px;
            text-align: left;
            color: #2d3748;
            font-weight: 600;
            font-size: 14px;
        }
        
        td {
            padding: 16px;
            border-top: 1px solid #e2e8f0;
            color: #4a5568;
        }
        
        tr:hover {
            background: #f7fafc;
        }
        
        .event-name {
            color: #1a202c;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .event-description {
            color: #718096;
            font-size: 14px;
        }
        
        .event-price {
            color: #1a202c;
            font-weight: 600;
            font-size: 16px;
        }
        
        .booking-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        input[type="number"] {
            width: 70px;
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }
        
        input[type="number"]:focus {
            outline: none;
            border-color: #4a5568;
        }
        
        button {
            background: #2d3748;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        button:hover {
            background: #1a202c;
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
            color: #4a5568;
        }
        
        .ticket-item strong {
            color: #1a202c;
        }
        
        @media (max-width: 768px) {
            .events-table {
                overflow-x: auto;
            }
            
            table {
                min-width: 600px;
            }
        }
    </style>
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
                            <form action="/events/book" method="POST" class="booking-form">
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