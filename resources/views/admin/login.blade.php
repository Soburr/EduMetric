<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – JGSGS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0d1f4e 0%, #1e4bb8 50%, #2563eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrap {
            width: 100%;
            max-width: 420px;
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 56px; height: 56px;
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            margin: 0 auto 12px;
            backdrop-filter: blur(8px);
        }

        .brand-name {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: .01em;
        }

        .brand-sub {
            font-size: 13px;
            color: rgba(255,255,255,.6);
            margin-top: 4px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: #0d1f4e;
            margin-bottom: 4px;
        }

        .card-sub {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 24px;
        }

        .error-box {
            background: #fee2e2;
            color: #b91c1c;
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .success-box {
            background: #dcfce7;
            color: #15803d;
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .field { margin-bottom: 16px; }

        .field label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }

        .field input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #1f2937;
            transition: border-color .2s, box-shadow .2s;
        }

        .field input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            margin-top: 8px;
            transition: background .2s;
        }

        .submit-btn:hover { background: #1e40af; }

        .admin-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #1e40af;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="login-wrap">

    <div class="brand">
        <div class="brand-icon">🛡️</div>
        <div class="brand-name">JGSGS</div>
        <div class="brand-sub">School Management System</div>
    </div>

    <div class="card">
        <div class="card-title">Admin Portal</div>
        <div class="card-sub">Sign in with your administrator credentials</div>

        @if($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif

        @if(session('success'))
            <div class="success-box">{{ session('success') }}</div>
        @endif

        <div class="admin-badge">
            🛡️ Restricted access — Administrators only
        </div>

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email"
                       placeholder="admin@jgsgs.edu.ng"
                       value="{{ old('email') }}"
                       autofocus required>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password"
                       placeholder="Your password" required>
            </div>

            <button type="submit" class="submit-btn">
                🔐 Sign In
            </button>
        </form>
    </div>

</div>

</body>
</html>