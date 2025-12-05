@extends('layouts.admin')

@section('content')
<style>
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
    details { margin-top: 15px; }
    summary { cursor: pointer; padding: 10px; background: #f8f9fa; border-radius: 4px; font-weight: bold; }
    summary:hover { background: #e9ecef; }
    .hotel-block { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
</style>

<h1>🏨 Hotel Management</h1>

@if(session('error'))
    <div style="background:#f8d7da; color:#721c24; padding:15px; margin-bottom:20px; border-radius:5px;">
        {{ session('error') }}
    </div>
@endif

@if(auth()->user()->role === 'admin')
<div class="section" style="border: 2px solid #007bff;">
    <h2 style="color: #007bff;">Admin: Create New Hotel</h2>
    <p>Only Admins can create new hotels.</p>
    <form action="/manage/hotels" method="POST">
        @csrf
        <input type="text" name="name" placeholder="New Hotel Name" required>
        <button type="submit" class="btn btn-blue">Create Hotel</button>
    </form>
</div>
@endif

<div class="section">
    <h2>🛏️ Manage Rooms</h2>
    <p>Add and manage individual rooms for each hotel.</p>
    
    @foreach($hotels as $hotel)
    <div class="hotel-block">
        <h3 style="margin-top: 0;">{{ $hotel->name }}</h3>
        
        @if($hotel->rooms->count() > 0)
        <table style="margin-bottom: 15px;">
            <thead>
                <tr>
                    <th>Room #</th>
                    <th>Type</th>
                    <th>Price/Night</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotel->rooms as $room)
                <tr>
                    <td>Room #{{ $room->id }}</td>
                    <td>{{ $room->type }}</td>
                    <td>${{ $room->price }}</td>
                    <td>
                        <form action="/manage/rooms/{{ $room->id }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-red" onclick="return confirm('Delete this room?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color: #666;">No rooms yet.</p>
        @endif

        <details>
            <summary>+ Add New Room</summary>
            <form action="/manage/rooms" method="POST" style="margin-top: 10px; padding: 15px; background: white; border-radius: 4px;">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                
                <label>Room Type:</label><br>
                <select name="type" required style="width: 200px;">
                    <option value="">Select type...</option>
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Suite">Suite</option>
                    <option value="Family">Family</option>
                </select><br>

                <label>Price per Night:</label><br>
                <input type="number" name="price" placeholder="e.g. 150" required min="0" step="0.01" style="width: 200px;"><br>
                
                <button type="submit" class="btn btn-green" style="margin-top: 10px;">Add Room</button>
            </form>
        </details>
    </div>
    @endforeach
</div>

<div class="section">
    <h2>📅 Booking Management</h2>
    <table>
        <thead>
            <tr>
                <th>Guest</th>
                <th>Hotel</th>
                <th>Room</th>
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
                <td>
                    @if($booking->room_id)
                        Room #{{ $booking->room_id }}<br>
                        <small style="color: #666;">{{ $booking->room_type }}</small>
                    @else
                        {{ $booking->room_type }}
                    @endif
                </td>
                <td>{{ $booking->check_in }} <br><small>to {{ $booking->check_out }}</small></td>
                <td>
                    <span class="badge status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                </td>
                <td>
                    @if($booking->status === 'pending')
                        <form action="/manage/bookings/{{ $booking->id }}" method="POST" style="display:inline; margin-right: 5px;">
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
                <select name="hotel_id" style="width:100%" required>
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
            <ul style="list-style: none; padding: 0;">
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
@endsection