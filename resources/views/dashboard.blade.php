@extends('layouts.app')

@section('title', 'Dashboard — MoodFlow')

@section('content')

{{-- Page Header --}}
<div class="page-header fade-up">
    <h1>Dashboard</h1>
    <p>Halo, <strong style="color: var(--accent)">{{ $username }}</strong>! Ini adalah ringkasan mood & produktivitasmu.</p>
</div>

{{-- Statistik Ringkasan --}}
<div class="grid grid-4 fade-up fade-up-delay-1" style="margin-bottom: 24px;">

    <div class="stat-card">
        <div class="stat-value">{{ $stats['total_input'] }}</div>
        <div class="stat-label">Total Catatan</div>
    </div>

    <div class="stat-card">
        <div class="stat-value" style="{{ $stats['rata_mood'] >= 4 ? 'background: linear-gradient(135deg,#34d399,#60a5fa); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;' : '' }}">
            {{ $stats['rata_mood'] ?: '—' }}
        </div>
        <div class="stat-label">Rata-rata Mood</div>
    </div>

    <div class="stat-card">
        <div class="stat-value" style="{{ $stats['rata_energi'] >= 4 ? 'background: linear-gradient(135deg,#fbbf24,#f97316); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;' : '' }}">
            {{ $stats['rata_energi'] ?: '—' }}
        </div>
        <div class="stat-label">Rata-rata Energi</div>
    </div>

    <div class="stat-card">
        <div class="stat-value" style="background: linear-gradient(135deg,#34d399,#c084fc); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
            {{ $stats['hari_produktif'] }}
        </div>
        <div class="stat-label">Hari Produktif</div>
    </div>

</div>

{{-- Rekomendasi & Chart --}}
<div class="grid grid-2 fade-up fade-up-delay-2" style="margin-bottom: 24px; align-items: start;">

    {{-- Rekomendasi --}}
    <div class="rekomendasi-card card rekomendasi-{{ $rekomendasi['warna'] }}">
        <div class="rek-badge">✦ Rekomendasi Untukmu</div>
        <div class="rek-icon">{{ $rekomendasi['icon'] }}</div>
        <h3 class="rek-judul">{{ $rekomendasi['judul'] }}</h3>
        <p class="rek-teks">{{ $rekomendasi['teks'] }}</p>

        @if($stats['total_input'] === 0)
            <a href="{{ route('pengelolaan') }}" class="btn btn-primary" style="margin-top: 20px; width: 100%; justify-content: center;">
                Mulai Catat Sekarang →
            </a>
        @endif
    </div>

    {{-- Grafik --}}
    <div class="card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <div>
                <h3 style="font-size: 1rem;">Grafik Mood & Energi</h3>
                <p style="font-size:12px; color: var(--muted); margin-top:2px;">7 input terakhir</p>
            </div>
            <span class="badge badge-accent">Live Data</span>
        </div>

        @if(count($chartData['labels']) === 0)
            <div class="empty-chart">
                <p>📊</p>
                <p>Grafik akan muncul setelah kamu menambahkan data.</p>
                <a href="{{ route('pengelolaan') }}" class="btn btn-ghost" style="margin-top:12px; font-size:13px;">Tambah Data</a>
            </div>
        @else
            <canvas id="moodChart" height="220"></canvas>
        @endif
    </div>

</div>

