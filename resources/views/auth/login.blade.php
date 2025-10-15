<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

@if ($errors->any())
<p style="color:red">{{ $errors->first() }}</p>
@endif

<form action="/login" method="POST">
    @csrf
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<p>Don’t have an account? <a href="/register">Register</a></p>

</body>
</html>
