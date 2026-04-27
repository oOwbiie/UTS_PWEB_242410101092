@props(['username' => 'Guest'])

<nav class="navbar">
    <div class="nav-inner">
        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="nav-logo">
            <span class="logo-icon">◈</span>
            <span class="logo-text">Mood<span class="logo-accent">Flow</span></span>
        </a>

        {{-- Navigation Links --}}
        <ul class="nav-links">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('pengelolaan') }}"
                   class="nav-link {{ Request::routeIs('pengelolaan') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Pengelolaan
                </a>
            </li>
            <li>
                <a href="{{ route('profile') }}"
                   class="nav-link {{ Request::routeIs('profile') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Profile
                </a>
            </li>
        </ul>

        {{-- User Info + Logout --}}
        <div class="nav-user">
            <div class="user-chip">
                <div class="user-avatar">{{ strtoupper(substr($username, 0, 1)) }}</div>
                <span class="user-name">{{ $username }}</span>
            </div>
            <a href="{{ route('logout') }}" class="btn btn-ghost btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Keluar
            </a>
        </div>

        {{-- Mobile Hamburger --}}
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('dashboard') }}" class="mobile-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('pengelolaan') }}" class="mobile-link {{ Request::routeIs('pengelolaan') ? 'active' : '' }}">Pengelolaan</a>
        <a href="{{ route('profile') }}" class="mobile-link {{ Request::routeIs('profile') ? 'active' : '' }}">Profile</a>
        <a href="{{ route('logout') }}" class="mobile-link" style="color: var(--red);">Keluar</a>
    </div>
</nav>

<style>
.navbar {
    background: rgba(14,15,23,0.85);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid var(--border);
    position: sticky;
    top: 0;
    z-index: 100;
}

.nav-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px;
    height: 60px;
    display: flex;
    align-items: center;
    gap: 24px;
}

.nav-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    flex-shrink: 0;
}

.logo-icon {
    font-size: 20px;
    color: var(--accent);
    line-height: 1;
}

.logo-text {
    font-family: var(--font-head);
    font-weight: 800;
    font-size: 1.15rem;
    color: var(--text);
    letter-spacing: -0.03em;
}

.logo-accent { color: var(--accent); }

.nav-links {
    display: flex;
    list-style: none;
    gap: 4px;
    flex: 1;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    color: var(--muted);
    font-size: 13.5px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.nav-link:hover { color: var(--text); background: var(--surface2); }
.nav-link.active { color: var(--accent); background: rgba(124,110,245,0.12); }

.nav-user {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.user-chip {
    display: flex;
    align-items: center;
    gap: 8px;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-head);
    font-size: 13px;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}

.user-name {
    font-size: 13px;
    font-weight: 500;
    color: var(--text);
}

.btn-sm { padding: 6px 12px; font-size: 13px; }

.hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    margin-left: auto;
}

.hamburger span {
    display: block;
    width: 22px;
    height: 2px;
    background: var(--muted);
    border-radius: 2px;
    transition: all 0.3s;
}

.mobile-menu {
    display: none;
    flex-direction: column;
    padding: 12px 20px 16px;
    border-top: 1px solid var(--border);
    gap: 4px;
}

.mobile-menu.open { display: flex; }

.mobile-link {
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--muted);
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.mobile-link:hover, .mobile-link.active {
    color: var(--text);
    background: var(--surface2);
}

@media (max-width: 768px) {
    .nav-links, .nav-user .user-chip, .nav-user .btn { display: none; }
    .hamburger { display: flex; }
    .nav-user { display: none; }
}
</style>

<script>
document.getElementById('hamburger')?.addEventListener('click', function() {
    document.getElementById('mobileMenu').classList.toggle('open');
});
</script>
