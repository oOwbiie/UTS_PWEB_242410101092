<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MoodFlow')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

    {{-- Chart.js via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        /* ═══════════════════════════════════
           CSS Variables & Reset
        ═══════════════════════════════════ */
        :root {
            --bg:       #0e0f17;
            --surface:  #161820;
            --surface2: #1e2030;
            --border:   #2a2d3e;
            --accent:   #7c6ef5;
            --accent2:  #c084fc;
            --green:    #34d399;
            --yellow:   #fbbf24;
            --red:      #f87171;
            --blue:     #60a5fa;
            --text:     #e2e4f0;
            --muted:    #6b7280;
            --radius:   16px;
            --radius-sm: 10px;
            --font-head: 'Syne', sans-serif;
            --font-body: 'DM Sans', sans-serif;
            --shadow:   0 8px 32px rgba(0,0,0,0.4);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-body);
            font-size: 15px;
            line-height: 1.65;
            min-height: 100vh;
        }

        /* Subtle grid texture on body */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(124,110,245,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,110,245,0.03) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 0;
        }

        /* ═══════════════════════════════════
           Layout
        ═══════════════════════════════════ */
        .app-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 24px 20px;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
        }

        /* ═══════════════════════════════════
           Typography
        ═══════════════════════════════════ */
        h1, h2, h3, h4 {
            font-family: var(--font-head);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        a { color: var(--accent); text-decoration: none; transition: opacity 0.2s; }
        a:hover { opacity: 0.8; }

        /* ═══════════════════════════════════
           Cards & Surfaces
        ═══════════════════════════════════ */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(124,110,245,0.4), transparent);
        }

        /* ═══════════════════════════════════
           Buttons
        ═══════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            font-family: var(--font-body);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: #6a5ce0; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(124,110,245,0.4); }

        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { color: var(--text); border-color: var(--accent); }

        .btn-danger {
            background: rgba(248,113,113,0.1);
            color: var(--red);
            border: 1px solid rgba(248,113,113,0.2);
        }
        .btn-danger:hover { background: rgba(248,113,113,0.2); color: var(--red); }

        /* ═══════════════════════════════════
           Forms
        ═══════════════════════════════════ */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            padding: 11px 14px;
            font-family: var(--font-body);
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(124,110,245,0.15);
        }

        .form-textarea { resize: vertical; min-height: 80px; }

        /* ═══════════════════════════════════
           Alert / Flash Messages
        ═══════════════════════════════════ */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.25); color: var(--green); }
        .alert-error   { background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.25); color: var(--red); }
        .alert-info    { background: rgba(124,110,245,0.1); border: 1px solid rgba(124,110,245,0.25); color: var(--accent2); }

        /* ═══════════════════════════════════
           Badge / Tag
        ═══════════════════════════════════ */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .badge-accent  { background: rgba(124,110,245,0.2); color: var(--accent2); }
        .badge-green   { background: rgba(52,211,153,0.15); color: var(--green); }
        .badge-yellow  { background: rgba(251,191,36,0.15); color: var(--yellow); }

        /* ═══════════════════════════════════
           Grid Utilities
        ═══════════════════════════════════ */
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }

        /* ═══════════════════════════════════
           Stat Card
        ═══════════════════════════════════ */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            text-align: center;
        }
        .stat-value {
            font-family: var(--font-head);
            font-size: 2.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        .stat-label {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ═══════════════════════════════════
           Page title
        ═══════════════════════════════════ */
        .page-header {
            margin-bottom: 28px;
        }
        .page-header h1 {
            font-size: 1.9rem;
            background: linear-gradient(135deg, var(--text), var(--muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .page-header p {
            color: var(--muted);
            font-size: 14px;
            margin-top: 4px;
        }

        /* ═══════════════════════════════════
           Scrollbar
        ═══════════════════════════════════ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        /* ═══════════════════════════════════
           Responsive
        ═══════════════════════════════════ */
        @media (max-width: 768px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .main-content { padding: 16px; }
        }
        @media (max-width: 480px) {
            .card { padding: 16px; }
        }

        /* ═══════════════════════════════════
           Fade-in animation
        ═══════════════════════════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .fade-up-delay-1 { animation-delay: 0.08s; }
        .fade-up-delay-2 { animation-delay: 0.16s; }
        .fade-up-delay-3 { animation-delay: 0.24s; }

        @yield('extra-css')
    </style>

    @yield('head')
</head>
<body>
<div class="app-wrapper">

    {{-- Navbar Component --}}
    @if(!Request::routeIs('login'))
        <x-navbar :username="session('username')" />
    @endif

    {{-- Flash Messages --}}
    <div style="max-width:1100px; width:100%; margin:0 auto; padding: 0 20px;">
        @if(session('success'))
            <div class="alert alert-success" style="margin-top:16px;">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info" style="margin-top:16px;">
                ℹ {{ session('info') }}
            </div>
        @endif
        @if($errors->has('login'))
            <div class="alert alert-error" style="margin-top:16px;">
                ✕ {{ $errors->first('login') }}
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Footer Component --}}
    <x-footer />

</div>

@yield('scripts')
</body>
</html>
