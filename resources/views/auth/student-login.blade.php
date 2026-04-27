<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>

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
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
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
            cursor: pointer;
        }

        button:hover {
            background: #1e40af;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
        }

        .footer a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="card">
    <h2 class="title">Student Login</h2>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/student/login">
        @csrf

        <input type="text" name="student_id" placeholder="Student ID" value="{{ old('student_id') }}">

        <input type="password" name="password" placeholder="Password">

        <button type="submit">Login</button>
    </form>

    <div class="footer">
        Don’t have an account? <a href="/student/register">Register</a>
    </div>
</div>

</body>
</html>