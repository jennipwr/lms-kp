@extends('layouts.index')

@section('content')
<div class="hk-page">

    {{-- Breadcrumb --}}
    <div class="hk-breadcrumb">
        <a href="{{ route('admin.hasil-kuesioner') }}"><i class="bi bi-house-door me-1"></i>Hasil Kuesioner</a>
        <span class="sep">›</span>
        <span>Detail Mahasiswa</span>
    </div>

    {{-- Profile --}}
    <div class="profile-card">
        <div class="profile-avatar"><i class="bi bi-person-fill"></i></div>
        <div class="profile-info">
            <h2>{{ optional(optional($hasil->mahasiswa)->user)->name ?? 'Mahasiswa' }}</h2>
            <div class="nrp">NRP: {{ $hasil->mahasiswa_nrp }}</div>
            <div class="profile-meta">
                <div class="profile-meta-item">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    {{ optional($hasil->listKuesioner)->nama_kuesioner ?? '-' }}
                </div>
                <div class="profile-meta-item">
                    <i class="bi bi-calendar3"></i>
                    {{ \Carbon\Carbon::parse($hasil->created_at)->isoFormat('D MMMM YYYY') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Dimensi Score Cards --}}
    <div class="dimensi-grid">
        @foreach($ringkasan as $key => $dim)
        @php
            /* Skor: -11 s.d. +11; tengah = 0, kiri = left, kanan = right */
            $skor   = $dim['skor'];
            $absVal = abs($skor);
            $maxVal = 11;
            $pct    = ($absVal / $maxVal) * 50; // 0-50%
            $isLeft = $skor <= 0;
            $fillLeft  = $isLeft ? (50 - $pct) : 50;
            $fillWidth = $pct;
            $thumbPos  = $isLeft ? (50 - $pct) : (50 + $pct);
            $fillColor = $isLeft ? '#1565C0' : '#2E7D32';
        @endphp
        <div class="dim-card">
            <div class="dim-card-header">
                <div>
                    <div class="dim-label">{{ $dim['label'] }}</div>
                    <div class="dim-result">{{ ucfirst($dim['hasil']) }}</div>
                </div>
                <span class="dim-kat-badge {{ strtolower($dim['kategori'] ?? '') }}">
                    {{ ucfirst($dim['kategori'] ?? '') }}
                </span>
            </div>
            <div class="slider-wrap">
                <div class="slider-labels">
                    <span>{{ ucfirst($dim['left']) }}</span>
                    <span>{{ ucfirst($dim['right']) }}</span>
                </div>
                <div class="slider-track">
                    {{-- center line --}}
                    <div style="position:absolute;left:50%;top:-3px;width:2px;height:16px;background:var(--border);z-index:1;"></div>
                    <div class="slider-fill" style="left:{{ $fillLeft }}%;width:{{ $fillWidth }}%;background:{{ $fillColor }};opacity:.35;"></div>
                    <div class="slider-thumb" style="left:{{ $thumbPos }}%;border-color:{{ $fillColor }};"></div>
                </div>
                <div class="slider-score">
                    Skor: {{ $skor > 0 ? '+' : '' }}{{ $skor }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Jawaban per Dimensi --}}
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--text-main);margin-bottom:1rem;">
        <i class="bi bi-list-check me-2 text-primary"></i>Jawaban Detail
    </h2>

    @forelse($jawaban as $dimensi => $items)
    <div class="section-card">
        <div class="section-header" onclick="toggleSection('dim-{{ Str::slug($dimensi) }}', this)">
            <h3><i class="bi bi-layers-fill"></i>Dimensi: {{ ucfirst($dimensi) }}</h3>
            <i class="bi bi-chevron-down section-toggle"></i>
        </div>
        <div class="section-body" id="dim-{{ Str::slug($dimensi) }}">
            <table class="jawaban-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Pertanyaan</th>
                        <th>Opsi A</th>
                        <th>Opsi B</th>
                        <th>Jawaban</th>
                        <th>Kutub</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $idx => $jwb)
                    <tr>
                        <td class="q-no">{{ $idx + 1 }}</td>
                        <td>{{ optional($jwb->kuesioner)->pertanyaan ?? '-' }}</td>
                        <td>
                            <span class="opsi-chip {{ $jwb->jawaban === 'a' ? 'selected-a' : '' }}">
                                @if($jwb->jawaban === 'a')<i class="bi bi-check-circle-fill"></i>@endif
                                {{ optional($jwb->kuesioner)->opsi_a ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="opsi-chip {{ $jwb->jawaban === 'b' ? 'selected-b' : '' }}">
                                @if($jwb->jawaban === 'b')<i class="bi bi-check-circle-fill"></i>@endif
                                {{ optional($jwb->kuesioner)->opsi_b ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ strtoupper($jwb->jawaban) }}</strong>
                        </td>
                        <td><span class="kutub-tag">{{ $jwb->kutub }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="section-card" style="padding:2rem;text-align:center;color:var(--text-muted);">
        <i class="bi bi-inbox" style="font-size:2rem;opacity:.3;"></i>
        <p class="mt-2">Tidak ada jawaban ditemukan.</p>
    </div>
    @endforelse

</div>
@endsection

@section('ExtraCSS')
<style>
    :root {
        --itb-navy:     #0D1F3C;
        --itb-navy-mid: #1A3560;
        --mcu-blue:     #1565C0;
        --mcu-gold:     #C9A84C;
        --mcu-green:    #2E7D32;
        --bg-light:     #F4F6FB;
        --text-main:    #1A2035;
        --text-muted:   #6B7A99;
        --border:       #D6DFF0;
        --white:        #FFFFFF;
        --danger:       #C62828;
    }
    .hk-page { background: var(--bg-light); min-height: 100vh; padding: 2rem; }

    /* Breadcrumb */
    .hk-breadcrumb { display: flex; align-items: center; gap: .5rem; font-size: .85rem; color: var(--text-muted); margin-bottom: 1.5rem; }
    .hk-breadcrumb a { color: var(--mcu-blue); text-decoration: none; }
    .hk-breadcrumb a:hover { text-decoration: underline; }
    .hk-breadcrumb .sep { opacity: .4; }

    /* Profile Card */
    .profile-card {
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 60%, var(--mcu-blue) 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    .profile-card::before {
        content: '';
        position: absolute; top: -80px; right: -80px;
        width: 260px; height: 260px;
        background: rgba(201,168,76,.1); border-radius: 50%;
    }
    .profile-avatar {
        width: 72px; height: 72px;
        background: rgba(255,255,255,.15);
        border: 2px solid rgba(255,255,255,.3);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: var(--white); flex-shrink: 0;
        position: relative; z-index: 1;
    }
    .profile-info { position: relative; z-index: 1; }
    .profile-info h2 { color: var(--white); font-size: 1.4rem; font-weight: 700; margin: 0 0 .25rem; }
    .profile-info .nrp { color: rgba(255,255,255,.7); font-family: 'Courier New', monospace; font-size: .9rem; }
    .profile-meta { display: flex; gap: 1.5rem; margin-top: .75rem; flex-wrap: wrap; }
    .profile-meta-item { color: rgba(255,255,255,.65); font-size: .83rem; display: flex; align-items: center; gap: .4rem; }

    /* Dimensi Grid */
    .dimensi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .dim-card {
        background: var(--white);
        border-radius: 14px;
        border: 1px solid var(--border);
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(13,31,60,.05);
        transition: box-shadow .2s, transform .2s;
    }
    .dim-card:hover { box-shadow: 0 6px 24px rgba(13,31,60,.1); transform: translateY(-2px); }
    .dim-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
    .dim-label { font-size: .8rem; text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted); font-weight: 600; }
    .dim-result { font-size: 1.1rem; font-weight: 800; color: var(--itb-navy); }
    .dim-kat-badge {
        font-size: .72rem; font-weight: 700;
        padding: .22rem .7rem; border-radius: 20px;
    }
    .dim-kat-badge.mild     { background: #e8f5e9; color: #2e7d32; }
    .dim-kat-badge.moderate { background: #fff8e1; color: #e65100; }
    .dim-kat-badge.strong   { background: #fce4ec; color: #c62828; }

    /* Slider Bar */
    .slider-wrap { margin-top: .5rem; }
    .slider-labels { display: flex; justify-content: space-between; font-size: .75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: .4rem; }
    .slider-track {
        position: relative;
        height: 10px;
        background: var(--bg-light);
        border-radius: 10px;
        border: 1px solid var(--border);
        overflow: visible;
    }
    .slider-fill {
        position: absolute;
        height: 100%;
        border-radius: 10px;
        top: 0;
    }
    .slider-thumb {
        position: absolute;
        width: 18px; height: 18px;
        background: var(--white);
        border: 3px solid var(--mcu-blue);
        border-radius: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 2px 6px rgba(21,101,192,.3);
        z-index: 2;
    }
    .slider-score { text-align: center; margin-top: .6rem; font-size: .82rem; font-weight: 700; color: var(--mcu-blue); }

    /* Jawaban Section */
    .section-card {
        background: var(--white);
        border-radius: 14px;
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(13,31,60,.05);
    }
    .section-header {
        background: var(--itb-navy);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }
    .section-header h3 { color: var(--white); font-size: .95rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: .6rem; }
    .section-toggle { color: rgba(255,255,255,.6); transition: transform .25s; }
    .section-toggle.open { transform: rotate(180deg); }
    .section-body { padding: 1.25rem 1.5rem; }

    /* Jawaban Table */
    .jawaban-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
    .jawaban-table th {
        background: var(--bg-light);
        padding: .6rem 1rem;
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--text-muted);
        font-weight: 700;
        text-align: left;
    }
    .jawaban-table td { padding: .7rem 1rem; border-bottom: 1px solid var(--border); color: var(--text-main); vertical-align: top; }
    .jawaban-table tr:last-child td { border-bottom: none; }
    .jawaban-table tr:hover td { background: #f8faff; }
    .opsi-chip {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .22rem .7rem; border-radius: 20px; font-size: .78rem; font-weight: 600;
    }
    .opsi-chip.selected-a { background: rgba(21,101,192,.12); color: var(--mcu-blue); }
    .opsi-chip.selected-b { background: rgba(46,125,50,.12); color: var(--mcu-green); }
    .kutub-tag {
        font-size: .7rem; font-weight: 700; padding: .15rem .45rem; border-radius: 4px;
        text-transform: uppercase; letter-spacing: .04em;
        background: rgba(13,31,60,.07); color: var(--itb-navy);
    }
    .q-no { color: var(--text-muted); font-size: .78rem; }
</style>
@endsection

@section('ExtraJS')
<script>
function toggleSection(id, header) {
    const body   = document.getElementById(id);
    const icon   = header.querySelector('.section-toggle');
    const isOpen = body.style.display !== 'none';
    body.style.display = isOpen ? 'none' : 'block';
    icon.classList.toggle('open', !isOpen);
}
</script>
@endsection