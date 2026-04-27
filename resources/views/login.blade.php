@extends('layouts.app')

@section('title', 'Login — MoodFlow')

@section('head')
<style>
    body { background: var(--bg); }

    .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        position: relative;
        z-index: 1;
    }

    /* Glowing blobs */
    .blob {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.12;
        pointer-events: none;
        z-index: 0;
    }
    .blob-1 {
        width: 400px; height: 400px;
        background: var(--accent);
        top: -100px; left: -100px;
    }
    .blob-2 {
        width: 300px; height: 300px;
        background: var(--accent2);
        bottom: -60px; right: -60px;
    }

    .login-container {
        width: 100%;
        max-width: 420px;
        position: relative;
        z-index: 1;
        animation: fadeUp 0.6s ease both;
    }

    .login-header {
        text-align: center;
        margin-bottom: 36px;
    }

    .login-logo {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .login-logo-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 8px 24px rgba(124,110,245,0.4);
    }

    .login-logo-name {
        font-family: var(--font-head);
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.04em;
    }

    .login-tagline {
        font-size: 14px;
        color: var(--muted);
        line-height: 1.5;
    }

    .login-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0; left: 20%; right: 20%;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    .login-card h2 {
        font-size: 1.3rem;
        margin-bottom: 6px;
        color: var(--text);
    }

    .login-card .subtitle {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 28px;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    .input-icon-wrapper .form-input {
        padding-left: 38px;
    }

    .demo-accounts {
        margin-top: 24px;
        padding: 16px;
        background: var(--surface2);
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .demo-title {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 10px;
    }

    .demo-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }

    .demo-item {
        font-size: 11px;
        color: var(--muted);
        font-family: 'Courier New', monospace;
        padding: 4px 8px;
        background: var(--bg);
        border-radius: 6px;
        cursor: pointer;
        transition: color 0.2s;
    }
    .demo-item:hover { color: var(--accent); }
    .demo-item strong { color: var(--text); }

    .btn-login {
        width: 100%;
        justify-content: center;
        padding: 13px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 12px;
        margin-top: 8px;
    }

    /* Error for individual fields */
    .field-error {
        font-size: 12px;
        color: var(--red);
        margin-top: 5px;
    }
</style>
@endsection

@section('content')
{{-- Override main-content padding for full-page login --}}
@endsection

{{-- Custom full-page layout, override the main section via script --}}
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="login-page">
    <div class="login-container">

        {{-- Header / Logo --}}
        <div class="login-header">
            <div class="login-logo">
                <div class="login-logo-icon">◈</div>
                <span class="login-logo-name">MoodFlow</span>
            </div>
            <p class="login-tagline">Catat mood, energi, dan aktivitas harian.<br>Temukan pola produktivitasmu.</p>
        </div>

        {{-- Error Alert --}}
        @if($errors->has('login'))
            <div class="alert alert-error" style="margin-bottom: 16px;">
                ✕ {{ $errors->first('login') }}
            </div>
        @endif

        {{-- Login Card --}}
        <div class="login-card">
            <h2>Selamat Datang</h2>
            <p class="subtitle">Masuk untuk mulai tracking harimu</p>

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <div class="input-icon-wrapper">
                        <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-input"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                        >
                    </div>
                    @error('username')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-icon-wrapper">
                        <svg class="input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                        >
                    </div>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    Masuk ke MoodFlow
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </form>

            {{-- Demo Accounts --}}
            <div class="demo-accounts">
                <p class="demo-title">👆 Akun Demo — klik untuk mengisi otomatis</p>
                <div class="demo-list">
                    <div class="demo-item" onclick="fillLogin('rizky','rizky123')"><strong>rizky</strong> / rizky123</div>
                    <div class="demo-item" onclick="fillLogin('andi','andi123')"><strong>andi</strong> / andi123</div>
                    <div class="demo-item" onclick="fillLogin('sari','sari123')"><strong>sari</strong> / sari123</div>
                    <div class="demo-item" onclick="fillLogin('demo','demo1234')"><strong>demo</strong> / demo1234</div>
                </div>
            </div>
        </div>

    </div>
</div>

@section('scripts')
<script>
    function fillLogin(user, pass) {
        document.getElementById('username').value = user;
        document.getElementById('password').value = pass;
    }

    // Override layout agar main-content tidak tambah padding
    document.querySelector('.main-content').style.padding = '0';
    document.querySelector('.main-content').style.maxWidth = '100%';
</script>
@endsection
