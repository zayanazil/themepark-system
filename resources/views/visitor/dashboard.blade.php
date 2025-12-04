<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Park Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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

    <div class="dashboard-grid">

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
                    <!-- ADD MAP HERE -->
                    <div id="map" style="height: 400px; border-radius: 8px; margin-bottom: 16px;"></div>
                    <!-- Keep the list below the map (optional) -->
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
        const map = L.map('map', {
            maxBounds: [
                    [4.18216, 73.520],  // Southwest corner
                    [4.16474, 73.49753]   // Northeast corner
                ],
            maxBoundsViscosity: 0.5
        }).setView([4.17454, 73.51004], 15);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap, © CartoDB',
            minZoom: 14, // prevent users zooming out too much (delete if issue) | ideally would be 15, 14 for mobile support
            maxZoom: 19
        }).addTo(map);
    </script>

</body>
</html>