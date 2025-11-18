<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .success { color: green; font-weight: bold; background: #eaffea; padding: 10px; border: 1px solid green; }
        button { cursor: pointer; }
    </style>
</head>
<body>

    <a href="/admin/dashboard">← Back to Dashboard</a>
    <h1>Admin: User Management</h1>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Current Role</th>
                <th>Action (Promote/Demote)</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                <form action="/manage/users/{{ $user->id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <td>
                        <span style="font-weight:bold; text-transform:uppercase;">{{ $user->role }}</span>
                    </td>

                    <td>
                        <select name="role">
                            <option value="visitor" {{ $user->role == 'visitor' ? 'selected' : '' }}>Visitor</option>
                            <option value="hotel_manager" {{ $user->role == 'hotel_manager' ? 'selected' : '' }}>Hotel Manager</option>
                            <option value="ferry_staff" {{ $user->role == 'ferry_staff' ? 'selected' : '' }}>Ferry Staff</option>
                            <option value="theme_park_staff" {{ $user->role == 'theme_park_staff' ? 'selected' : '' }}>Theme Park Staff</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <button type="submit">Update</button>
                    </td>
                </form>

                <td>
                    @if(auth()->id() !== $user->id) <form action="/manage/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red;">Delete</button>
                        </form>
                    @else
                        <small>(You)</small>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
