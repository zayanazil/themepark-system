<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h2>Create Account</h2>
        <p class="subtitle">Sign up to get started</p>
        
        @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
        @endif
        
        <form action="/register" method="POST">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required placeholder="Your full name">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="you@example.com">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Create a password">
            </div>
            
            <button type="submit">Create Account</button>
        </form>
        
        <p class="footer">
            Already registered? <a href="/login">Sign in</a>
        </p>
    </div>
</body>
</html>