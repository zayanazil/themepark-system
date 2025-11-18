<!DOCTYPE html>
<html>
<head><title>Ferry Management</title></head>
<body style="padding:20px; font-family: sans-serif;">
    <h1>⛴️ Ferry Ticket Control</h1>
    <form method='POST' action='/logout'>@csrf <button>Logout</button></form>

    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif

    <h3>Issued Tickets</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Passenger</th>
                <th>Hotel</th>
                <th>Ferry Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->hotelBooking->user->name ?? 'Unknown' }}</td>
                <td>{{ $ticket->hotelBooking->hotel->name ?? 'N/A' }}</td>
                <td>{{ $ticket->ferry_time }}</td>
                <td>
                    <span style="color: {{ $ticket->status == 'valid' ? 'green' : 'red' }}">
                        {{ strtoupper($ticket->status) }}
                    </span>
                </td>
                <td>
                    @if($ticket->status == 'valid')
                        <form action="/manage/ferry/{{ $ticket->id }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <button style="background:red; color:white; border:none; padding:5px;">Cancel Ticket</button>
                        </form>
                    @else
                        <form action="/manage/ferry/{{ $ticket->id }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="valid">
                            <button style="background:green; color:white; border:none; padding:5px;">Re-Validate</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
