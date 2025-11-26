@extends('layouts.admin')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>📑 System Reports</h1>
        <button onclick="window.print()" style="background: #34495e; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">🖨️ Print Report</button>
    </div>

    <div class="card">
        <h2>🏨 Hotel Performance</h2>
        <table>
            <thead>
                <tr>
                    <th>Hotel Name</th>
                    <th>Total Rooms</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotels as $hotel)
                <tr>
                    <td>{{ $hotel->name }}</td>
                    <td>{{ $hotel->rooms->count() }} physical rooms</td>
                    <td>Active</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>🎡 Theme Park Sales</h2>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventSales as $event)
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->category }}</td>
                    <td>${{ $event->price }}</td>
                    <td>{{ $event->bookings_count }}</td>
                    <td>${{ number_format($event->bookings_count * $event->price) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
