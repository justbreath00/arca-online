<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arca — Inventory & Sales Platform</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:        #080811;
            --surface:   #0f0f1c;
            --card:      #13131f;
            --border:    rgba(255,255,255,0.07);
            --cyan:      #00d4e8;
            --cyan-dim:  #00a8b8;
            --cyan-glow: rgba(0,212,232,0.15);
            --white:     #f0f0f8;
            --muted:     #7a7a9a;
            --accent2:   #7c6aff;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            overflow-x: hidden;
        }

        /* ── NOISE OVERLAY ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }

        /* ── AMBIENT GLOWS ── */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }
        .glow-orb-1 { width: 500px; height: 500px; background: rgba(0,212,232,0.06); top: -100px; left: -100px; }
        .glow-orb-2 { width: 400px; height: 400px; background: rgba(124,106,255,0.06); bottom: 20%; right: -100px; }
        .glow-orb-3 { width: 300px; height: 300px; background: rgba(0,212,232,0.04); top: 50%; left: 40%; }

        /* ── HEADER ── */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 40px;
            background: rgba(8,8,17,0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-wrap img { width: 70px; height: auto; }

        .logo-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: 0.05em;
            color: var(--white);
        }

        .logo-name span { color: var(--cyan); }

        header nav { display: flex; gap: 28px; align-items: center; }

        header nav a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 400;
            letter-spacing: 0.02em;
            transition: color 0.25s;
        }

        header nav a:hover { color: var(--white); }

        .nav-cta {
            padding: 8px 20px;
            border-radius: 8px;
            background: var(--cyan);
            color: #000 !important;
            font-weight: 500 !important;
            transition: background 0.25s, transform 0.2s !important;
        }

        .nav-cta:hover { background: var(--cyan-dim) !important; transform: translateY(-1px); }

        /* ── HERO ── */
        .hero {
            position: relative;
            z-index: 1;
            min-height: 92vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 80px 24px 60px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 50px;
            border: 1px solid rgba(0,212,232,0.3);
            background: rgba(0,212,232,0.06);
            font-size: 0.78rem;
            color: var(--cyan);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 500;
            margin-bottom: 28px;
            animation: fadeUp 0.8s ease both;
        }

        .hero-eyebrow::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--cyan);
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.8); }
        }

        .hero h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            line-height: 1.05;
            letter-spacing: -0.02em;
            max-width: 820px;
            animation: fadeUp 0.8s 0.1s ease both;
        }

        .hero h1 .accent { color: var(--cyan); }

        .hero-sub {
            margin-top: 24px;
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: var(--muted);
            max-width: 520px;
            line-height: 1.7;
            animation: fadeUp 0.8s 0.2s ease both;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            margin-top: 40px;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeUp 0.8s 0.3s ease both;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            border-radius: 10px;
            background: var(--cyan);
            color: #000;
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
            transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
            box-shadow: 0 0 30px rgba(0,212,232,0.2);
        }

        .btn-primary:hover {
            background: var(--cyan-dim);
            transform: translateY(-2px);
            box-shadow: 0 0 50px rgba(0,212,232,0.35);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            border-radius: 10px;
            border: 1px solid var(--border);
            color: var(--white);
            font-size: 0.95rem;
            text-decoration: none;
            transition: border-color 0.25s, transform 0.2s;
        }

        .btn-ghost:hover { border-color: rgba(255,255,255,0.25); transform: translateY(-2px); }

        /* hero dashboard mockup */
        .hero-mockup {
            position: relative;
            margin-top: 70px;
            width: 100%;
            max-width: 860px;
            animation: fadeUp 0.8s 0.45s ease both;
        }

        .mockup-frame {
            border-radius: 16px;
            border: 1px solid var(--border);
            background: var(--card);
            overflow: hidden;
            box-shadow: 0 40px 120px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.04);
        }

        .mockup-bar {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 12px 18px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .mockup-dot { width: 11px; height: 11px; border-radius: 50%; }
        .mockup-dot:nth-child(1) { background: #ff5f57; }
        .mockup-dot:nth-child(2) { background: #febc2e; }
        .mockup-dot:nth-child(3) { background: #28c840; }

        .mockup-body {
            padding: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
        }

        .mock-card {
            background: var(--surface);
            border-radius: 10px;
            padding: 18px;
            border: 1px solid var(--border);
        }

        .mock-card-label { font-size: 0.72rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .mock-card-value { font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--white); }
        .mock-card-value.cyan { color: var(--cyan); }
        .mock-card-sub { font-size: 0.75rem; color: var(--muted); margin-top: 4px; }

        .mock-bar-row { display: flex; align-items: center; gap: 10px; margin-top: 14px; }
        .mock-bar-label { font-size: 0.72rem; color: var(--muted); width: 60px; text-align: right; flex-shrink: 0; }
        .mock-bar-track { flex: 1; height: 6px; background: var(--bg); border-radius: 99px; overflow: hidden; }
        .mock-bar-fill { height: 100%; border-radius: 99px; background: var(--cyan); }

        /* ─── Hero Mockup Mobile Adjustments ─── */
@media (max-width: 430px) {
    .hero-mockup {
        margin-top: 30px; 
        width: 100%;
    }

    .mockup-body {
        grid-template-columns: 1fr; 
        padding: 12px; /* Tighter padding for small screens */
        gap: 8px;
    }

    .mock-card {
        padding: 12px;
    }

    /* Primary values (e.g., "$1,200") */
    .mock-card-value {
        font-size: 1.1rem; /* Dropped from 1.2rem */
    }

    /* Small labels (e.g., "TOTAL REVENUE") */
    .mock-card-label {
        font-size: 0.6rem; /* Dropped from 0.72rem */
        letter-spacing: 0.05em;
    }

    /* Subtext (e.g., "+12% from last month") */
    .mock-card-sub {
        font-size: 0.65rem; /* Dropped from 0.75rem */
    }

    /* Bar chart labels */
    .mock-bar-label {
        width: 45px;
        font-size: 0.6rem; /* Scaled down to match labels */
    }

    /* Header text if applicable */
    .logo-name {
        font-size: 1.1rem;
    }

    /* Navigation links */
    header nav a {
        font-size: 0.75rem;
    }

    .mockup-bar {
        padding: 8px 12px;
    }
}

        /* ── STATS STRIP ── */
        .stats-strip {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            gap: 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: var(--surface);
        }

        .stat-item {
            flex: 1;
            max-width: 220px;
            text-align: center;
            padding: 36px 20px;
            border-right: 1px solid var(--border);
        }

        .stat-item:last-child { border-right: none; }

        .stat-number {
            font-family: 'Syne', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--cyan);
            line-height: 1;
        }

        .stat-label { font-size: 0.82rem; color: var(--muted); margin-top: 6px; }


        /* ── FEATURES ── */
        .section {
            position: relative;
            z-index: 1;
            padding: 100px 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 0.75rem;
            color: var(--cyan);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-weight: 500;
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            max-width: 540px;
            margin-bottom: 60px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px 28px;
            transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .feat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 0% 0%, var(--cyan-glow), transparent 60%);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .feat-card:hover { border-color: rgba(0,212,232,0.3); transform: translateY(-4px); box-shadow: 0 20px 60px rgba(0,0,0,0.4); }
        .feat-card:hover::before { opacity: 1; }

        .feat-icon {
            width: 46px; height: 46px;
            border-radius: 10px;
            background: rgba(0,212,232,0.1);
            border: 1px solid rgba(0,212,232,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 20px;
        }

        .feat-card h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .feat-card p { font-size: 0.88rem; color: var(--muted); line-height: 1.65; }

        /* ── HOW IT WORKS ── */
        .how-section {
            position: relative;
            z-index: 1;
            padding: 80px 24px 100px;
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .how-inner {
            max-width: 1100px;
            margin: 0 auto;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            margin-top: 60px;
            position: relative;
        }

        .steps::before {
            content: '';
            position: absolute;
            top: 22px;
            left: 10%;
            right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            z-index: 0;
        }

        .step {
            text-align: center;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .step-num {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: var(--card);
            border: 1px solid var(--cyan);
            color: var(--cyan);
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 0 20px rgba(0,212,232,0.2);
        }

        .step h4 {
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .step p { font-size: 0.82rem; color: var(--muted); line-height: 1.6; }

        /* ── TESTIMONIAL / HIGHLIGHT ── */
        .highlight-section {
            position: relative;
            z-index: 1;
            padding: 100px 24px;
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .highlight-text .section-title { margin-bottom: 20px; }

        .highlight-text p {
            color: var(--muted);
            line-height: 1.75;
            font-size: 0.95rem;
            margin-bottom: 16px;
        }

        .check-list { list-style: none; margin-top: 28px; display: flex; flex-direction: column; gap: 12px; }

        .check-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            color: var(--white);
        }

        .check-list li::before {
            content: '✓';
            width: 22px; height: 22px;
            border-radius: 50%;
            background: rgba(0,212,232,0.1);
            border: 1px solid rgba(0,212,232,0.3);
            color: var(--cyan);
            font-size: 0.7rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .highlight-visual {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        .receipt-mock { font-size: 0.82rem; }
        .receipt-mock .r-header {
            text-align: center;
            padding-bottom: 14px;
            border-bottom: 1px dashed var(--border);
            margin-bottom: 14px;
        }

        .receipt-mock .r-header h4 { font-family: 'Syne', sans-serif; font-size: 1rem; margin-bottom: 4px; }
        .receipt-mock .r-header p { color: var(--muted); font-size: 0.75rem; }

        .r-row { display: flex; justify-content: space-between; padding: 6px 0; color: var(--muted); }
        .r-row .r-name { color: var(--white); }
        .r-total {
            display: flex; justify-content: space-between;
            margin-top: 14px; padding-top: 14px;
            border-top: 1px dashed var(--border);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            color: var(--cyan);
        }

        /* ── CTA ── */
        .cta-section {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 100px 24px;
            background: var(--surface);
            border-top: 1px solid var(--border);
        }

        .cta-section h2 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .cta-section p { color: var(--muted); font-size: 1rem; margin-bottom: 36px; }

        /* ── FOOTER ── */
        footer {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 24px 40px;
            border-top: 1px solid var(--border);
        }

        footer p { font-size: 0.82rem; color: var(--muted); }
        footer .footer-links { display: flex; gap: 20px; }
        footer .footer-links a { font-size: 0.82rem; color: var(--muted); text-decoration: none; transition: color 0.2s; }
        footer .footer-links a:hover { color: var(--white); }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .features-grid { grid-template-columns: 1fr 1fr; }
            .steps { grid-template-columns: 1fr 1fr; gap: 40px; }
            .steps::before { display: none; }
            .highlight-section { grid-template-columns: 1fr; }
            .stats-strip { flex-wrap: wrap; }
            .stat-item { border-right: none; border-bottom: 1px solid var(--border); min-width: 50%; }
        }

        @media (max-width: 600px) {
            header { padding: 14px 20px; }
            .features-grid { grid-template-columns: 1fr; }
            .steps { grid-template-columns: 1fr; }
            .mockup-body { grid-template-columns: 1fr 1fr; }
            footer { flex-direction: column; text-align: center; }
        }

        /* ─── Mobile Optimization for iPhone 12 Pro (390x844) ─── */
@media (max-width: 430px) {
    /* 1. Header & Navigation */
    header {
        padding: 12px 20px;
    }

    header nav {
        gap: 15px; /* Tighter gap for small screens */
    }

    header nav a:not(.nav-cta) {
        display: none; /* Hide non-essential links to prevent overlap */
    }

    .logo-name {
        font-size: 1.1rem; /* Smaller logo text */
    }

    .logo-wrap img {
        width: 50px;
    }

    /* 2. Hero Section Scaling */
    .hero {
        padding: 60px 20px 40px;
        min-height: auto;
    }

    .hero-eyebrow {
        font-size: 0.65rem;
        padding: 4px 12px;
        margin-bottom: 20px;
    }

    .hero h1 {
        font-size: 2.8rem; /* Scaled for 390px width */
        line-height: 1.1;
    }

    .hero-sub {
        font-size: 0.95rem;
        margin-top: 16px;
    }

    /* 3. Button Resizing */
    .hero-actions {
        flex-direction: column; /* Stack buttons to prevent clipping */
        width: 100%;
        max-width: 280px; /* Limits button width so they aren't "too big" */
        margin: 32px auto 0;
    }

    .btn-primary, .btn-ghost {
        padding: 12px 24px;
        font-size: 0.88rem;
        justify-content: center;
        width: 100%; /* Uniform width for stacked look */
    }

    .nav-cta {
        padding: 6px 14px !important;
        font-size: 0.8rem !important;
    }

    /* 4. Grid & Layout Adjustments */
    .features-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .mockup-body {
        grid-template-columns: 1fr; /* Single column for stats on mobile */
        padding: 16px;
    }

    .stat-item {
        min-width: 100%;
        padding: 24px 10px;
    }

    .section-title {
        font-size: 2rem;
        margin-bottom: 40px;
    }

    .steps {
        margin-top: 40px;
    }

    .highlight-visual {
        padding: 15px;
    }

    /* 5. Footer Padding */
    footer {
        padding: 30px 20px;
        gap: 20px;
    }
}
    </style>
</head>
<body>

<!-- ambient glows -->
<div class="glow-orb glow-orb-1"></div>
<div class="glow-orb glow-orb-2"></div>
<div class="glow-orb glow-orb-3"></div>

<!-- ── HEADER ── -->
<header>
    <div class="logo-wrap">
        <img src="assets/img/arca.png" alt="Arca Logo">
        
    </div>
    <nav>
        <a href="#home">Home</a>
        <a href="#features">Features</a>
        <a href="#how">How it works</a>
        <a href="auth/login.php" class="nav-cta">Sign In</a>
    </nav>
</header>

<!-- ── HERO ── -->
<section class="hero" id="home">
    <div class="hero-eyebrow">Inventory & Sales Platform</div>

    <h1>
        Run your store<br>
        with <span class="accent">total clarity</span>
    </h1>

    <p class="hero-sub">
        Arca gives small businesses a clean, fast way to manage inventory, punch sales, and understand their numbers — all in one place.
    </p>

    <div class="hero-actions">
        <a href="auth/register.php" class="btn-primary">
            Get started free
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="auth/login.php" class="btn-ghost">Sign in to dashboard</a>
    </div>

    <!-- dashboard mockup -->
    <div class="hero-mockup">
        <div class="mockup-frame">
            <div class="mockup-bar">
                <div class="mockup-dot"></div>
                <div class="mockup-dot"></div>
                <div class="mockup-dot"></div>
            </div>
            <div class="mockup-body">
                <div class="mock-card">
                    <div class="mock-card-label">Total Sales</div>
                    <div class="mock-card-value cyan">₱48,320</div>
                    <div class="mock-card-sub">↑ 12% this month</div>
                </div>
                <div class="mock-card">
                    <div class="mock-card-label">Items in Stock</div>
                    <div class="mock-card-value">124</div>
                    <div class="mock-card-sub">6 low stock alerts</div>
                </div>
                <div class="mock-card">
                    <div class="mock-card-label">Net Profit</div>
                    <div class="mock-card-value cyan">₱18,940</div>
                    <div class="mock-card-sub">After cost deductions</div>
                </div>
                <div class="mock-card" style="grid-column: span 3;">
                    <div class="mock-card-label">Top Items This Week</div>
                    <div class="mock-bar-row"><div class="mock-bar-label">Drinks</div><div class="mock-bar-track"><div class="mock-bar-fill" style="width:82%"></div></div></div>
                    <div class="mock-bar-row"><div class="mock-bar-label">Snacks</div><div class="mock-bar-track"><div class="mock-bar-fill" style="width:65%"></div></div></div>
                    <div class="mock-bar-row"><div class="mock-bar-label">Canned</div><div class="mock-bar-track"><div class="mock-bar-fill" style="width:48%"></div></div></div>
                    <div class="mock-bar-row"><div class="mock-bar-label">Noodles</div><div class="mock-bar-track"><div class="mock-bar-fill" style="width:33%"></div></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── STATS STRIP ── -->
<div class="stats-strip reveal">
    <div class="stat-item">
        <div class="stat-number">100%</div>
        <div class="stat-label">Browser-based, no install</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">3 sec</div>
        <div class="stat-label">Average transaction time</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">Real-time</div>
        <div class="stat-label">Sales & inventory sync</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">Free</div>
        <div class="stat-label">To get started today</div>
    </div>
</div>

<!-- ── FEATURES ── -->
<div id="features" class="section">
    <div class="section-label">Features</div>
    <h2 class="section-title">Everything your store needs</h2>

    <div class="features-grid">
        <div class="feat-card reveal">
            <div class="feat-icon">📦</div>
            <h3>Inventory Management</h3>
            <p>Add, edit, and remove products with ease. Track quantity, cost, and price per item — all in a clean searchable table.</p>
        </div>
        <div class="feat-card reveal" style="transition-delay:0.08s">
            <div class="feat-icon">🖥️</div>
            <h3>Point-of-Sale Counter</h3>
            <p>Punch items into a live receipt, adjust quantities, and push completed sales in seconds. Built for speed at the counter.</p>
        </div>
        <div class="feat-card reveal" style="transition-delay:0.16s">
            <div class="feat-icon">📊</div>
            <h3>Sales Reports</h3>
            <p>See overall revenue, total costs, and net profit at a glance. Drill into your top 5 best-selling products automatically.</p>
        </div>
        <div class="feat-card reveal" style="transition-delay:0.08s">
            <div class="feat-icon">🔍</div>
            <h3>Live Search</h3>
            <p>Find any product instantly with real-time AJAX search. No page reloads, no waiting — just results as you type.</p>
        </div>
        <div class="feat-card reveal" style="transition-delay:0.16s">
            <div class="feat-icon">🔐</div>
            <h3>Secure Accounts</h3>
            <p>Each user has their own private inventory and sales data. Passwords are hashed, inputs sanitized, queries parameterized.</p>
        </div>
        <div class="feat-card reveal" style="transition-delay:0.24s">
            <div class="feat-icon">🛡️</div>
            <h3>Admin Controls</h3>
            <p>Admin dashboard lets you monitor all users, view their total sales, and manage accounts — with a clean sortable interface.</p>
        </div>
    </div>
</div>

<!-- ── HOW IT WORKS ── -->
<div id="how" class="how-section">
    <div class="how-inner">
        <div class="section-label">How it works</div>
        <h2 class="section-title">Up and running in minutes</h2>

        <div class="steps">
            <div class="step reveal">
                <div class="step-num">1</div>
                <h4>Create an account</h4>
                <p>Sign up with your email. Your data is private and tied to your account only.</p>
            </div>
            <div class="step reveal" style="transition-delay:0.1s">
                <div class="step-num">2</div>
                <h4>Add your products</h4>
                <p>Fill in item name, category, quantity, cost, and price. Done in seconds per item.</p>
            </div>
            <div class="step reveal" style="transition-delay:0.2s">
                <div class="step-num">3</div>
                <h4>Start punching sales</h4>
                <p>Go to Counter, punch items into the receipt, then push to record the transaction.</p>
            </div>
            <div class="step reveal" style="transition-delay:0.3s">
                <div class="step-num">4</div>
                <h4>Review your sales</h4>
                <p>Check the Sales Report for totals, top items, and profit margins over time.</p>
            </div>
        </div>
    </div>
</div>

<!-- ── HIGHLIGHT ── -->
<div class="highlight-section">
    <div class="highlight-text reveal">
        <div class="section-label">Built for small stores</div>
        <h2 class="section-title">Simple enough to use at the counter</h2>
        <p>Arca was designed for sari-sari stores, small shops, and any business that needs a fast, no-nonsense way to track what they're selling and what they're earning.</p>
        <p>No spreadsheets. No complicated setup. Just a dashboard that works.</p>
        <ul class="check-list">
            <li>Works on any browser, phone or desktop</li>
            <li>Each sale automatically updates your stock</li>
            <li>Cancel a receipt anytime before pushing</li>
            <li>Separate login for every staff member</li>
        </ul>
    </div>
    <div class="highlight-visual reveal" style="transition-delay:0.15s">
        <div class="receipt-mock">
            <div class="r-header">
                <h4>Arca Receipt</h4>
                <p><?= date('M d, Y — h:i A') ?></p>
            </div>
            <div class="r-row"><span class="r-name">Mineral Water (500ml)</span><span>×2</span><span>₱50.00</span></div>
            <div class="r-row"><span class="r-name">Instant Noodles</span><span>×3</span><span>₱45.00</span></div>
            <div class="r-row"><span class="r-name">Canned Sardines</span><span>×1</span><span>₱32.00</span></div>
            <div class="r-row"><span class="r-name">Chips (Large)</span><span>×2</span><span>₱70.00</span></div>
            <div class="r-row"><span class="r-name">Laundry Soap</span><span>×1</span><span>₱28.00</span></div>
            <div class="r-total">
                <span>Grand Total</span>
                <span>₱225.00</span>
            </div>
        </div>
    </div>
</div>


<div class="cta-section">
    <h2 class="reveal">Ready to take control<br>of your inventory?</h2>
    <p class="reveal">Join Arca and start managing your store the smart way.</p>
    <a href="auth/register.html" class="btn-primary reveal">
        Create your free account
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </a>
</div>

<!-- ── FOOTER ── -->
<footer>
    <p>© <?= date('Y') ?> Arca. All rights reserved.</p>
</footer>

<script>
    // Scroll reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>

</body>
</html>