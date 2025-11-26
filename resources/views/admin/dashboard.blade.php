@extends('layouts.admin')

@section('content')
    <h1>System Overview</h1>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="card">
            <h3 style="margin:0; color:#777;">Total Revenue</h3>
            <h2 style="margin:5px 0; color:green;">${{ number_format($totalRevenue) }}</h2>
        </div>
        <div class="card">
            <h3 style="margin:0; color:#777;">Total Users</h3>
            <h2 style="margin:5px 0;">{{ $totalUsers }}</h2>
        </div>
        <div class="card">
            <h3 style="margin:0; color:#777;">Hotel Revenue</h3>
            <h2 style="margin:5px 0;">${{ number_format($hotelRevenue) }}</h2>
        </div>
        <div class="card">
            <h3 style="margin:0; color:#777;">Park Revenue</h3>
            <h2 style="margin:5px 0;">${{ number_format($eventRevenue) }}</h2>
        </div>
    </div>

    <div class="card">
        <h3>Recent Hotel Bookings</h3>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Hotel</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBookings as $booking)
                <tr>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->hotel->name }}</td>
                    <td>${{ number_format($booking->total_price) }}</td>
                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
