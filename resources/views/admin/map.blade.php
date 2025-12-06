@extends('layouts.admin')

@section('content')
    <h1>📍 Manage Map Locations</h1>

    <!-- Add success/error messages -->
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">

        <div class="card">
            <h3>Add Location Pin</h3>
            <form action="{{ route('map.store') }}" method="POST">
                @csrf
                <label>Location Name:</label><br>
                <input type="text" name="name" required style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"><br>

                <label>Latitude:</label><br>
                <input type="text" name="latitude" placeholder="e.g. 4.1755" required style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"><br>

                <label>Longitude:</label><br>
                <input type="text" name="longitude" placeholder="e.g. 73.5093" required style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"><br>

                <label>Description:</label><br>
                <input type="text" name="description" style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"><br><br>

                <button style="background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; width: 100%;">Add Pin</button>
            </form>
        </div>

        <div class="card">
            <h3>Map Points</h3>
            @if($locations->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Coordinates</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $loc)
                    <tr>
                        <td>{{ $loc->name }}</td>
                        <td>{{ $loc->latitude }}, {{ $loc->longitude }}</td>
                        <td>{{ $loc->description ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('map.destroy', $loc->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this location?')"
                                        style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 0.8em;">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <p style="color: #666; text-align: center; padding: 20px;">No map locations added yet.</p>
            @endif
        </div>
    </div>
@endsection