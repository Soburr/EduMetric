<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JGSGS – Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0a1628;
            --blue:   #2563eb;
            --blue-l: #3b82f6;
            --sky:    #dbeafe;
            --white:  #ffffff;
            --gray:   #64748b;
            --light:  #f8fafc;
            --border: #e2e8f0;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--navy);
            overflow-x: hidden;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 18px 6vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226,232,240,.6);
            transition: background .3s;
        }

        .nav-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 20px;
            color: var(--navy);
            text-decoration: none;
            letter-spacing: -.01em;
        }

        .nav-brand span { color: var(--blue); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-btn {
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .nav-btn.outline {
            border: 1.5px solid var(--border);
            color: var(--navy);
            background: none;
        }

        .nav-btn.outline:hover {
            border-color: var(--blue);
            color: var(--blue);
            background: var(--sky);
        }

        .nav-btn.solid {
            background: var(--blue);
            color: var(--white);
            border: 1.5px solid var(--blue);
        }

        .nav-btn.solid:hover { background: #1d4ed8; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 6vw 80px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background blobs */
        .hero::before {
            content: '';
            position: absolute;
            top: -200px; right: -200px;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(37,99,235,.12) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -150px; left: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(59,130,246,.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        .hero-inner {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--sky);
            color: var(--blue);
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 24px;
            border: 1px solid #bfdbfe;
            animation: fadeUp .6s ease both;
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(36px, 5vw, 58px);
            line-height: 1.1;
            letter-spacing: -.02em;
            color: var(--navy);
            margin-bottom: 20px;
            animation: fadeUp .6s ease .1s both;
        }

        .hero-title .highlight {
            color: var(--blue);
            position: relative;
            display: inline-block;
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 4px; left: 0; right: 0;
            height: 3px;
            background: var(--blue);
            border-radius: 2px;
            opacity: .3;
        }

        .hero-desc {
            font-size: 17px;
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: 36px;
            animation: fadeUp .6s ease .2s both;
        }

        .hero-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            animation: fadeUp .6s ease .3s both;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all .25s;
            font-family: 'DM Sans', sans-serif;
        }

        .cta-primary {
            background: var(--blue);
            color: var(--white);
            box-shadow: 0 4px 20px rgba(37,99,235,.3);
        }

        .cta-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(37,99,235,.4);
        }

        .cta-secondary {
            background: var(--white);
            color: var(--navy);
            border: 1.5px solid var(--border);
        }

        .cta-secondary:hover {
            border-color: var(--blue);
            color: var(--blue);
            background: var(--sky);
            transform: translateY(-2px);
        }

        /* Hero visual */
        .hero-visual {
            animation: fadeUp .7s ease .2s both;
        }

        .portal-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(10,22,40,.08);
            position: relative;
        }

        .portal-card::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 21px;
            background: linear-gradient(135deg, rgba(37,99,235,.15), transparent 60%);
            z-index: -1;
        }

        .portal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .portal-logo {
            width: 44px; height: 44px;
            background: var(--blue);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }

        .portal-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 16px;
            color: var(--navy);
        }

        .portal-sub { font-size: 12px; color: var(--gray); margin-top: 2px; }

        .portal-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: var(--light);
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--border);
        }

        .portal-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .portal-icon.blue  { background: var(--sky); }
        .portal-icon.green { background: rgba(16,185,129,.1); }

        .portal-row-label { font-size: 13px; font-weight: 600; color: var(--navy); }
        .portal-row-sub   { font-size: 11.5px; color: var(--gray); margin-top: 1px; }

        .portal-arrow {
            margin-left: auto;
            font-size: 14px;
            color: var(--blue);
            font-weight: 700;
        }

        /* ── STATS ── */
        .stats {
            padding: 80px 6vw;
            background: var(--navy);
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.1), transparent);
        }

        .stats-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
        }

        .stat-item {
            text-align: center;
            padding: 32px 20px;
            border-radius: 16px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            transition: all .3s;
        }

        .stat-item:hover {
            background: rgba(255,255,255,.07);
            transform: translateY(-4px);
        }

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 44px;
            color: var(--white);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-num span { color: var(--blue-l); }

        .stat-label {
            font-size: 14px;
            color: rgba(255,255,255,.5);
            font-weight: 500;
        }

        /* ── HOW IT WORKS ── */
        .how {
            padding: 100px 6vw;
            background: var(--light);
        }

        .how-inner { max-width: 1100px; margin: 0 auto; }

        .section-tag {
            display: inline-block;
            background: var(--sky);
            color: var(--blue);
            font-size: 12.5px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(28px, 4vw, 40px);
            color: var(--navy);
            margin-bottom: 56px;
            line-height: 1.15;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }

        .step {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 32px 28px;
            position: relative;
            transition: all .3s;
        }

        .step:hover {
            border-color: var(--blue);
            box-shadow: 0 12px 40px rgba(37,99,235,.1);
            transform: translateY(-4px);
        }

        .step-num {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 48px;
            color: var(--sky);
            line-height: 1;
            margin-bottom: 16px;
        }

        .step-icon {
            font-size: 32px;
            margin-bottom: 14px;
        }

        .step-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 18px;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .step-desc {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.65;
        }

        /* connector line between steps */
        .step:not(:last-child)::after {
            content: '→';
            position: absolute;
            top: 50%;
            right: -20px;
            transform: translateY(-50%);
            font-size: 20px;
            color: var(--blue);
            opacity: .4;
            font-weight: 700;
        }

        /* ── CTA BANNER ── */
        .cta-banner {
            padding: 80px 6vw;
            background: var(--white);
        }

        .cta-banner-inner {
            max-width: 1100px;
            margin: 0 auto;
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a8a 50%, #2563eb 100%);
            border-radius: 24px;
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            flex-wrap: wrap;
            position: relative;
            overflow: hidden;
        }

        .cta-banner-inner::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
        }

        .cta-banner-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(24px, 3vw, 34px);
            color: var(--white);
            line-height: 1.2;
            position: relative;
            z-index: 1;
        }

        .cta-banner-sub {
            font-size: 15px;
            color: rgba(255,255,255,.65);
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }

        .cta-banner-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .cta-white {
            padding: 13px 26px;
            background: var(--white);
            color: var(--navy);
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .cta-white:hover { background: var(--sky); transform: translateY(-2px); }

        .cta-ghost {
            padding: 13px 26px;
            background: rgba(255,255,255,.12);
            color: var(--white);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .cta-ghost:hover { background: rgba(255,255,255,.2); transform: translateY(-2px); }

        /* ── FOOTER ── */
        footer {
            background: var(--navy);
            padding: 40px 6vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .footer-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 18px;
            color: var(--white);
        }

        .footer-brand span { color: var(--blue-l); }

        .footer-copy {
            font-size: 13px;
            color: rgba(255,255,255,.35);
        }

        .footer-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .footer-link {
            font-size: 13px;
            color: rgba(255,255,255,.45);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-link:hover { color: rgba(255,255,255,.8); }

        .footer-link.admin {
            font-size: 11.5px;
            color: rgba(255,255,255,.2);
        }

        .footer-link.admin:hover { color: rgba(255,255,255,.5); }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; gap: 40px; }
            .hero-visual { display: none; }
            .stats-inner { grid-template-columns: repeat(2, 1fr); }
            .steps { grid-template-columns: 1fr; }
            .step::after { display: none; }
            .cta-banner-inner { padding: 40px 30px; }
        }

        @media (max-width: 600px) {
            .stats-inner { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .nav-links .nav-btn.outline { display: none; }
            footer { flex-direction: column; text-align: center; }
            .hero-btns { flex-direction: column; }
            .cta-btn { justify-content: center; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <a href="/" class="nav-brand">JGS<span>GS</span></a>
    <div class="nav-links">
        <a href="{{ route('student.login') }}" class="nav-btn outline">Student Login</a>
        <a href="{{ route('teacher.login') }}" class="nav-btn solid">Teacher Login</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">

        <div class="hero-content">
            <div class="hero-tag">
                🎓 Digital Learning Portal
            </div>
            <h1 class="hero-title">
                Your School.<br>
                <span class="highlight">Smarter.</span><br>
                Faster. Better.
            </h1>
            <p class="hero-desc">
                JGSGS brings students, teachers, and administrators
                together on one seamless platform — for exams, results,
                learning materials, and more.
            </p>
            <div class="hero-btns">
                <a href="{{ route('student.login') }}" class="cta-btn cta-primary">
                    🎓 I'm a Student
                </a>
                <a href="{{ route('teacher.login') }}" class="cta-btn cta-secondary">
                    👨‍🏫 I'm a Teacher
                </a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="portal-card">
                <div class="portal-header">
                    <div class="portal-logo">🎓</div>
                    <div>
                        <div class="portal-name">JGSGS Portal</div>
                        <div class="portal-sub">Student Dashboard</div>
                    </div>
                </div>

                <div class="portal-row">
                    <div class="portal-icon blue">📝</div>
                    <div>
                        <div class="portal-row-label">Take a Test</div>
                        <div class="portal-row-sub">2 tests available</div>
                    </div>
                    <div class="portal-arrow">→</div>
                </div>

                <div class="portal-row">
                    <div class="portal-icon green">📊</div>
                    <div>
                        <div class="portal-row-label">View Results</div>
                        <div class="portal-row-sub">Mathematics — 85%</div>
                    </div>
                    <div class="portal-arrow">→</div>
                </div>

                <div class="portal-row">
                    <div class="portal-icon blue">📁</div>
                    <div>
                        <div class="portal-row-label">Study Materials</div>
                        <div class="portal-row-sub">8 files uploaded</div>
                    </div>
                    <div class="portal-arrow">→</div>
                </div>

                <div class="portal-row" style="margin-bottom:0;">
                    <div class="portal-icon green">📈</div>
                    <div>
                        <div class="portal-row-label">Performance</div>
                        <div class="portal-row-sub">Overall avg — 74%</div>
                    </div>
                    <div class="portal-arrow">→</div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- STATS -->
<section class="stats">
    <div class="stats-inner">
        <div class="stat-item">
            <div class="stat-num">{{ $totalStudents }}<span>+</span></div>
            <div class="stat-label">Students Enrolled</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">{{ $totalTeachers }}<span>+</span></div>
            <div class="stat-label">Teachers</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">{{ $totalClasses }}<span></span></div>
            <div class="stat-label">Active Classes</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">{{ $totalTests }}<span>+</span></div>
            <div class="stat-label">Tests Created</div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how">
    <div class="how-inner">
        <div class="section-tag">How It Works</div>
        <h2 class="section-title">Three steps to get started</h2>

        <div class="steps">
            <div class="step">
                <div class="step-num">01</div>
                <div class="step-icon">📋</div>
                <div class="step-title">Register Your Account</div>
                <div class="step-desc">
                    Students and teachers sign up with their school-issued ID or email.
                    Your class and subjects are set up automatically.
                </div>
            </div>
            <div class="step">
                <div class="step-num">02</div>
                <div class="step-icon">🔐</div>
                <div class="step-title">Log In to Your Portal</div>
                <div class="step-desc">
                    Access your personalized dashboard. Students see their tests and results.
                    Teachers manage exams and track class performance.
                </div>
            </div>
            <div class="step">
                <div class="step-num">03</div>
                <div class="step-icon">🚀</div>
                <div class="step-title">Learn & Teach Better</div>
                <div class="step-desc">
                    Take timed tests, view instant results, download study materials,
                    and track academic progress — all in one place.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA BANNER -->
<section class="cta-banner">
    <div class="cta-banner-inner">
        <div>
            <div class="cta-banner-title">
                Ready to get started?<br>Pick your portal.
            </div>
            <div class="cta-banner-sub">
                Everything you need for school — in one place.
            </div>
        </div>
        <div class="cta-banner-btns">
            <a href="{{ route('student.login') }}" class="cta-white">
                🎓 Student Login
            </a>
            <a href="{{ route('teacher.login') }}" class="cta-ghost">
                👨‍🏫 Teacher Login
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-brand">JGS<span>GS</span></div>
    <div class="footer-copy">© {{ date('Y') }} JGSGS. All rights reserved.</div>
    <div class="footer-links">
        <a href="{{ route('student.login') }}" class="footer-link">Student Login</a>
        <a href="{{ route('teacher.login') }}" class="footer-link">Teacher Login</a>
        <a href="{{ route('admin.login') }}" class="footer-link admin">Admin</a>
    </div>
</footer>

</body>
</html>