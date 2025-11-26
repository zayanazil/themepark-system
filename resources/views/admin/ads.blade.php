@extends('layouts.admin')

@section('content')
    <h1>📢 Manage Advertisements</h1>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">

        <div class="card">
            <h3>Post New Ad</h3>
            <form action="/manage/ads" method="POST">
                @csrf
                <label>Title:</label><br>
                <input type="text" name="title" required style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"><br>

                <label>Content:</label><br>
                <textarea name="content" rows="4" required style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"></textarea><br>

                <label>Image URL (Optional):</label><br>
                <input type="text" name="image_url" style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"><br><br>

                <button style="background: green; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; width: 100%;">Post Ad</button>
            </form>
        </div>

        <div class="card">
            <h3>Active Ads</h3>
            @foreach($ads as $ad)
                <div style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                    <h4 style="margin: 0 0 5px 0;">{{ $ad->title }}</h4>
                    <p style="margin: 0; color: #555;">{{ $ad->content }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
