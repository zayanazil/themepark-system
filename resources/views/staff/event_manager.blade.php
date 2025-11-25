<!DOCTYPE html>
<html>
<head>
    <title>Theme Park Manager</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f6f8; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }

        /* Global Headings */
        h2 { margin-top: 0; color: #333; border-bottom: 2px solid #ff9800; padding-bottom: 10px; }
        h3 { margin-top: 0; color: #555; }

        /* FIXED: Stat Box specific styles to force white text */
        .stat-box { background: #333; color: white; padding: 20px; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .stat-box h3 { color: #ccc; font-size: 0.9em; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; border: none; }
        .stat-box h2 { color: white; font-size: 2.5em; border: none; padding: 0; margin: 0; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #eee; padding: 8px; text-align: left; }
        input, select, textarea { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { cursor: pointer; padding: 10px; background: #ff9800; color: white; border: none; border-radius: 4px; width: 100%; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 0.8em; color: white; }
        .bg-valid { background: green; }
        .bg-redeemed { background: gray; }
        .bg-cancelled { background: red; }
    </style>
</head>
<body>

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>🎡 Theme Park Operations</h1>
        <form method='POST' action='/logout'>@csrf <button style="background:#dc3545; width:auto;">Logout</button></form>
    </div>

    @if(session('success')) <div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:15px;">{{ session('success') }}</div> @endif
    @if(session('error')) <div style="background:#f8d7da; color:#721c24; padding:10px; margin-bottom:15px;">{{ session('error') }}</div> @endif

    <div class="grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 20px;">
        <div class="stat-box">
            <h3>Revenue</h3>
            <h2>${{ number_format($totalRevenue) }}</h2>
        </div>
        <div class="stat-box">
            <h3>Tickets Sold</h3>
            <h2>{{ $totalTicketsSold }}</h2>
        </div>
        <div class="stat-box">
            <h3>Active Events</h3>
            <h2>{{ $events->count() }}</h2>
        </div>
        <div class="stat-box">
            <h3>Active Promos</h3>
            <h2>{{ $promotions->count() }}</h2>
        </div>
    </div>

    <div class="grid">

        <div>
            <div class="card" style="border-left: 5px solid green;">
                <h2>✅ On-Site Ticket Validation</h2>
                <p>Enter Ticket ID to redeem entry.</p>
                <form action="/themepark/validate" method="POST" style="display:flex; gap:10px;">
                    @csrf
                    <input type="number" name="booking_id" placeholder="Ticket ID #" required>
                    <button style="background: green;">Validate / Scan</button>
                </form>
            </div>

            <div class="card" style="border-left: 5px solid #007bff;">
                <h2>🎟️ Sell Ticket (Entrance)</h2>
                <form action="/themepark/sell" method="POST">
                    @csrf
                    <label>Visitor Email (Must be registered):</label>
                    <input type="email" name="email" required>

                    <label>Select Activity:</label>
                    <select name="event_id">
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">
                                {{ $event->name }} (${{ $event->price }}) - {{ $event->category }}
                            </option>
                        @endforeach
                    </select>

                    <label>Quantity:</label>
                    <input type="number" name="tickets" value="1" min="1">

                    <button style="background: #007bff;">Process Sale</button>
                </form>
            </div>

            <div class="card">
                <h2>🏷️ Create Promotion</h2>
                <form action="/themepark/promote" method="POST">
                    @csrf
                    <label>Activity:</label>
                    <select name="event_id">
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="title" placeholder="Promo Title (e.g. Kids Free)" required>
                    <input type="text" name="discount_details" placeholder="Details (e.g. Under 5s enter free)" required>
                    <button>Launch Promo</button>
                </form>

                <h4 style="margin-bottom:5px;">Active Promos:</h4>
                <ul>
                    @foreach($promotions as $promo)
                        <li><strong>{{ $promo->title }}:</strong> {{ $promo->discount_details }} ({{ $promo->event->name }})</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div>
            <div class="card">
                <h2>📅 Schedule Activity</h2>
                <form action="/manage/events" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="text" name="name" placeholder="Event Name" required>
                        <select name="category">
                            <option>Ride</option>
                            <option>Show</option>
                            <option>Beach Event</option>
                            <option>General</option>
                        </select>
                    </div>
                    <textarea name="description" placeholder="Description"></textarea>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label>Date:</label>
                            <input type="date" name="event_date" required>
                        </div>
                        <div>
                            <label>Time:</label>
                            <input type="time" name="event_time" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="number" name="capacity" placeholder="Capacity" required>
                        <input type="number" name="price" placeholder="Price ($)" required>
                    </div>

                    <button>Add to Schedule</button>
                </form>
            </div>

            <div class="card">
                <h2>📊 Capacity Monitor</h2>
                <table>
                    <thead>
                        <tr><th>Activity</th><th>Category</th><th>Sold/Cap</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        @php
                            $sold = $event->bookings->where('status','!=','cancelled')->sum('tickets');
                            $percent = $event->capacity > 0 ? ($sold / $event->capacity) * 100 : 0;
                            $color = $percent > 90 ? 'red' : ($percent > 50 ? 'orange' : 'green');
                        @endphp
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td><small>{{ $event->category }}</small></td>
                            <td>
                                <span style="color:{{ $color }}; font-weight:bold;">
                                    {{ $sold }} / {{ $event->capacity }}
                                </span>
                            </td>
                            <td>
                                <form action="/manage/events/{{ $event->id }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button style="padding:2px; font-size:0.8em; background:red; width:auto;">X</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>🧾 Ticket Sales Log</h2>
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Guest</th>
                    <th>Activity</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->user->name }} ({{ $booking->user->email }})</td>
                    <td>{{ $booking->event->name }}</td>
                    <td><span class="badge bg-{{ $booking->status }}">{{ strtoupper($booking->status) }}</span></td>
                    <td>${{ $booking->total_price }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
