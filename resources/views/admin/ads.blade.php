<h1>Manage Ads</h1>
<a href="/admin/dashboard">Back</a>
<form action="/manage/ads" method="POST">
    @csrf
    <input type="text" name="title" placeholder="Title"><br>
    <textarea name="content" placeholder="Content"></textarea><br>
    <button>Post Ad</button>
</form>
<ul>
    @foreach($ads as $ad) <li>{{ $ad->title }}</li> @endforeach
</ul>
