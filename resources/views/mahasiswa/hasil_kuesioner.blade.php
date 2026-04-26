@extends('layouts.index')

@section('content')
<div class="hasil-hero">
    <div class="container">
        <div class="hero-badge">
            ✦ Felder–Silverman Learning Style Model
        </div>
        <h1>Hasil Tes Gaya Belajarmu</h1>
        <p>Berikut adalah profil gaya belajar kamu berdasarkan jawaban yang telah diberikan.</p>
    </div>
</div>

<div class="main-wrap">

    @php
        // $dimensiMap sudah dikirim dari controller via $hasil->getRingkasanDimensi()
        // Struktur tiap item: label, skor (net), hasil (kutub pemenang), kategori, left, right

        $dimColors = [
            'active_reflective' => 'navy-accent',
            'sensing_intuitive' => 'blue-accent',
            'visual_verbal'     => 'green-accent',
            'sequential_global' => 'gold-accent',
        ];

        $dimDesc = [
            'active'     => 'Kamu cenderung belajar melalui praktik langsung, diskusi, dan eksperimen. Bekerja dalam kelompok sangat membantumu.',
            'reflective' => 'Kamu lebih nyaman merefleksikan informasi secara mandiri sebelum bertindak. Kamu suka memikirkan sesuatu terlebih dahulu.',
            'sensing'    => 'Kamu menyukai fakta konkret, detail, dan prosedur yang jelas. Kamu cenderung sabar dan teliti.',
            'intuitive'  => 'Kamu lebih suka menemukan pola, hubungan abstrak, dan inovasi. Kamu cepat memahami konsep baru.',
            'visual'     => 'Kamu lebih mudah menyerap informasi melalui gambar, diagram, grafik, dan demonstrasi visual.',
            'verbal'     => 'Kamu belajar lebih baik melalui kata-kata tertulis maupun lisan, seperti membaca dan mendengarkan penjelasan.',
            'sequential' => 'Kamu memahami materi secara bertahap dan linear. Kamu unggul dalam mengikuti langkah-langkah logis.',
            'global'     => 'Kamu lebih suka melihat gambaran besar terlebih dahulu sebelum masuk ke detail. Kamu sering mendapat pemahaman mendalam secara tiba-tiba.',
        ];

        $tipsDimensi = [
            'active'     => 'Coba berdiskusi konsep dengan teman, buat grup belajar, atau ajarkan materi kepada orang lain.',
            'reflective' => 'Sisihkan waktu review mandiri setelah kuliah. Tulis ringkasan atau jurnal belajar harian.',
            'sensing'    => 'Cari contoh konkret dari setiap konsep. Kerjakan soal latihan dan studi kasus nyata.',
            'intuitive'  => 'Eksplorasi ide di luar silabus. Tantang dirimu dengan soal open-ended dan proyek kreatif.',
            'visual'     => 'Gunakan mind map, flowchart, dan video pembelajaran. Ubah catatan teks menjadi diagram.',
            'verbal'     => 'Bacalah materi dari berbagai sumber. Rekam dirimu menjelaskan materi dan putar ulang.',
            'sequential' => 'Susun jadwal belajar terstruktur. Pastikan kamu memahami setiap langkah sebelum melanjutkan.',
            'global'     => 'Baca ringkasan atau overview terlebih dahulu. Hubungkan topik baru dengan pengetahuan yang sudah ada.',
        ];
    @endphp

    {{-- ─── CHART CARD ─── --}}
    <div class="chart-card">
        <h2>Profil Gaya Belajar</h2>
        <p class="subtitle">Skala menunjukkan kecenderungan kamu pada setiap dimensi Felder-Silverman</p>

        @foreach($dimensiMap as $dim => $d)
        @php
            $net       = $d['skor'];
            $absNet    = abs($net);
            $total     = $hasil->{"skor_{$dim}"} !== null
                            ? ($net >= 0
                                ? ($hasil->{"skor_{$dim}"} + 0) // placeholder, hitung dari jawaban
                                : 0)
                            : 0;
            // Hitung left/right count dari skor net:
            // Kita tidak simpan left/right count terpisah, tapi bisa ditampilkan sebagai |skor|
            $pct       = $absNet > 0 ? min($absNet / 11 * 50, 50) : 0;
            $markerPct = 50 + ($net / 11) * 50;
            $markerPct = max(5, min(95, $markerPct));
            $isLeft    = $net >= 0;
        @endphp
        <div class="fs-dim">
            <div class="fs-dim-title">{{ $d['label'] }}</div>
            <div class="scale-labels">
                <span class="lbl-left">{{ ucfirst($d['left']) }}</span>
                <span class="lbl-right">{{ ucfirst($d['right']) }}</span>
            </div>
            <div class="scale-track">
                <div class="scale-center"></div>
                @if($isLeft)
                <div class="scale-fill left-fill" style="width:{{ $pct }}%"></div>
                @else
                <div class="scale-fill right-fill" style="width:{{ $pct }}%"></div>
                @endif
                <div class="scale-marker"
                     style="left:{{ $markerPct }}%;
                            border-color:{{ $isLeft ? 'var(--itb-navy-mid)' : 'var(--mcu-blue)' }}">
                </div>
            </div>
            <div class="scale-score-label">
                <span class="score-pill pill-navy">{{ ucfirst($d['left']) }}</span>
                <span class="interpretation-label">
                    <strong>{{ ucfirst($d['hasil']) }}</strong>
                    ({{ $d['kategori'] }}, skor {{ $absNet }})
                </span>
                <span class="score-pill pill-blue">{{ ucfirst($d['right']) }}</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ─── DIMENSION RESULT CARDS ─── --}}
    <div class="dim-cards-grid">
        @foreach($dimensiMap as $dim => $d)
        @php
            $colorClass  = $dimColors[$dim] ?? 'navy-accent';
            $desc        = $dimDesc[$d['hasil']] ?? '';
            $badge_bg    = $d['skor'] >= 0 ? 'rgba(26,53,96,.1)' : 'rgba(21,101,192,.1)';
            $badge_color = $d['skor'] >= 0 ? 'var(--itb-navy-mid)' : 'var(--mcu-blue)';
        @endphp
        <div class="dim-result-card {{ $colorClass }}" style="animation-delay:{{ $loop->index * 0.12 }}s">
            <div class="dim-label">{{ $d['label'] }}</div>
            <div class="dim-result-title">{{ ucfirst($d['hasil']) }}</div>
            <div class="dim-score-badge" style="background:{{ $badge_bg }}; color:{{ $badge_color }}">
                {{ $d['kategori'] }} · Skor {{ abs($d['skor']) }}
            </div>
            <div class="dim-desc">{{ $desc }}</div>
        </div>
        @endforeach
    </div>

    {{-- ─── RADAR CHART ─── --}}
    <div class="chart-card" style="margin-bottom:28px;">
        <h2>Visualisasi Grafik</h2>
        <p class="subtitle">Distribusi kecenderungan gaya belajar kamu pada empat dimensi</p>
        <canvas id="fsChart" height="320"></canvas>
    </div>

    {{-- ─── TIPS CARD ─── --}}
    <div class="tips-card">
        <h3>💡 Tips Belajar Untukmu</h3>
        <div class="tips-grid">
            @foreach($dimensiMap as $dim => $d)
            <div class="tip-item">
                <div class="tip-dim">{{ $d['label'] }} · {{ ucfirst($d['hasil']) }}</div>
                <p>{{ $tipsDimensi[$d['hasil']] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>

</div>

@php
    $chartDimData = [];
    foreach ($dimensiMap as $dim => $d) {
        $chartDimData[] = [
            'leftPole'  => $d['left'],
            'rightPole' => $d['right'],
            'left'      => $d['skor'] >= 0 ? abs($d['skor']) : 0,
            'right'     => $d['skor'] < 0  ? abs($d['skor']) : 0,
            'net'       => $d['skor'],
            'winner'    => $d['hasil'],
        ];
    }
    $chartDims = array_values(array_map(fn($d) => $d['label'], $dimensiMap));
@endphp

@endsection

@section('ExtraCSS')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap');

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

    body { background: var(--bg-light); }

    /* ── HERO ── */
    .hasil-hero {
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 45%, var(--mcu-blue) 100%);
        padding: 48px 0 100px;
        position: relative; overflow: hidden;
        text-align: center;
    }
    .hasil-hero::before {
        content:''; position:absolute;
        top:-100px; right:-100px;
        width:380px; height:380px; border-radius:50%;
        background:rgba(201,168,76,.1);
    }
    .hasil-hero::after {
        content:''; position:absolute;
        bottom:-120px; left:-60px;
        width:280px; height:280px; border-radius:50%;
        background:rgba(255,255,255,.04);
    }
    .hero-badge {
        display:inline-flex; align-items:center; gap:7px;
        background:rgba(201,168,76,.18);
        border:1px solid rgba(201,168,76,.4);
        color:var(--mcu-gold);
        font-family:'DM Sans',sans-serif;
        font-size:11px; font-weight:600;
        letter-spacing:1.4px; text-transform:uppercase;
        padding:6px 16px; border-radius:20px; margin-bottom:18px;
    }
    .hasil-hero h1 {
        font-family:'Playfair Display',serif;
        font-size:2.5rem; color:#fff; margin-bottom:10px;
    }
    .hasil-hero p {
        font-family:'DM Sans',sans-serif;
        color:rgba(255,255,255,.65); font-size:.95rem;
    }

    /* ── MAIN WRAP ── */
    .main-wrap {
        max-width:960px; margin:-60px auto 80px;
        padding:0 24px; position:relative; z-index:10;
    }
    /* Ensure header/nav stays above the main content (prevent chart-card overlapping navbar) */
    .app-header { z-index: 9999; }
    .main-wrap { z-index: 1; }

    /* ── DIMENSION GRID CHART CARD ── */
    .chart-card {
        background:var(--white);
        border:1px solid var(--border);
        border-radius:20px;
        box-shadow:0 8px 40px rgba(13,31,60,.1);
        padding:40px 40px 32px;
        margin-bottom:28px;
    }
    .chart-card h2 {
        font-family:'Playfair Display',serif;
        font-size:1.5rem; color:var(--text-main);
        margin-bottom:6px;
    }
    .chart-card .subtitle {
        font-family:'DM Sans',sans-serif;
        font-size:.87rem; color:var(--text-muted);
        margin-bottom:32px;
    }

    /* ── FS SCALE BAR ── */
    .fs-dim { margin-bottom:32px; }
    .fs-dim-title {
        font-family:'DM Sans',sans-serif;
        font-size:.78rem; font-weight:600;
        letter-spacing:1.2px; text-transform:uppercase;
        color:var(--text-muted); margin-bottom:10px;
    }
    .scale-container {
        position:relative;
    }
    .scale-labels {
        display:flex; justify-content:space-between;
        font-family:'DM Sans',sans-serif;
        font-size:.8rem; font-weight:600;
        margin-bottom:6px;
    }
    .scale-labels .lbl-left { color:var(--itb-navy-mid); }
    .scale-labels .lbl-right { color:var(--mcu-blue); }

    .scale-track {
        position:relative;
        height:36px;
        background:linear-gradient(90deg, rgba(26,53,96,.08), rgba(21,101,192,.08));
        border-radius:18px;
        border:1px solid var(--border);
        overflow:hidden;
        display:flex; align-items:center;
    }
    .scale-center {
        position:absolute; left:50%; top:0; bottom:0;
        width:2px; background:var(--border);
        transform:translateX(-50%);
        z-index:2;
    }
    .scale-fill {
        position:absolute;
        height:100%; border-radius:18px;
        transition:width .8s cubic-bezier(.4,0,.2,1), left .8s;
    }
    .scale-fill.left-fill {
        background:linear-gradient(90deg, var(--itb-navy-mid), rgba(26,53,96,.6));
        right:50%; left:auto;
    }
    .scale-fill.right-fill {
        background:linear-gradient(90deg, rgba(21,101,192,.6), var(--mcu-blue));
        left:50%; right:auto;
    }
    .scale-marker {
        position:absolute;
        top:50%; transform:translate(-50%,-50%);
        width:20px; height:20px; border-radius:50%;
        background:var(--white);
        border:3px solid;
        z-index:3;
        box-shadow:0 2px 8px rgba(0,0,0,.2);
    }
    .scale-score-label {
        display:flex; justify-content:space-between;
        margin-top:7px;
    }
    .scale-score-label span {
        font-family:'DM Sans',sans-serif;
        font-size:.8rem; font-weight:600;
    }
    .score-pill {
        display:inline-flex; align-items:center; gap:4px;
        padding:2px 10px; border-radius:12px;
        font-size:.75rem; font-weight:700;
        font-family:'DM Sans',sans-serif;
    }
    .pill-navy { background:rgba(26,53,96,.12); color:var(--itb-navy-mid); }
    .pill-blue { background:rgba(21,101,192,.12); color:var(--mcu-blue); }

    .interpretation-label {
        font-family:'DM Sans',sans-serif;
        font-size:.78rem; color:var(--text-muted);
        text-align:center; margin-top:4px;
    }
    .interpretation-label strong { color:var(--text-main); }

    /* ── DIMENSION RESULT CARDS ── */
    .dim-cards-grid {
        display:grid; grid-template-columns:1fr 1fr;
        gap:20px; margin-bottom:28px;
    }
    @media(max-width:640px) { .dim-cards-grid { grid-template-columns:1fr; } }

    .dim-result-card {
        background:var(--white);
        border:1px solid var(--border);
        border-radius:16px;
        box-shadow:0 4px 20px rgba(13,31,60,.07);
        padding:24px;
        position:relative; overflow:hidden;
        opacity:0; transform:translateY(16px);
        animation:fadeUp .5s forwards;
    }
    @keyframes fadeUp { to { opacity:1; transform:translateY(0); } }

    .dim-result-card::before {
        content:''; position:absolute;
        top:0; left:0; right:0; height:4px;
    }
    .dim-result-card.navy-accent::before { background:linear-gradient(90deg,var(--itb-navy),var(--itb-navy-mid)); }
    .dim-result-card.blue-accent::before { background:linear-gradient(90deg,var(--mcu-blue),#42A5F5); }
    .dim-result-card.green-accent::before { background:linear-gradient(90deg,var(--mcu-green),#43A047); }
    .dim-result-card.gold-accent::before { background:linear-gradient(90deg,var(--mcu-gold),#E8C46A); }

    .dim-label {
        font-family:'DM Sans',sans-serif;
        font-size:.73rem; font-weight:600; letter-spacing:1px;
        text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;
    }
    .dim-result-title {
        font-family:'Playfair Display',serif;
        font-size:1.4rem; color:var(--text-main); margin-bottom:4px;
    }
    .dim-score-badge {
        display:inline-block;
        padding:4px 12px; border-radius:20px;
        font-family:'DM Sans',sans-serif;
        font-size:.78rem; font-weight:700;
        margin-bottom:12px;
    }
    .dim-desc {
        font-family:'DM Sans',sans-serif;
        font-size:.85rem; line-height:1.6;
        color:var(--text-muted);
    }

    /* ── TIPS CARD ── */
    .tips-card {
        background:linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 60%, var(--mcu-blue) 100%);
        border-radius:20px;
        padding:36px 40px;
        color:#fff;
        position:relative; overflow:hidden;
    }
    .tips-card::before {
        content:''; position:absolute;
        top:-60px; right:-60px;
        width:200px; height:200px; border-radius:50%;
        background:rgba(201,168,76,.12);
    }
    .tips-card h3 {
        font-family:'Playfair Display',serif;
        font-size:1.3rem; margin-bottom:18px;
        color:#fff;
    }
    .tips-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    @media(max-width:640px) { .tips-grid { grid-template-columns:1fr; } }
    .tip-item {
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.12);
        border-radius:12px; padding:16px;
    }
    .tip-item .tip-dim {
        font-family:'DM Sans',sans-serif;
        font-size:.72rem; font-weight:600;
        letter-spacing:1px; text-transform:uppercase;
        color:var(--mcu-gold); margin-bottom:6px;
    }
    .tip-item p {
        font-family:'DM Sans',sans-serif;
        font-size:.83rem; color:rgba(255,255,255,.75);
        line-height:1.55; margin:0;
    }
</style>
@endsection

@section('ExtraJS')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('fsChart').getContext('2d');

    const dimData = @json($chartDimData);
    const dims    = @json($chartDims);
    const leftLabels  = dimData.map(d => d.leftPole.charAt(0).toUpperCase() + d.leftPole.slice(1));
    const rightLabels = dimData.map(d => d.rightPole.charAt(0).toUpperCase() + d.rightPole.slice(1));
    const leftVals    = dimData.map(d => d.left);
    const rightVals   = dimData.map(d => d.right);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dims,
            datasets: [
                {
                    label: 'Kutub Kiri',
                    data: leftVals,
                    backgroundColor: 'rgba(26,53,96,.75)',
                    borderColor: '#1A3560',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Kutub Kanan',
                    data: rightVals,
                    backgroundColor: 'rgba(21,101,192,.7)',
                    borderColor: '#1565C0',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        font: { family: 'DM Sans', size: 13 },
                        color: '#1A2035'
                    }
                },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            const i = context.dataIndex;
                            const ds = context.datasetIndex;
                            const pole = ds === 0 ? leftLabels[i] : rightLabels[i];
                            return '→ ' + pole;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(214,223,240,.5)' },
                    ticks: { font: { family: 'DM Sans', size: 12 }, color: '#1A2035' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(214,223,240,.5)' },
                    ticks: { font: { family: 'DM Sans', size: 12 }, color: '#6B7A99', stepSize: 1 }
                }
            }
        }
    });
});
</script>
@endsection