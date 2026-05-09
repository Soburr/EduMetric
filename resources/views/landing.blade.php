<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dunamis Group of Schools</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --wine:      #4C0F24;
            --wine-dark: #360A19;
            --wine-mid:  #6A1833;
            --wine-lite: #9F2A50;
            --blush:     #FCE7F3;
            --gold:      #C9A84C;
            --gold-lite: #F0D48A;
            --white:     #FDFAF8;
            --off:       #F5EEE9;
            --gray:      #8C7B74;
            --dark:      #1A0A0F;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--white);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
            padding: 0 6vw;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(253,250,248,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(76,15,36,0.08);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-crest {
            width: 40px;
            height: 40px;
            background: var(--wine);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .nav-name {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 20px;
            color: var(--wine);
            letter-spacing: 0.02em;
            line-height: 1.1;
        }

        .nav-name small {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 500;
            color: var(--gray);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn {
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.01em;
        }

        .nav-btn.ghost {
            color: var(--wine);
            border: 1.5px solid rgba(76,15,36,0.2);
            background: transparent;
        }

        .nav-btn.ghost:hover {
            background: var(--blush);
            border-color: var(--wine);
        }

        .nav-btn.filled {
            background: var(--wine);
            color: #fff;
            border: 1.5px solid var(--wine);
        }

        .nav-btn.filled:hover {
            background: var(--wine-dark);
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            overflow: hidden;
        }

        /* Left panel — dark wine */
        .hero-left {
            background: var(--wine);
            padding: 140px 7vw 80px 6vw;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .hero-left::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 400px; height: 400px;
            border: 1px solid rgba(201,168,76,0.15);
            border-radius: 50%;
        }

        .hero-left::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 280px; height: 280px;
            border: 1px solid rgba(201,168,76,0.1);
            border-radius: 50%;
        }

        .hero-eyebrow {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            animation: fadeUp .7s ease both;
        }

        .hero-eyebrow-line {
            width: 32px;
            height: 1px;
            background: var(--gold);
            opacity: 0.7;
        }

        .hero-eyebrow-text {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold-lite);
            opacity: 0.85;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            font-size: clamp(44px, 5.5vw, 72px);
            color: #fff;
            line-height: 1.07;
            letter-spacing: -0.01em;
            margin-bottom: 28px;
            animation: fadeUp .7s ease .1s both;
        }

        .hero-title em {
            font-style: italic;
            color: var(--gold-lite);
        }

        .hero-desc {
            font-size: 15.5px;
            color: rgba(255,255,255,0.6);
            line-height: 1.75;
            max-width: 420px;
            margin-bottom: 44px;
            font-weight: 300;
            animation: fadeUp .7s ease .2s both;
        }

        .hero-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            animation: fadeUp .7s ease .3s both;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 14px 28px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .25s;
            letter-spacing: 0.01em;
        }

        .hero-cta.primary {
            background: var(--gold);
            color: var(--wine-dark);
        }

        .hero-cta.primary:hover {
            background: var(--gold-lite);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201,168,76,0.3);
        }

        .hero-cta.secondary {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.85);
            border: 1px solid rgba(255,255,255,0.15);
        }

        .hero-cta.secondary:hover {
            background: rgba(255,255,255,0.14);
            transform: translateY(-2px);
        }

        /* Right panel — cream/off-white */
        .hero-right {
            background: var(--off);
            padding: 140px 6vw 80px 5vw;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 20px;
            position: relative;
        }

        .hero-right::before {
            content: 'EXCELLENCE';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-90deg);
            font-family: 'Cormorant Garamond', serif;
            font-size: 100px;
            font-weight: 700;
            color: rgba(76,15,36,0.04);
            letter-spacing: 0.3em;
            white-space: nowrap;
            pointer-events: none;
        }

        .portal-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(76,15,36,0.1);
            padding: 22px 24px;
            box-shadow: 0 4px 24px rgba(76,15,36,0.06);
            animation: fadeUp .7s ease .15s both;
            transition: transform .3s, box-shadow .3s;
            text-decoration: none;
            display: block;
            position: relative;
            z-index: 1;
        }

        .portal-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(76,15,36,0.12);
        }

        .portal-card-head {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }

        .portal-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .portal-icon-wrap.student { background: var(--blush); }
        .portal-icon-wrap.teacher { background: rgba(76,15,36,0.08); }

        .portal-card-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 20px;
            color: var(--wine);
        }

        .portal-card-sub {
            font-size: 12.5px;
            color: var(--gray);
            margin-top: 2px;
        }

        .portal-features {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .portal-feature {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--gray);
        }

        .portal-feature-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--wine-lite);
            flex-shrink: 0;
        }

        .portal-card-arrow {
            position: absolute;
            top: 22px;
            right: 22px;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--wine);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: transform .2s;
        }

        .portal-card:hover .portal-card-arrow {
            transform: translateX(3px);
        }

        /* ── STATS ── */
        .stats {
            background: var(--wine-dark);
            padding: 72px 6vw;
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0.3;
        }

        .stats-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2px;
        }

        .stat-item {
            padding: 40px 24px;
            text-align: center;
            border-right: 1px solid rgba(255,255,255,0.06);
        }

        .stat-item:last-child { border-right: none; }

        .stat-num {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 52px;
            color: #fff;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-num sup {
            font-size: 24px;
            color: var(--gold);
        }

        .stat-label {
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* ── FEATURES ── */
        .features {
            padding: 100px 6vw;
            background: var(--white);
        }

        .features-inner {
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--wine-lite);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-label::before {
            content: '';
            width: 24px;
            height: 1px;
            background: var(--wine-lite);
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            font-size: clamp(32px, 4vw, 50px);
            color: var(--wine);
            line-height: 1.1;
            margin-bottom: 60px;
            max-width: 560px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px;
            background: rgba(76,15,36,0.06);
            border-radius: 20px;
            overflow: hidden;
        }

        .feature-item {
            background: var(--white);
            padding: 40px 36px;
            transition: background .3s;
        }

        .feature-item:hover {
            background: var(--off);
        }

        .feature-emoji {
            font-size: 28px;
            margin-bottom: 18px;
            display: block;
        }

        .feature-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 22px;
            color: var(--wine);
            margin-bottom: 10px;
        }

        .feature-desc {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.7;
            font-weight: 300;
        }

        /* ── HOW ── */
        .how {
            padding: 100px 6vw;
            background: var(--off);
        }

        .how-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .how-steps {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .how-step {
            display: flex;
            gap: 24px;
            padding: 28px 0;
            border-bottom: 1px solid rgba(76,15,36,0.08);
            transition: all .2s;
        }

        .how-step:last-child { border-bottom: none; }

        .how-step-num {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 42px;
            color: rgba(76,15,36,0.12);
            line-height: 1;
            flex-shrink: 0;
            width: 48px;
            transition: color .2s;
        }

        .how-step:hover .how-step-num {
            color: var(--wine-lite);
        }

        .how-step-content {}

        .how-step-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 20px;
            color: var(--wine);
            margin-bottom: 6px;
        }

        .how-step-desc {
            font-size: 13.5px;
            color: var(--gray);
            line-height: 1.65;
            font-weight: 300;
        }

        .how-visual {
            background: var(--wine);
            border-radius: 20px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .how-visual::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 50%;
        }

        .how-visual-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 26px;
            color: #fff;
            margin-bottom: 6px;
        }

        .how-visual-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 28px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .score-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: rgba(255,255,255,0.06);
            border-radius: 10px;
            margin-bottom: 8px;
            border: 1px solid rgba(255,255,255,0.06);
        }

        .score-subject {
            font-size: 13px;
            color: rgba(255,255,255,0.75);
        }

        .score-bar-wrap {
            flex: 1;
            margin: 0 16px;
            height: 4px;
            background: rgba(255,255,255,0.1);
            border-radius: 99px;
            overflow: hidden;
        }

        .score-bar {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, var(--gold), var(--gold-lite));
        }

        .score-val {
            font-size: 13px;
            font-weight: 600;
            color: var(--gold-lite);
            width: 36px;
            text-align: right;
        }

        /* ── CTA ── */
        .cta {
            padding: 100px 6vw;
            background: var(--wine);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 700px; height: 700px;
            border: 1px solid rgba(201,168,76,0.08);
            border-radius: 50%;
        }

        .cta::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 400px; height: 400px;
            border: 1px solid rgba(201,168,76,0.12);
            border-radius: 50%;
        }

        .cta-inner {
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-eyebrow {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold-lite);
            opacity: 0.7;
            margin-bottom: 20px;
        }

        .cta-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            font-size: clamp(36px, 5vw, 58px);
            color: #fff;
            line-height: 1.1;
            margin-bottom: 16px;
        }

        .cta-title em {
            font-style: italic;
            color: var(--gold-lite);
        }

        .cta-sub {
            font-size: 15px;
            color: rgba(255,255,255,0.55);
            margin-bottom: 40px;
            font-weight: 300;
            line-height: 1.6;
        }

        .cta-btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 30px;
            background: var(--gold);
            color: var(--wine-dark);
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .cta-btn-gold:hover {
            background: var(--gold-lite);
            transform: translateY(-2px);
        }

        .cta-btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 30px;
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .cta-btn-ghost:hover {
            background: rgba(255,255,255,0.14);
            transform: translateY(-2px);
        }

        /* ── FOOTER ── */
        footer {
            background: var(--dark);
            padding: 40px 6vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-brand {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: 20px;
            color: rgba(255,255,255,0.7);
            letter-spacing: 0.04em;
        }

        .footer-copy {
            font-size: 12.5px;
            color: rgba(255,255,255,0.25);
        }

        .footer-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .footer-link {
            font-size: 13px;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-link:hover { color: rgba(255,255,255,0.7); }

        .footer-link.admin {
            font-size: 11px;
            color: rgba(255,255,255,0.15);
        }

        .footer-link.admin:hover { color: rgba(255,255,255,0.4); }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            .hero-left { padding: 120px 6vw 60px; }
            .hero-right { padding: 40px 6vw 60px; }
            .stats-inner { grid-template-columns: repeat(2, 1fr); }
            .stat-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.06); }
            .features-grid { grid-template-columns: 1fr; }
            .how-inner { grid-template-columns: 1fr; gap: 40px; }
        }

        @media (max-width: 600px) {
            .stats-inner { grid-template-columns: repeat(2, 1fr); gap: 0; }
            .nav-links .nav-btn.ghost { display: none; }
            footer { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <a href="/" class="nav-brand">
        <div class="nav-crest">🎓</div>
        <div class="nav-name">
            Dunamis
            <small>Group of Schools</small>
        </div>
    </a>
    <div class="nav-links">
        <a href="{{ route('student.login') }}" class="nav-btn ghost">Student Login</a>
        <a href="{{ route('teacher.login') }}" class="nav-btn filled">Staff Login</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero">

    <!-- Left: Dark wine panel -->
    <div class="hero-left">
        <div class="hero-eyebrow">
            <span class="hero-eyebrow-line"></span>
            <span class="hero-eyebrow-text">Alagbado, Lagos State</span>
        </div>
        <h1 class="hero-title">
            Where<br>
            <em>Excellence</em><br>
            Takes Root.
        </h1>
        <p class="hero-desc">
            Dunamis Group of Schools brings learning, results, and administration 
            together on one seamless digital platform — built for students, teachers, 
            and parents who expect more.
        </p>
        <div class="hero-btns">
            <a href="{{ route('student.login') }}" class="hero-cta primary">
                🎓 Student Portal
            </a>
            <a href="{{ route('teacher.login') }}" class="hero-cta secondary">
                👨‍🏫 Staff Portal
            </a>
        </div>
    </div>

    <!-- Right: Cream panel with portal cards -->
    <div class="hero-right">

        <a href="{{ route('student.login') }}" class="portal-card" style="animation-delay:.1s">
            <div class="portal-card-arrow">→</div>
            <div class="portal-card-head">
                <div class="portal-icon-wrap student">🎓</div>
                <div>
                    <div class="portal-card-title">Student Portal</div>
                    <div class="portal-card-sub">Access your academic dashboard</div>
                </div>
            </div>
            <div class="portal-features">
                <div class="portal-feature">
                    <span class="portal-feature-dot"></span>
                    Take timed CBT tests online
                </div>
                <div class="portal-feature">
                    <span class="portal-feature-dot"></span>
                    View term report cards & results
                </div>
                <div class="portal-feature">
                    <span class="portal-feature-dot"></span>
                    Download study materials
                </div>
                <div class="portal-feature">
                    <span class="portal-feature-dot"></span>
                    Track your academic performance
                </div>
            </div>
        </a>

        <a href="{{ route('teacher.login') }}" class="portal-card" style="animation-delay:.2s">
            <div class="portal-card-arrow">→</div>
            <div class="portal-card-head">
                <div class="portal-icon-wrap teacher">👨‍🏫</div>
                <div>
                    <div class="portal-card-title">Staff Portal</div>
                    <div class="portal-card-sub">Manage your classes & results</div>
                </div>
            </div>
            <div class="portal-features">
                <div class="portal-feature">
                    <span class="portal-feature-dot"></span>
                    Create & manage CBT exams
                </div>
                <div class="portal-feature">
                    <span class="portal-feature-dot"></span>
                    Enter CA and exam scores
                </div>
                <div class="portal-feature">
                    <span class="portal-feature-dot"></span>
                    View class broadsheets
                </div>
                <div class="portal-feature">
                    <span class="portal-feature-dot"></span>
                    Print student report cards
                </div>
            </div>
        </a>

    </div>
</section>

<!-- STATS -->
<section class="stats">
    <div class="stats-inner">
        <div class="stat-item">
            <div class="stat-num">{{ $totalStudents }}<sup>+</sup></div>
            <div class="stat-label">Students Enrolled</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">{{ $totalTeachers }}<sup>+</sup></div>
            <div class="stat-label">Teaching Staff</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">{{ $totalClasses }}</div>
            <div class="stat-label">Active Classes</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">{{ $totalTests }}<sup>+</sup></div>
            <div class="stat-label">Tests Conducted</div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features">
    <div class="features-inner">
        <div class="section-label">What We Offer</div>
        <h2 class="section-title">Everything your school needs, in one place.</h2>

        <div class="features-grid">
            <div class="feature-item">
                <span class="feature-emoji">📝</span>
                <div class="feature-title">Online CBT Testing</div>
                <div class="feature-desc">Students take timed computer-based tests from any device. Results are instant, automatic, and tamper-proof.</div>
            </div>
            <div class="feature-item">
                <span class="feature-emoji">📊</span>
                <div class="feature-title">Result Management</div>
                <div class="feature-desc">Teachers enter CA and exam scores. The system calculates totals, grades, positions, and generates class broadsheets.</div>
            </div>
            <div class="feature-item">
                <span class="feature-emoji">📄</span>
                <div class="feature-title">Printable Report Cards</div>
                <div class="feature-desc">Professional term report cards with attendance, subject scores, behavioural ratings, and teacher comments — ready to print.</div>
            </div>
            <div class="feature-item">
                <span class="feature-emoji">📁</span>
                <div class="feature-title">Study Materials</div>
                <div class="feature-desc">Teachers upload notes, past questions, and resources. Students download directly from their dashboard, anytime.</div>
            </div>
            <div class="feature-item">
                <span class="feature-emoji">📈</span>
                <div class="feature-title">Performance Analytics</div>
                <div class="feature-desc">Students see their subject-by-subject performance. Teachers track class averages, highest, lowest, and individual progress.</div>
            </div>
            <div class="feature-item">
                <span class="feature-emoji">🔔</span>
                <div class="feature-title">School Notices</div>
                <div class="feature-desc">Announcements from school management reach every student and teacher instantly through the portal.</div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how">
    <div class="how-inner">

        <div>
            <div class="section-label">How It Works</div>
            <h2 class="section-title">Simple for students. Powerful for teachers.</h2>

            <div class="how-steps">
                <div class="how-step">
                    <div class="how-step-num">01</div>
                    <div class="how-step-content">
                        <div class="how-step-title">Log in to your portal</div>
                        <div class="how-step-desc">Students and staff access their personalised dashboards using their school-assigned credentials.</div>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-step-num">02</div>
                    <div class="how-step-content">
                        <div class="how-step-title">Take tests or enter scores</div>
                        <div class="how-step-desc">Students sit timed CBT exams online. Teachers enter CA and exam scores which are automatically calculated.</div>
                    </div>
                </div>
                <div class="how-step">
                    <div class="how-step-num">03</div>
                    <div class="how-step-content">
                        <div class="how-step-title">View results & report cards</div>
                        <div class="how-step-desc">Full term results, class positions, broadsheets, and printable report cards are available instantly after scores are entered.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visual result preview -->
        <div class="how-visual">
            <div class="how-visual-title">Term Result Preview</div>
            <div class="how-visual-sub">First Term · 2025/2026</div>

            <div class="score-row">
                <span class="score-subject">Mathematics</span>
                <div class="score-bar-wrap">
                    <div class="score-bar" style="width:82%"></div>
                </div>
                <span class="score-val">82</span>
            </div>
            <div class="score-row">
                <span class="score-subject">English Language</span>
                <div class="score-bar-wrap">
                    <div class="score-bar" style="width:74%"></div>
                </div>
                <span class="score-val">74</span>
            </div>
            <div class="score-row">
                <span class="score-subject">Basic Science</span>
                <div class="score-bar-wrap">
                    <div class="score-bar" style="width:90%"></div>
                </div>
                <span class="score-val">90</span>
            </div>
            <div class="score-row">
                <span class="score-subject">Social Studies</span>
                <div class="score-bar-wrap">
                    <div class="score-bar" style="width:67%"></div>
                </div>
                <span class="score-val">67</span>
            </div>
            <div class="score-row">
                <span class="score-subject">Civic Education</span>
                <div class="score-bar-wrap">
                    <div class="score-bar" style="width:78%"></div>
                </div>
                <span class="score-val">78</span>
            </div>

            <div style="margin-top:20px; padding-top:16px; border-top:1px solid rgba(255,255,255,0.08);
                        display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:11px; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.1em;">Average</div>
                    <div style="font-family:'Cormorant Garamond',serif; font-size:28px; font-weight:700; color:var(--gold-lite);">78.2%</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:11px; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.1em;">Position</div>
                    <div style="font-family:'Cormorant Garamond',serif; font-size:28px; font-weight:700; color:#fff;">3<span style="font-size:14px; color:rgba(255,255,255,0.5)"> / 42</span></div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- CTA -->
<section class="cta">
    <div class="cta-inner">
        <div class="cta-eyebrow">Get Started Today</div>
        <h2 class="cta-title">
            Your school.<br>
            <em>Fully digital.</em>
        </h2>
        <p class="cta-sub">
            Join Dunamis Group of Schools on a smarter academic journey.
            Access your results, tests, and materials from anywhere.
        </p>
        <div class="cta-btns">
            <a href="{{ route('student.login') }}" class="cta-btn-gold">🎓 Student Login</a>
            <a href="{{ route('teacher.login') }}" class="cta-btn-ghost">👨‍🏫 Staff Login</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-brand">Dunamis Group of Schools</div>
    <div class="footer-copy">© {{ date('Y') }} Dunamis Group of Schools · Alagbado, Lagos</div>
    <div class="footer-links">
        <a href="{{ route('student.login') }}" class="footer-link">Student Login</a>
        <a href="{{ route('teacher.login') }}" class="footer-link">Staff Login</a>
        <a href="{{ route('admin.login') }}" class="footer-link admin">Admin</a>
    </div>
</footer>

</body>
</html>