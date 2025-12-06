<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Park Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .beach-badge {
            background: #f2e2a6;
            color: white;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-top: 4px;
        }
        .promo-badge {
            background: #16a34a;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }
        .capacity-warning {
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
        }
        .sold-out {
            background: #fee2e2;
            color: #991b1b;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .original-price {
            text-decoration: line-through;
            color: #9ca3af;
            font-size: 14px;
            margin-right: 8px;
        }
        .discounted-price {
            color: #16a34a;
            font-weight: 600;
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
                        <th>Capacity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>
                            <div class="event-name">
                                {{ $event->name }}
                                @if($event->promotion)
                                    <span class="promo-badge">{{ $event->promotion->discount_percent }}% OFF</span>
                                @endif
                            </div>
                            <div class="event-description">{{ $event->description }}</div>
                            @if($event->promotion)
                                <div style="color: #16a34a; font-size: 13px; margin-top: 4px;">
                                    {{ $event->promotion->title }}
                                </div>
                            @endif
                            @if(strtolower($event->category) === 'beach event')
                                <span class="beach-badge">Beach Event</span>
                            @endif
                        </td>
                        <td>{{ $event->event_date }} at {{ $event->event_time }}</td>
                        <td>
                            @if($event->isSoldOut())
                                <span class="sold-out">SOLD OUT</span>
                            @elseif($event->remaining_capacity <= 10)
                                <span class="capacity-warning">{{ $event->remaining_capacity }} left!</span>
                            @else
                                {{ $event->remaining_capacity }} available
                            @endif
                        </td>
                        <td>
                            @if($event->promotion)
                                <span class="original-price">${{ $event->price }}</span>
                                <span class="discounted-price">
                                    ${{ number_format($event->promotion->applyDiscount($event->price), 2) }}
                                </span>
                            @else
                                <span class="event-price">${{ $event->price }}</span>
                            @endif
                        </td>
                        <td>
                            @if($event->isSoldOut())
                                <button disabled style="opacity: 0.5; cursor: not-allowed;">Sold Out</button>
                            @else
                                <form action="/events/book" method="POST" class="booking-form">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <input type="number" name="tickets" placeholder="Qty" min="1" max="{{ min(10, $event->remaining_capacity) }}" value="1" required>
                                    <button type="submit">Book</button>
                                </form>
                            @endif
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
                    <strong>{{ $booking->event->name }}</strong> - {{ $booking->tickets }} tickets (Total: ${{ number_format($booking->total_price, 2) }})
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>