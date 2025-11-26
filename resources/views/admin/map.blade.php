@extends('layouts.admin')

@section('content')
    <h1>📍 Manage Map Locations</h1>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">

        <div class="card">
            <h3>Add Location Pin</h3>
            <form action="/manage/map" method="POST">
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
            <table>
                <thead>
                    <tr><th>Name</th><th>Coordinates</th><th>Description</th></tr>
                </thead>
                <tbody>
                    @foreach($locations as $loc)
                    <tr>
                        <td>{{ $loc->name }}</td>
                        <td>{{ $loc->latitude }}, {{ $loc->longitude }}</td>
                        <td>{{ $loc->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
