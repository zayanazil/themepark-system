<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h2>Welcome!</h2>
        <p class="subtitle">Please sign in to your account</p>
        
        @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
        @endif
        
        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="you@example.com" class="@error('email') error-input @enderror">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password" class="@error('password') error-input @enderror">
            </div>
            
            <button type="submit">Sign In</button>
        </form>
        
        <p class="footer">
            Don't have an account? <a href="/register">Create one</a>
        </p>
    </div>
</body>
</html>