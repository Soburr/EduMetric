<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #1e3a8a, #2563eb);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .title {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 13.5px;
        }

        .success {
            background: #dcfce7;
            color: #15803d;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 13.5px;
        }

        .field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #374151;
            margin-top: 14px;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 11px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
            box-sizing: border-box;
            font-family: inherit;
            color: #1f2937;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            font-size: 13px;
            color: #64748b;
        }

        .remember-row input[type="checkbox"] {
            width: auto;
            margin: 0;
            accent-color: #2563eb;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
        }

        button:hover { background: #1e40af; }

        .footer {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #64748b;
        }

        .footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 500px) {
            .card { margin: 16px; padding: 22px; }
        }
    </style>
</head>
<body>

<div class="card">
    <h2 class="title">👨‍🏫 Teacher Login</h2>
    <p class="subtitle">Sign in to your JGSGS staff account</p>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="/teacher/sign-in">
        @csrf

        <label class="field-label">Email Address</label>
        <input type="email" name="email" placeholder="e.g. adebayo@jgsgs.edu.ng"
               value="{{ old('email') }}" required autofocus>

        <label class="field-label">Password</label>
        <input type="password" name="password" placeholder="Your password" required>

        <div class="remember-row">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me</label>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="footer">
        Don't have an account? <a href="/teacher/sign-up">Register here</a>
    </div>
</div>

</body>
</html>