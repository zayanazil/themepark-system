@extends('layouts.admin')

@section('content')
    <h1>👥 User Management</h1>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Update Role</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span style="font-weight:bold; text-transform:uppercase; font-size: 0.8em; background: #eee; padding: 3px 8px; border-radius: 4px;">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>
                        <form action="/manage/users/{{ $user->id }}" method="POST" style="display: flex; gap: 5px;">
                            @csrf @method('PUT')
                            <select name="role" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                                <option value="visitor" {{ $user->role == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                <option value="hotel_manager" {{ $user->role == 'hotel_manager' ? 'selected' : '' }}>Hotel Manager</option>
                                <option value="ferry_staff" {{ $user->role == 'ferry_staff' ? 'selected' : '' }}>Ferry Staff</option>
                                <option value="theme_park_staff" {{ $user->role == 'theme_park_staff' ? 'selected' : '' }}>Park Staff</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button style="background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Save</button>
                        </form>
                    </td>
                    <td>
                        @if(auth()->id() !== $user->id)
                            <form action="/manage/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                @csrf @method('DELETE')
                                <button style="background: red; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">X</button>
                            </form>
                        @else
                            <small>(You)</small>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
