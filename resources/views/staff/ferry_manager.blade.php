@extends('layouts.admin')

@section('content')
    @if(session('error'))
        <div style="background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-weight:600;">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 10px 15px; border-radius: 6px; margin-bottom: 15px; font-size: 14px;">
            {{ $errors->first() }}
        </div>
    @endif
    <style>
        /* Specific styles for Ferry grid */
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .badge { padding: 3px 8px; border-radius: 10px; color: white; font-size: 0.8em; }
        .status-valid { background: green; }
        .status-boarded { background: gray; }
        .status-cancelled { background: red; }
    </style>

    <h1>⚓ Ferry Operations</h1>

    <div class="grid">
        <div class="card">
            <h3>📅 Manage Schedule</h3>
            <form action="/ferry/trips" method="POST" style="margin-bottom: 20px;">
                @csrf
                <input type="text" name="route_name" placeholder="Route Name" required style="width: 100%; padding: 8px; margin-bottom: 5px; border: 1px solid #ccc;">
                <input type="datetime-local" name="departure_time" required style="width: 100%; padding: 8px; margin-bottom: 5px; border: 1px solid #ccc;">
                <input type="number" name="capacity" value="50" required style="width: 100%; padding: 8px; margin-bottom: 5px; border: 1px solid #ccc;">
                <button style="width: 100%; background: #007bff; color: white; border: none; padding: 10px; cursor: pointer;">Add Trip</button>
            </form>

            <table>
                <thead><tr><th>Route</th><th>Time</th><th>Sold</th><th></th></tr></thead>
                <tbody>
                    @foreach($trips as $trip)
                    <tr>
                        <td>{{ $trip->route_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($trip->departure_time)->format('M d, h:i A') }}</td>
                        <td>{{ $trip->tickets_count }}</td>
                        <td>
                            <form action="/ferry/trips/{{ $trip->id }}" method="POST">
                                @csrf @method('DELETE')
                                <button style="background:red; color: white; border: none; padding: 2px 5px; cursor: pointer;">X</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card" style="border-top: 5px solid green;">
            <h3>🎫 Issue Ticket</h3>
            <form action="/ferry/tickets" method="POST">
                @csrf
                <label>Customer Email:</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;">
                <label>Assign Trip:</label>
                <select name="ferry_trip_id" style="width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;">
                    @foreach($trips as $trip)
                        <option value="{{ $trip->id }}">{{ $trip->route_name }} ({{ \Carbon\Carbon::parse($trip->departure_time)->format('h:i A') }})</option>
                    @endforeach
                </select>
                <button style="width: 100%; background: green; color: white; border: none; padding: 10px; cursor: pointer;">Issue Ticket</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h3>📋 Passenger Manifest</h3>
        <table>
            <thead>
                <tr>
                    <th>Passenger</th>
                    <th>Trip</th>
                    <th>Hotel Check</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allTickets as $ticket)
                <tr>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->trip->route_name }}</td>
                    <td>{{ $ticket->hotelBooking->hotel->name ?? 'N/A' }}</td>
                    <td><span class="badge status-{{ $ticket->status }}">{{ strtoupper($ticket->status) }}</span></td>
                    <td>
                        @if($ticket->status === 'valid')
                            <form action="/ferry/tickets/{{ $ticket->id }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="boarded">
                                <button style="background: #333; color: white; border: none; padding: 5px; cursor: pointer;">Board</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
