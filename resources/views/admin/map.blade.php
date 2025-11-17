<h1>Manage Map Locations</h1>
<a href="/admin/dashboard">Back</a>
<form action="/manage/map" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Location Name"><br>
    <input type="text" name="latitude" placeholder="Lat (e.g. 40.7128)"><br>
    <input type="text" name="longitude" placeholder="Lng (e.g. -74.0060)"><br>
    <button>Add Pin</button>
</form>
<ul>
    @foreach($locations as $loc) <li>{{ $loc->name }} ({{ $loc->latitude }}, {{ $loc->longitude }})</li> @endforeach
</ul>
