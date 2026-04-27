@extends('layouts.app')

@section('title', 'Profile — MoodFlow')

@section('content')

{{-- Page Header --}}
<div class="page-header fade-up">
    <h1>Profile</h1>
    <p>Informasi akun dan rekap perjalanan mood-mu, <strong style="color: var(--accent)">{{ $username }}</strong>.</p>
</div>

<div class="profile-layout fade-up fade-up-delay-1">

    {{-- ═══ PROFILE CARD ═══ --}}
    <div>
        <div class="card profile-card">
            {{-- Avatar & Info --}}
            <div class="profile-avatar-wrap">
                <div class="profile-avatar">{{ strtoupper(substr($username, 0, 1)) }}</div>
                <div class="profile-ring"></div>
            </div>

            <h2 class="profile-nama">{{ $info['nama_lengkap'] }}</h2>
            <p class="profile-username">@{{ $username }}</p>

            <div class="profile-badge-row">
                <span class="badge badge-accent">MoodFlow Member</span>
                <span class="badge badge-green">Bergabung {{ $stats['bergabung'] }}</span>
            </div>

            <p class="profile-bio">{{ $info['bio'] }}</p>

            <div class="profile-info-list">
                <div class="info-row">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <span>{{ $info['email'] }}</span>
                </div>
                <div class="info-row">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>Login terakhir: {{ $loginTime }}</span>
                </div>
            </div>

            <a href="{{ route('logout') }}" class="btn btn-danger" style="width:100%; justify-content:center; margin-top:20px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout dari Akun Ini
            </a>
        </div>
    </div>

    {{-- ═══ STATS & RIWAYAT ═══ --}}
    <div class="profile-right">

        {{-- Statistik Akun --}}
        <div class="card" style="margin-bottom: 20px;">
            <h3 style="font-size: 1rem; margin-bottom: 16px;">📊 Statistik Akun</h3>
            <div class="grid grid-2" style="gap: 12px;">

                <div class="stat-card mini">
                    <div class="stat-value" style="font-size:1.8rem;">{{ $stats['total_catatan'] }}</div>
                    <div class="stat-label">Total Catatan</div>
                </div>

                <div class="stat-card mini">
                    <div class="stat-value" style="font-size:1.8rem; background: linear-gradient(135deg,#34d399,#60a5fa); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
                        {{ $stats['mood_terbaik'] ?: '—' }}
                    </div>
                    <div class="stat-label">Mood Tertinggi</div>
                </div>

                <div class="stat-card mini">
                    <div class="stat-value" style="font-size:1.8rem; background: linear-gradient(135deg,#fbbf24,#f97316); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
                        {{ $stats['energi_terbaik'] ?: '—' }}
                    </div>
                    <div class="stat-label">Energi Tertinggi</div>
                </div>

                <div class="stat-card mini">
                    <div class="stat-label" style="font-size:11px; margin-bottom:4px;">Aktivitas Favorit</div>
                    <div style="font-size:12px; color: var(--accent2); font-weight:600; word-break:break-word;">
                        {{ $stats['aktivitas_fav'] }}
                    </div>
                </div>

            </div>
        </div>

        {{-- Riwayat 5 Terakhir --}}
        <div class="card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                <h3 style="font-size: 1rem;">🕑 5 Catatan Terakhir</h3>
                <a href="{{ route('pengelolaan') }}" class="btn btn-ghost" style="font-size:12px; padding: 5px 12px;">
                    Lihat Semua →
                </a>
            </div>

            @if(count($moodHistory) === 0)
                <div style="text-align:center; padding: 32px; color: var(--muted); font-size:13px;">
                    <p style="font-size:2rem; margin-bottom:8px;">📭</p>
                    <p>Belum ada catatan mood.</p>
                    <a href="{{ route('pengelolaan') }}" class="btn btn-primary" style="margin-top:12px; font-size:13px;">
                        Tambah Sekarang
                    </a>
                </div>
            @else
                <div class="recent-list">
                    @foreach(array_slice(array_reverse($moodHistory), 0, 5) as $item)
                        <div class="recent-item">
                            <div class="recent-left">
                                <div class="recent-emoji">
                                    @if($item['mood'] == 5) 😄
                                    @elseif($item['mood'] == 4) 🙂
                                    @elseif($item['mood'] == 3) 😐
                                    @elseif($item['mood'] == 2) 😕
                                    @else 😞
                                    @endif
                                </div>
                                <div>
                                    <div class="recent-aktivitas">{{ $item['aktivitas'] }}</div>
                                    <div class="recent-date">{{ $item['tanggal'] }} · {{ $item['waktu'] }}</div>
                                </div>
                            </div>
                            <div class="recent-scores">
                                <div class="score-mini mood-mini">M:{{ $item['mood'] }}</div>
                                <div class="score-mini energi-mini">E:{{ $item['energi'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

<style>
/* ── Layout ── */
.profile-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    align-items: start;
}

/* ── Profile Card ── */
.profile-card { text-align: center; position: relative; }

.profile-avatar-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 16px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-head);
    font-size: 2rem;
    font-weight: 800;
    color: white;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.profile-ring {
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 2px solid transparent;
    background: linear-gradient(135deg, var(--accent), var(--accent2)) border-box;
    -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: destination-out;
    mask-composite: exclude;
    animation: spin 6s linear infinite;
}

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.profile-nama {
    font-size: 1.2rem;
    color: var(--text);
    margin-bottom: 4px;
}

.profile-username {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 12px;
}

.profile-badge-row {
    display: flex;
    gap: 6px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

.profile-bio {
    font-size: 13px;
    color: var(--muted);
    line-height: 1.6;
    font-style: italic;
    padding: 12px;
    background: var(--surface2);
    border-radius: 8px;
    border: 1px solid var(--border);
    margin-bottom: 16px;
    text-align: left;
}

.profile-info-list { display: flex; flex-direction: column; gap: 8px; text-align: left; }

.info-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    color: var(--muted);
}

.info-row svg { flex-shrink: 0; color: var(--accent); }

/* ── Stat Card Mini ── */
.stat-card.mini {
    padding: 14px;
    text-align: center;
}

/* ── Recent List ── */
.recent-list { display: flex; flex-direction: column; gap: 10px; }

.recent-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    background: var(--surface2);
    border-radius: 10px;
    border: 1px solid var(--border);
    transition: border-color 0.2s;
}
.recent-item:hover { border-color: var(--accent); }

.recent-left { display: flex; align-items: center; gap: 10px; }

.recent-emoji { font-size: 1.5rem; line-height: 1; }

.recent-aktivitas { font-size: 13px; font-weight: 500; color: var(--text); }
.recent-date { font-size: 11px; color: var(--muted); margin-top: 2px; }

.recent-scores { display: flex; gap: 5px; }

.score-mini {
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}

.mood-mini   { background: rgba(124,110,245,0.15); color: var(--accent2); }
.energi-mini { background: rgba(52,211,153,0.12);  color: var(--green);   }

/* ── Responsive ── */
@media (max-width: 768px) {
    .profile-layout { grid-template-columns: 1fr; }
    .profile-card { max-width: 400px; margin: 0 auto; }
}
</style>

@endsection
