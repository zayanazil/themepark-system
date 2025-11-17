<!DOCTYPE html>
<html>
<head><title>Visitor Dashboard</title></head>
<body>
    <h1>Welcome Visitor!</h1>
    <ul>
        <li><a href="/hotels">Book a Hotel</a></li>
        <li><a href="/ferry">Book Ferry Ticket</a></li>
        <li><a href="/events">Buy Event Tickets</a></li>
    </ul>
    <br>
    <form method='POST' action='/logout'>@csrf<button>Logout</button></form>
</body>
</html>
