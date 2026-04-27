@extends('layouts.app')

@section('title', 'Pengelolaan Mood — MoodFlow')

@section('content')

{{-- Page Header --}}
<div class="page-header fade-up">
    <h1>Pengelolaan Mood & Energi</h1>
    <p>Catat kondisimu hari ini dan pantau histori aktivitas, <strong style="color: var(--accent)">{{ $username }}</strong>.</p>
</div>

<div class="peng-layout fade-up fade-up-delay-1">

    {{-- ═══ FORM INPUT ═══ --}}
    <div class="form-section">
        <div class="card">
            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 1.05rem;">✏️ Tambah Catatan Baru</h3>
                <p style="font-size:13px; color: var(--muted); margin-top: 4px;">Isi setiap hari untuk hasil analisis yang lebih akurat</p>
            </div>

            <form action="{{ route('pengelolaan.simpan') }}" method="POST">
                @csrf

                {{-- Mood --}}
                <div class="form-group">
                    <label class="form-label">Bagaimana Moodmu? <span style="color:var(--red)">*</span></label>
                    <div class="emoji-selector" id="moodSelector">
                        @foreach($daftarMood as $nilai => $label)
                            <label class="emoji-option">
                                <input type="radio" name="mood" value="{{ $nilai }}" {{ old('mood') == $nilai ? 'checked' : '' }} required>
                                <div class="emoji-card">
                                    <span class="emoji-icon">{{ explode(' ', $label)[0] }}</span>
                                    <span class="emoji-label">{{ implode(' ', array_slice(explode(' ', $label), 1)) }}</span>
                                    <span class="emoji-val">{{ $nilai }}/5</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('mood')
                        <p style="font-size:12px; color:var(--red); margin-top:6px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Energi --}}
                <div class="form-group">
                    <label class="form-label">Level Energimu? <span style="color:var(--red)">*</span></label>
                    <div class="emoji-selector" id="energiSelector">
                        @foreach($daftarEnergi as $nilai => $label)
                            <label class="emoji-option">
                                <input type="radio" name="energi" value="{{ $nilai }}" {{ old('energi') == $nilai ? 'checked' : '' }} required>
                                <div class="emoji-card">
                                    <span class="emoji-icon">{{ explode(' ', $label)[0] }}</span>
                                    <span class="emoji-label">{{ implode(' ', array_slice(explode(' ', $label), 1)) }}</span>
                                    <span class="emoji-val">{{ $nilai }}/5</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('energi')
                        <p style="font-size:12px; color:var(--red); margin-top:6px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Aktivitas --}}
                <div class="form-group">
                    <label class="form-label" for="aktivitas">Aktivitas Utama <span style="color:var(--red)">*</span></label>
                    <select name="aktivitas" id="aktivitas" class="form-select" required>
                        <option value="" disabled {{ old('aktivitas') ? '' : 'selected' }}>Pilih aktivitas...</option>
                        @foreach($daftarAktivitas as $a)
                            <option value="{{ $a }}" {{ old('aktivitas') === $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                    @error('aktivitas')
                        <p style="font-size:12px; color:var(--red); margin-top:6px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="form-group">
                    <label class="form-label" for="catatan">Catatan Tambahan <span style="color:var(--muted); font-weight:400;">(opsional)</span></label>
                    <textarea name="catatan" id="catatan" class="form-textarea" placeholder="Apa yang terjadi hari ini? Hal apa yang memengaruhi moodmu?...">{{ old('catatan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:13px; font-size:15px;">
                    Simpan Catatan
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- ═══ HISTORI DATA ═══ --}}
    <div class="history-section">
        <div class="card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
                <div>
                    <h3 style="font-size: 1.05rem;">📋 Histori Catatan</h3>
                    <p style="font-size:12px; color:var(--muted); margin-top:3px;">
                        {{ count($moodData) }} catatan tersimpan
                    </p>
                </div>
                @if(count($moodData) > 0)
                    <span class="badge badge-green">{{ count($moodData) }} Data</span>
                @endif
            </div>

            @if(count($moodData) === 0)
                {{-- Empty State --}}
                <div class="empty-state">
                    <div class="empty-icon">📝</div>
                    <h4>Belum ada catatan</h4>
                    <p>Tambahkan catatan pertamamu menggunakan form di samping!</p>
                </div>

            @else
                {{-- Data List via @foreach --}}
                <div class="history-list">
                    @foreach(array_reverse($moodData) as $item)
                        <div class="history-item fade-up" style="animation-delay: {{ $loop->index * 0.04 }}s">

                            {{-- Tanggal & Waktu --}}
                            <div class="hi-meta">
                                <span class="hi-date">{{ $item['tanggal'] }}</span>
                                <span class="hi-time">{{ $item['waktu'] }}</span>
                            </div>

                            {{-- Indikator Mood & Energi --}}
                            <div class="hi-scores">
                                <div class="score-pill mood-pill">
                                    <span class="score-emoji">
                                        @if($item['mood'] == 5) 😄
                                        @elseif($item['mood'] == 4) 🙂
                                        @elseif($item['mood'] == 3) 😐
                                        @elseif($item['mood'] == 2) 😕
                                        @else 😞
                                        @endif
                                    </span>
                                    <span class="score-num">{{ $item['mood'] }}</span>
                                </div>
                                <div class="score-pill energi-pill">
                                    <span class="score-emoji">
                                        @if($item['energi'] == 5) ⚡
                                        @elseif($item['energi'] == 4) 💪
                                        @elseif($item['energi'] == 3) 🔋
                                        @elseif($item['energi'] == 2) 😴
                                        @else 🪫
                                        @endif
                                    </span>
                                    <span class="score-num">{{ $item['energi'] }}</span>
                                </div>
                            </div>

                            {{-- Aktivitas --}}
                            <div class="hi-aktivitas">
                                <span class="badge badge-accent">{{ $item['aktivitas'] }}</span>
                            </div>

                            {{-- Catatan (jika ada) --}}
                            @if($item['catatan'] !== '-' && !empty($item['catatan']))
                                <p class="hi-catatan">"{{ $item['catatan'] }}"</p>
                            @endif

                            {{-- Bar visual --}}
                            <div class="hi-bars">
                                <div class="bar-row">
                                    <span class="bar-label">Mood</span>
                                    <div class="bar-track">
                                        <div class="bar-fill bar-mood" style="width: {{ ($item['mood'] / 5) * 100 }}%"></div>
                                    </div>
                                </div>
                                <div class="bar-row">
                                    <span class="bar-label">Energi</span>
                                    <div class="bar-track">
                                        <div class="bar-fill bar-energi" style="width: {{ ($item['energi'] / 5) * 100 }}%"></div>
                                    </div>
                                </div>
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
.peng-layout {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 24px;
    align-items: start;
}

/* ── Emoji Selector ── */
.emoji-selector {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
}

.emoji-option input { display: none; }

.emoji-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 6px;
    background: var(--surface2);
    border: 2px solid var(--border);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
}

.emoji-card:hover { border-color: var(--accent); background: rgba(124,110,245,0.08); }

.emoji-option input:checked + .emoji-card {
    border-color: var(--accent);
    background: rgba(124,110,245,0.15);
    box-shadow: 0 0 0 3px rgba(124,110,245,0.15);
}

.emoji-icon { font-size: 1.5rem; line-height: 1; margin-bottom: 4px; }
.emoji-label { font-size: 9px; color: var(--muted); line-height: 1.2; }
.emoji-val { font-size: 9px; color: var(--accent); font-weight: 600; margin-top: 2px; }

/* ── Empty State ── */
.empty-state {
    text-align: center;
    padding: 48px 20px;
    color: var(--muted);
}
.empty-icon { font-size: 3rem; margin-bottom: 12px; }
.empty-state h4 { font-size: 1rem; color: var(--text); margin-bottom: 8px; }
.empty-state p { font-size: 13px; }

/* ── History List ── */
.history-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 680px;
    overflow-y: auto;
    padding-right: 4px;
}

.history-item {
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px 16px;
    transition: border-color 0.2s;
}
.history-item:hover { border-color: var(--accent); }

.hi-meta {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 10px;
}
.hi-date { font-size: 12px; font-weight: 600; color: var(--text); }
.hi-time { font-size: 11px; color: var(--muted); }

.hi-scores { display: flex; gap: 8px; margin-bottom: 10px; }

.score-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.mood-pill   { background: rgba(124,110,245,0.15); color: var(--accent2); border: 1px solid rgba(124,110,245,0.2); }
.energi-pill { background: rgba(52,211,153,0.12);  color: var(--green);   border: 1px solid rgba(52,211,153,0.2); }

.score-emoji { font-size: 14px; }
.score-num   { font-size: 13px; }

.hi-aktivitas { margin-bottom: 10px; }

.hi-catatan {
    font-size: 12px;
    color: var(--muted);
    font-style: italic;
    margin-bottom: 10px;
    padding-left: 10px;
    border-left: 2px solid var(--border);
    line-height: 1.5;
}

/* ── Progress Bars ── */
.hi-bars { display: flex; flex-direction: column; gap: 5px; }
.bar-row { display: flex; align-items: center; gap: 8px; }
.bar-label { font-size: 10px; color: var(--muted); width: 36px; flex-shrink: 0; }
.bar-track { flex: 1; height: 4px; background: var(--border); border-radius: 4px; overflow: hidden; }
.bar-fill { height: 100%; border-radius: 4px; transition: width 0.6s ease; }
.bar-mood   { background: linear-gradient(90deg, var(--accent), var(--accent2)); }
.bar-energi { background: linear-gradient(90deg, var(--green), #60a5fa); }

/* ── Responsive ── */
@media (max-width: 960px) {
    .peng-layout { grid-template-columns: 1fr; }
    .emoji-selector { grid-template-columns: repeat(5, 1fr); }
}

@media (max-width: 480px) {
    .emoji-selector { grid-template-columns: repeat(3, 1fr); }
    .emoji-label { display: none; }
}
</style>

@endsection
