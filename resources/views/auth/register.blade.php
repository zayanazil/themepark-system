<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #f7fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 40px;
            width: 100%;
            max-width: 420px;
        }
        
        h2 {
            color: #1a202c;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            text-align: center;
        }
        
        .subtitle {
            color: #718096;
            text-align: center;
            margin-bottom: 32px;
            font-size: 14px;
        }
        
        .error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #2d3748;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: #4a5568;
            box-shadow: 0 0 0 3px rgba(74, 85, 104, 0.1);
        }
        
        button {
            width: 100%;
            background: #2d3748;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }
        
        button:hover {
            background: #1a202c;
        }
        
        button:active {
            background: #000;
        }
        
        .footer {
            text-align: center;
            margin-top: 24px;
            color: #718096;
            font-size: 14px;
        }
        
        .footer a {
            color: #2d3748;
            text-decoration: none;
            font-weight: 600;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
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