{{-- Tips Produktivitas --}}
<div class="card fade-up fade-up-delay-3">
    <div style="display:flex; align-items:center; gap:10px; margin-bottom: 20px;">
        <h3 style="font-size: 1rem;">💡 Tips Produktivitas</h3>
        <span class="badge badge-yellow">Curated</span>
    </div>

    <div class="tips-grid">
        @foreach([
            ['🌅', 'Pagi Hari', 'Luangkan 5 menit untuk catat mood pagi sebelum mulai aktivitas. Pagi yang sadar = hari yang terencana.'],
            ['🧠', 'Zona Fokus', 'Kerjakan tugas terberat saat energi dan moodmu di atas 3. Jangan buang peak hour untuk hal sepele.'],
            ['🌙', 'Review Malam', 'Sebelum tidur, refleksi 1 hal baik dan 1 hal yang mau diperbaiki besok. Konsistensi adalah kuncinya.'],
            ['💧', 'Jaga Dasar', 'Tidur cukup, makan teratur, dan gerak ringan adalah fondasi mood stabil. Jangan skip basics.'],
        ] as $tip)
            <div class="tip-item">
                <div class="tip-icon">{{ $tip[0] }}</div>
                <div>
                    <div class="tip-title">{{ $tip[1] }}</div>
                    <p class="tip-desc">{{ $tip[2] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
/* Rekomendasi Card Colors */
.rekomendasi-card { position: relative; }
.rekomendasi-green  { border-color: rgba(52,211,153,0.3); }
.rekomendasi-yellow { border-color: rgba(251,191,36,0.3); }
.rekomendasi-blue   { border-color: rgba(96,165,250,0.3); }
.rekomendasi-purple { border-color: rgba(192,132,252,0.3); }
.rekomendasi-neutral { border-color: var(--border); }

.rek-badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 16px;
}

.rek-icon {
    font-size: 3rem;
    line-height: 1;
    margin-bottom: 12px;
}

.rek-judul {
    font-size: 1.25rem;
    margin-bottom: 10px;
    color: var(--text);
}

.rek-teks {
    font-size: 14px;
    color: var(--muted);
    line-height: 1.7;
}

/* Tips Grid */
.tips-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.tip-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 14px;
    background: var(--surface2);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    transition: border-color 0.2s;
}

.tip-item:hover { border-color: var(--accent); }

.tip-icon { font-size: 1.5rem; flex-shrink: 0; line-height: 1; margin-top: 2px; }

.tip-title {
    font-family: var(--font-head);
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 4px;
}

.tip-desc { font-size: 12.5px; color: var(--muted); line-height: 1.6; }

/* Empty Chart */
.empty-chart {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 180px;
    color: var(--muted);
    font-size: 13px;
    text-align: center;
    gap: 8px;
}

.empty-chart p:first-child { font-size: 2.5rem; }

@media (max-width: 768px) {
    .tips-grid { grid-template-columns: 1fr; }
}
</style>

@endsection

@section('scripts')
@if(count($chartData['labels']) > 0)
<script>
    const ctx = document.getElementById('moodChart').getContext('2d');

    const labels = @json($chartData['labels']);
    const moodData = @json($chartData['mood']);
    const energiData = @json($chartData['energi']);

    // Format labels — ambil waktu saja supaya lebih ringkas
    const shortLabels = labels.map(l => {
        const parts = l.split(' ');
        return parts[parts.length - 1] || l;
    });

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: shortLabels,
            datasets: [
                {
                    label: 'Mood',
                    data: moodData,
                    borderColor: '#7c6ef5',
                    backgroundColor: 'rgba(124,110,245,0.15)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#7c6ef5',
                    pointRadius: 5,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Energi',
                    data: energiData,
                    borderColor: '#34d399',
                    backgroundColor: 'rgba(52,211,153,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#34d399',
                    pointRadius: 5,
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    min: 0, max: 5,
                    ticks: {
                        color: '#6b7280',
                        stepSize: 1,
                        callback: v => ['', '😞', '😕', '😐', '🙂', '😄'][v] || v
                    },
                    grid: { color: 'rgba(255,255,255,0.04)' }
                },
                x: {
                    ticks: { color: '#6b7280', font: { size: 11 } },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: {
                    labels: { color: '#e2e4f0', font: { family: 'DM Sans' }, boxWidth: 12, padding: 16 }
                },
                tooltip: {
                    backgroundColor: '#1e2030',
                    borderColor: '#2a2d3e',
                    borderWidth: 1,
                    titleColor: '#e2e4f0',
                    bodyColor: '#6b7280',
                    padding: 12,
                }
            }
        }
    });
</script>
@endif
@endsection
