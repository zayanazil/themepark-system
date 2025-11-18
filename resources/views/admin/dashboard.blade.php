<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: sans-serif; padding: 30px; background-color: #f4f6f8; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 20px; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; text-align: center; transition: 0.2s; }
        .card:hover { background-color: #f9f9f9; border-color: #bbb; }
        .card a { text-decoration: none; color: #007bff; font-weight: bold; font-size: 18px; display: block; height: 100%; }
        .logout { margin-top: 30px; text-align: center; }
        button { background: #ff4d4d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}. Manage your application below.</p>

    <div class="grid">
        <div class="card">
            <a href="/manage/users">
                <h3>👥 Manage Users</h3>
                <p>Promote staff, view visitors.</p>
            </a>
        </div>

        <div class="card">
            <a href="/manage/ads">
                <h3>📢 Manage Ads</h3>
                <p>Create and edit advertisements.</p>
            </a>
        </div>

        <div class="card">
            <a href="/manage/map">
                <h3>📍 Manage Map</h3>
                <p>Add locations and pins.</p>
            </a>
        </div>

        <div class="card">
            <a href="/manage/hotels">
                <h3>🏨 Hotel Overview</h3>
                <p>View all hotels and rooms.</p>
            </a>
        </div>
    </div>

    <div class="logout">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>

</body>
</html>
