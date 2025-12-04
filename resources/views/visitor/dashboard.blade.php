<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Park Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
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
        
        .nav {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            gap: 24px;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        
        .nav h2 {
            color: #1a202c;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav a {
            text-decoration: none;
            color: #2d3748;
            font-weight: 500;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .nav a:hover {
            color: #1a202c;
            text-decoration: underline;
        }
        
        .nav form {
            margin-left: auto;
        }
        
        .nav button {
            background: #2d3748;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            font-family: inherit;
        }
        
        .nav button:hover {
            background: #1a202c;
        }
        
        .success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }
        
        .section {
            margin-bottom: 32px;
        }
        
        h2 {
            color: #1a202c;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        h3 {
            color: #1a202c;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 16px;
        }
        
        .card h3 {
            color: #1a202c;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 16px 0;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .card p {
            color: #718096;
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            color: #718096;
            font-weight: 600;
            font-size: 13px;
            text-align: left;
            padding: 8px 0;
            border-bottom: 2px solid #e2e8f0;
        }
        
        td {
            padding: 12px 0;
            border-bottom: 1px solid #f7fafc;
            color: #4a5568;
            font-size: 14px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .status-confirmed,
        .status-valid {
            color: #16a34a;
            font-weight: 600;
        }
        
        .status-cancelled {
            color: #dc2626;
            font-weight: 600;
        }
        
        .status-pending {
            color: #ea580c;
            font-weight: 600;
        }
        
        .news-card {
            border-left: 4px solid #2d3748;
        }
        
        .news-card h3 {
            border-bottom: none;
            padding-bottom: 8px;
            font-size: 16px;
        }
        
        .news-card p {
            color: #4a5568;
            line-height: 1.6;
        }
        
        .ad-body {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            margin-top: 8px;
        }
        
        .ad-image {
            width: 60%;
            height: auto;
            border-radius: 6px;
            margin-bottom: 12px;
            flex-shrink: 0;
        }
        
        .ad-text {
            margin: 0;
            flex: 1;
        }
        
        .location-item {
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f7fafc;
        }
        
        .location-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .location-name {
            color: #1a202c;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 4px;
        }
        
        .location-description {
            color: #718096;
            font-size: 14px;
        }
        
        @media (max-width: 968px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .ad-body {
                flex-direction: column;
            }
            
            .ad-image {
                width: 100%;
                max-width: 400px;
            }
        }
        
        @media (max-width: 640px) {
            .nav {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            
            .nav form {
                margin-left: 0;
                width: 100%;
            }
            
            .nav button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="nav">
        <h2>
            <i data-lucide="ferris-wheel"></i>
            Theme Park App
        </h2>
        <a href="/hotels">
            <i data-lucide="hotel"></i>
            Book Hotel
        </a>
        <a href="/ferry">
            <i data-lucide="ship"></i>
            Ferry Tickets
        </a>
        <a href="/events">
            <i data-lucide="ticket"></i>
            Events
        </a>
        <form method='POST' action='/logout'>
            @csrf
            <button>Logout</button>
        </form>
    </div>

    @if(session('success'))
    <div class="success">
        {{ session('success') }}
    </div>
    @endif

    <div class="container">

        <!-- LEFT COLUMN: PURCHASES -->
        <div>
            <div class="section">
                <h2>
                    <i data-lucide="shopping-bag"></i>
                    My Activity & Purchases
                </h2>

                <!-- 1. HOTELS -->
                <div class="card">
                    <h3>
                        <i data-lucide="hotel"></i>
                        Hotel Stays
                    </h3>
                    @if($myHotelBookings->isEmpty())
                        <p>No bookings yet.</p>
                    @else
                        <table>
                            <tr>
                                <th>Hotel</th>
                                <th>Room</th>
                                <th>Dates</th>
                                <th>Status</th>
                            </tr>
                            @foreach($myHotelBookings as $booking)
                            <tr>
                                <td>{{ $booking->hotel->name }}</td>
                                <td>{{ $booking->room_type }}</td>
                                <td>{{ $booking->check_in }}<br>to {{ $booking->check_out }}</td>
                                <td class="status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                </div>

                <!-- 2. FERRY -->
                <div class="card">
                    <h3>
                        <i data-lucide="ship"></i>
                        Ferry Tickets
                    </h3>
                    @if($myFerryTickets->isEmpty())
                        <p>No tickets yet.</p>
                    @else
                        <table>
                            <tr>
                                <th>Time</th>
                                <th>Linked Hotel</th>
                                <th>Status</th>
                            </tr>
                            @foreach($myFerryTickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ferry_time }}</td>
                                <td>{{ $ticket->hotelBooking->hotel->name ?? 'N/A' }}</td>
                                <td class="status-{{ $ticket->status }}">{{ ucfirst($ticket->status) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                </div>

                <!-- 3. EVENTS -->
                <div class="card">
                    <h3>
                        <i data-lucide="ticket"></i>
                        Event Tickets
                    </h3>
                    @if($myEventBookings->isEmpty())
                        <p>No events booked.</p>
                    @else
                        <table>
                            <tr>
                                <th>Event</th>
                                <th>Date</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                            @foreach($myEventBookings as $booking)
                            <tr>
                                <td>{{ $booking->event->name }}</td>
                                <td>{{ $booking->event->event_date }}</td>
                                <td>{{ $booking->tickets }}</td>
                                <td>${{ $booking->total_price }}</td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ADS & MAP -->
        <div>
            <div class="section">
                <h3>
                    <i data-lucide="megaphone"></i>
                    News
                </h3>
                @foreach($ads as $ad)
                <div class="card news-card">
                    <h3>{{ $ad->title }}</h3>
                    <div class="ad-body">
                        @if($ad->image_url)
                        <img src="{{ asset($ad->image_url) }}"
                             alt="{{ $ad->title }}"
                             class="ad-image">
                        @endif
                        <p class="ad-text">
                            {{ $ad->content }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="section">
                <h3>
                    <i data-lucide="map-pin"></i>
                    Map Points
                </h3>
                <div class="card">
                    @foreach($locations as $loc)
                        <div class="location-item">
                            <div class="location-name">{{ $loc->name }}</div>
                            <div class="location-description">{{ $loc->description }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>