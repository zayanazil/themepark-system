@extends('layouts.admin')

@section('content')
    <h1>📢 Manage Advertisements</h1>

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
            <h3>Post New Ad</h3>
            <form action="{{ route('ads.store') }}" method="POST">
                @csrf
                <label>Title:</label><br>
                <input type="text" name="title" required style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"><br>

                <label>Content:</label><br>
                <textarea name="content" rows="4" required style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"></textarea><br>

                <label>Image URL (Optional):</label><br>
                <input type="text" name="image_url" placeholder="https://example.com/image.jpg" style="width:100%; padding:8px; margin:5px 0; border:1px solid #ccc;"><br><br>

                <button style="background: green; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; width: 100%;">Post Ad</button>
            </form>
        </div>

        <div class="card">
            <h3>Active Ads ({{ $ads->count() }})</h3>
            
            @if($ads->count() > 0)
                @foreach($ads as $ad)
                    <div style="border-bottom: 1px solid #eee; padding: 15px; margin-bottom: 10px; background: #f9f9f9; border-radius: 5px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <h4 style="margin: 0 0 5px 0;">{{ $ad->title }}</h4>
                            <form action="{{ route('ads.destroy', $ad->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this advertisement?')"
                                        style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 0.8em;">
                                    Delete
                                </button>
                            </form>
                        </div>
                        <p style="margin: 0 0 10px 0; color: #555;">{{ $ad->content }}</p>
                        @if($ad->image_url)
                            <div style="margin-top: 10px;">
                                <small style="color: #777;">Image URL:</small><br>
                                <a href="{{ $ad->image_url }}" target="_blank" style="color: #007bff; font-size: 0.9em;">
                                    {{ Str::limit($ad->image_url, 40) }}
                                </a>
                            </div>
                        @endif
                        <div style="margin-top: 10px; font-size: 0.8em; color: #888;">
                            Posted: {{ $ad->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 30px; color: #666;">
                    <p>No advertisements yet.</p>
                    <p>Create your first ad using the form on the left!</p>
                </div>
            @endif
        </div>
    </div>
@endsection