@extends('layouts.admin')

@section('content')
    <div style="text-align: center; margin-top: 50px;">
        <h1>🏨 Hotel Management Portal</h1>
        <p>Please select the hotel you wish to manage.</p>

        <div style="background: white; padding: 40px; border-radius: 8px; max-width: 400px; margin: 30px auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <form action="/hotel/select" method="POST">
                @csrf
                <select name="hotel_id" style="width: 100%; padding: 12px; font-size: 16px; margin-bottom: 20px; border:1px solid #ccc; border-radius:4px;">
                    <option value="" disabled selected>-- Choose Hotel --</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
                <button type="submit" style="background: #007bff; color: white; border: none; padding: 12px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; width: 100%;">
                    Go to Dashboard
                </button>
            </form>
        </div>
    </div>
@endsection
