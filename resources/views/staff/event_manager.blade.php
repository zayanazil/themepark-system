@extends('layouts.admin')

@section('content')
    <style>
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px; }
        .stat-box { background: #333; color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-box h3 { color: #ccc; font-size: 0.9em; margin: 0 0 5px 0; }
        .stat-box h2 { font-size: 2em; margin: 0; }
        .badge { padding: 3px 8px; border-radius: 10px; color: white; font-size: 0.8em; }
        .bg-valid { background: green; }
        .bg-redeemed { background: gray; }
        .bg-cancelled { background: red; }
        .alert { padding: 12px 15px; border-radius: 4px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 25px; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        .btn-green { background: #28a745; }
        .btn-red { background: #dc3545; }
        .promo-block { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
    </style>

    <h1>🎡 Park Operations</h1>
    
    @if(session('error'))
        <div class="alert alert-error">
            s{{ session('error') }}
        </div>
    @endif

    <div class="stat-grid">
        <div class="stat-box">
            <h3>Revenue</h3>
            <h2>${{ number_format($totalRevenue) }}</h2>
        </div>
        <div class="stat-box">
            <h3>Sold</h3>
            <h2>{{ $totalTicketsSold }}</h2>
        </div>
        <div class="stat-box">
            <h3>Events</h3>
            <h2>{{ $events->count() }}</h2>
        </div>
        <div class="stat-box">
            <h3>Promos</h3>
            <h2>{{ $promotions->count() }}</h2>
        </div>
    </div>

    <div class="grid">
        <div>
            <div class="card" style="border-top: 5px solid green;">
                <h3>✅ Ticket Scanner</h3>
                <form action="/themepark/validate" method="POST" style="display:flex; gap:10px;">
                    @csrf
                    <input type="number" name="booking_id" placeholder="Ticket ID #" required style="flex:1; padding:8px; border:1px solid #ccc;">
                    <button style="background: green; color: white; border: none; padding: 8px 15px; cursor: pointer;">Scan</button>
                </form>
            </div>

            <div class="card" style="border-top: 5px solid #007bff;">
                <h3>🎟️ Sell Ticket</h3>
                <form action="/themepark/sell" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="User Email" required style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc;">
                    <select name="event_id" style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc;">
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">
                                {{ $event->name }} - ${{ $event->price }}
                                @if($event->promotion)
                                    ({{ $event->promotion->discount_percent }}% OFF)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="tickets" value="1" min="1" placeholder="Number of tickets" style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ccc;">
                    <button style="width:100%; background: #007bff; color: white; border: none; padding: 10px; cursor: pointer;">Sell</button>
                </form>
            </div>
        </div>

        <div class="card">
            <h3>📅 Schedule Activity</h3>
            <form action="/manage/events" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Event Name" required style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc;">
                <textarea name="description" placeholder="Event Description" required 
                    style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc; min-height: 80px;">
                </textarea>
                <select name="category" style="width:100%; padding:8px; margin-bottom:5px; border:1px solid #ccc;">
                    <option>Ride</option><option>Show</option><option>Beach Event</option><option>General</option>
                </select>
                <div style="display:flex; gap:10px;">
                    <input type="date" name="event_date" required style="flex:1; padding:8px; border:1px solid #ccc;">
                    <input type="time" name="event_time" required style="flex:1; padding:8px; border:1px solid #ccc;">
                </div>
                <div style="display:flex; gap:10px; margin-top:5px;">
                    <input type="number" name="capacity" placeholder="Capacity" required style="flex:1; padding:8px; border:1px solid #ccc;">
                    <input type="number" name="price" placeholder="Price $" required step="0.01" style="flex:1; padding:8px; border:1px solid #ccc;">
                </div>
                <button style="width:100%; background: #007bff; color: white; border: none; padding: 10px; margin-top:10px; cursor: pointer;">Schedule Event</button>
            </form>
        </div>
    </div>

    <div class="section">
        <h2 style="border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-top: 0; color: #333;">🏷️ Event Promotions</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 25px; margin-top: 15px;">
            <!-- Create Promotion Form -->
            <div class="promo-block">
                <h3 style="margin-top: 0; color: #333;">Create New Promotion</h3>
                <form action="/themepark/promotions" method="POST">
                    @csrf
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Select Event:</label>
                        <select name="event_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="">Choose event...</option>
                            @foreach($events as $event)
                                @php
                                    $hasPromo = $event->promotion;
                                @endphp
                                <option value="{{ $event->id }}" {{ $hasPromo ? 'disabled' : '' }}>
                                    {{ $event->name }} {{ $hasPromo ? '(Has Active Promo)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Title:</label>
                        <input type="text" name="title" placeholder="e.g. Early Bird Special" required 
                               style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Discount Percentage:</label>
                        <input type="number" name="discount_percent" placeholder="e.g. 20" required min="1" max="100" 
                               style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Description:</label>
                        <textarea name="description" rows="3" placeholder="Describe the offer..." 
                                  style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; resize: vertical;"></textarea>
                    </div>

                    <button class="btn btn-green" style="width: 100%; padding: 10px; font-size: 1em;">Create Promotion</button>
                </form>
            </div>

            <!-- Active Promotions List -->
            <div>
                <h3 style="margin-top: 0; color: #333; margin-bottom: 15px;">Active Promotions</h3>
                @if($promotions->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        @foreach($promotions as $promo)
                            <div style="border: 1px solid #dee2e6; padding: 18px; border-radius: 6px; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div style="flex: 1;">
                                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                            <strong style="font-size: 1.1em; color: #333;">{{ $promo->title }}</strong>
                                            <span style="background: #28a745; color: white; padding: 4px 12px; border-radius: 12px; font-size: 0.85em; margin-left: 12px; font-weight: 600;">
                                                {{ $promo->discount_percent }}% OFF
                                            </span>
                                        </div>
                                        <div style="color: #666; margin-bottom: 8px; font-size: 0.9em;">
                                            🎡 {{ $promo->event->name }}
                                        </div>
                                        @if($promo->description)
                                            <p style="margin: 0; color: #333; line-height: 1.5;">{{ $promo->description }}</p>
                                        @endif
                                    </div>
                                    <div style="margin-left: 15px; flex-shrink: 0;">
                                        <form action="/themepark/promotions/{{ $promo->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-red" 
                                                    onclick="return confirm('Remove this promotion?')"
                                                    style="padding: 6px 12px; font-size: 0.9em;">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: #666; background: #f8f9fa; border-radius: 6px; border: 1px dashed #dee2e6;">
                        <p style="margin-bottom: 10px; font-size: 1.1em;">No active promotions yet</p>
                        <p style="color: #888;">Create your first promotional offer using the form on the left.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <h3>🧾 Recent Sales</h3>
        <table>
            <thead><tr><th>ID</th><th>User</th><th>Activity</th><th>Tickets</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($bookings->take(10) as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->event->name }}</td>
                    <td>{{ $booking->tickets }}</td>
                    <td>${{ number_format($booking->total_price, 2) }}</td>
                    <td><span class="badge bg-{{ $booking->status }}">{{ strtoupper($booking->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection