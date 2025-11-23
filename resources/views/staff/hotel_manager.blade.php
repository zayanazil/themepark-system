<!DOCTYPE html>
<html>
<head>
    <title>Hotel Manager Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f8f9fa; }
        .section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 25px; }
        h2 { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-top: 0; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background-color: #e9ecef; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        .btn-green { background: #28a745; }
        .btn-red { background: #dc3545; }
        .btn-blue { background: #007bff; }
        input, select, textarea { padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 4px; }
        .badge { padding: 5px 10px; border-radius: 12px; font-weight: bold; font-size: 0.9em; }
        .status-confirmed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-pending { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h1>🏨 Hotel Management</h1>
            @if(auth()->user()->role === 'admin')
                <a href="/admin/dashboard" style="text-decoration:none; color: #007bff;">← Back to Admin Dashboard</a>
            @endif
        </div>
        <form method='POST' action='/logout'>@csrf <button class="btn btn-red">Logout</button></form>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px;">
            {{ session('success') }}
        </div>
    @endif


    @if(auth()->user()->role === 'admin')
    <div class="section" style="border: 2px solid #007bff;">
        <h2 style="color: #007bff;">Admin: Infrastructure</h2>
        <p>Only Admins can build new hotels.</p>
        <form action="/manage/hotels" method="POST">
            @csrf
            <input type="text" name="name" placeholder="New Hotel Name" required>
            <input type="number" name="room_count" placeholder="Total Rooms" required>
            <button type="submit" class="btn btn-blue">Build Hotel</button>
        </form>
    </div>
    @endif


    <div class="section">
        <h2>🛏️ Manage Availability</h2>
        <p>Update room capacities for existing hotels.</p>
        <table>
            <thead>
                <tr>
                    <th>Hotel Name</th>
                    <th>Total Rooms</th>
                    <th>Update Capacity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotels as $hotel)
                <tr>
                    <td>{{ $hotel->name }}</td>
                    <td>{{ $hotel->room_count }} Rooms</td>
                    <td>
                        <form action="/manage/hotels/{{ $hotel->id }}/rooms" method="POST" style="display:flex; gap:10px;">
                            @csrf @method('PUT')
                            <input type="number" name="room_count" value="{{ $hotel->room_count }}" style="width: 80px; margin:0;">
                            <button class="btn btn-blue">Update</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="section">
        <h2>📅 Booking Management</h2>
        <table>
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Hotel</th>
                    <th>Dates</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allBookings as $booking)
                <tr>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->hotel->name }}</td>
                    <td>{{ $booking->check_in }} <br><small>to {{ $booking->check_out }}</small></td>
                    <td>
                        <span class="badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td>
                        @if($booking->status === 'pending')
                            <form action="/manage/bookings/{{ $booking->id }}" method="POST" style="display:inline;">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="confirmed">
                                <button class="btn btn-green">Confirm</button>
                            </form>
                            <form action="/manage/bookings/{{ $booking->id }}" method="POST" style="display:inline;">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="btn btn-red">Cancel</button>
                            </form>
                        @elseif($booking->status === 'confirmed')
                            <form action="/manage/bookings/{{ $booking->id }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="btn btn-red">Cancel Booking</button>
                            </form>
                        @else
                            <span style="color:gray;">No actions</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="section">
        <h2>🏷️ Promotional Offers</h2>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">

            <div style="background: #f1f1f1; padding: 15px; border-radius: 5px;">
                <h3>Create New Offer</h3>
                <form action="/manage/promotions" method="POST">
                    @csrf
                    <label>Select Hotel:</label><br>
                    <select name="hotel_id" style="width:100%">
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                        @endforeach
                    </select><br>

                    <label>Title:</label><br>
                    <input type="text" name="title" placeholder="e.g. Summer Sale" required style="width:100%"><br>

                    <label>Discount:</label><br>
                    <input type="text" name="discount_percent" placeholder="e.g. 15% OFF" required style="width:100%"><br>

                    <label>Description:</label><br>
                    <textarea name="description" rows="3" style="width:100%"></textarea><br>

                    <button class="btn btn-green" style="width:100%">Post Offer</button>
                </form>
            </div>

            <div>
                <h3>Active Promotions</h3>
                <ul>
                    @foreach($promotions as $promo)
                        <li style="margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                            <strong>{{ $promo->title }} ({{ $promo->discount_percent }})</strong><br>
                            <small>at {{ $promo->hotel->name }}</small><br>
                            {{ $promo->description }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</body>
</html>
