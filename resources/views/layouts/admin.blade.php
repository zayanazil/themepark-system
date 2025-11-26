<!DOCTYPE html>
<html>
<head>
    <title>Theme Park System</title>
    <style>
        body { margin: 0; font-family: sans-serif; display: flex; height: 100vh; background: #f4f6f8; }

        /* SIDEBAR STYLES */
        .sidebar { width: 250px; background: #2c3e50; color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #34495e; background: #1a252f; }
        .sidebar-header h3 { margin: 0; font-size: 1.2em; }
        .sidebar-menu { padding: 20px; overflow-y: auto; flex: 1; }
        .sidebar a { text-decoration: none; color: #ecf0f1; padding: 12px 15px; display: block; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .sidebar a:hover { background: #34495e; padding-left: 20px; }
        .sidebar a.active { background: #3498db; }
        .sidebar-footer { padding: 20px; border-top: 1px solid #34495e; }

        /* CONTENT AREA STYLES */
        .content { flex: 1; padding: 30px; overflow-y: auto; }

        /* COMMON UTILS */
        .btn-logout { background: #e74c3c; color: white; border: none; padding: 10px; width: 100%; border-radius: 5px; cursor: pointer; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h3>🎡 System Panel</h3>
            <small>{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</small>
        </div>

        <div class="sidebar-menu">

            @if(auth()->user()->role === 'admin')
                <a href="/admin/dashboard">📊 Dashboard</a>
                <a href="/manage/users">👥 User Management</a>
                <a href="/admin/reports">📑 Sales Reports</a>
                <a href="/manage/ads">📢 Manage Ads</a>
                <a href="/manage/map">📍 Manage Map</a>
                <div style="border-top: 1px solid #34495e; margin: 10px 0;"></div>
            @endif

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hotel_manager')
                <a href="/hotel/selection">🏨 Hotel Selection</a>
            @endif

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'ferry_staff')
                <a href="/ferry/dashboard">⛴️ Ferry Operations</a>
            @endif

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'theme_park_staff')
                <a href="/themepark/dashboard">🎢 Park Operations</a>
            @endif

        </div>

        <div class="sidebar-footer">
            <form method="POST" action="/logout">
                @csrf
                <button class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

</body>
</html>
