<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Create Account</h2>

@if ($errors->any())
<p style="color:red">{{ $errors->first() }}</p>
@endif

<form action="/register" method="POST">
    @csrf

    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

<p>Already registered? <a href="/login">Login</a></p>

</body>
</html>
