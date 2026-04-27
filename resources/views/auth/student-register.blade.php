<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>

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

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        input:focus, select:focus {
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

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
        }

        .footer a {
            color: #2563eb;
            text-decoration: none;
        }

        @media (max-width: 500px) {
            .card {
                margin: 10px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="card">
    <h2 class="title">Student Registration</h2>
    @if ($errors->any())
    <div style="background:#fee2e2; padding:10px; border-radius:6px; margin-bottom:10px;">
        <ul style="margin:0; padding-left:15px; color:#b91c1c;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form method="POST" action="/student/register">
        @csrf

        <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>

        <input type="text" name="student_id" placeholder="Student ID (e.g REG123X)" value="{{ old('student_id') }}" required>

<select name="class_id" required>
    <option value="">Select Class</option>
    @foreach($classes as $class)
        <option value="{{ $class->id }}">{{ $class->name }}</option>
    @endforeach
</select>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Create Account</button>
    </form>

    <div class="footer">
        Already have an account? <a href="/student/login">Login</a>
    </div>
</div>

</body>
</html>