@extends('layouts.index')

@section('content')
<div class="hk-page">

    {{-- Header --}}
    <div class="hk-header">
        <div class="hk-header-inner">
            <div>
                <h1><i class="bi bi-bar-chart-line-fill me-2"></i>Grafik Analitik Kuesioner</h1>
                <p>Distribusi gaya belajar dan skor rata-rata seluruh mahasiswa</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" id="filterForm">
                    <div class="filter-pill">
                        <label for="id_list"><i class="bi bi-journal-bookmark-fill"></i> Kuesioner:</label>
                        <select name="id_list" id="id_list" onchange="document.getElementById('filterForm').submit()">
                            @foreach($listKuesioner as $lk)
                                <option value="{{ $lk->id_list }}" {{ $idList == $lk->id_list ? 'selected' : '' }}>
                                    {{ $lk->nama_kuesioner }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.hasil-kuesioner') : route('dosen.hasil-kuesioner') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="summary-row">
        <div class="sum-card">
            <div class="sum-val">{{ $data->count() }}</div>
            <div class="sum-lbl">Total Responden</div>
        </div>
        @foreach([
            ['Active',      $distribusi['active_reflective']['active']      ?? 0, '#1565C0'],
            ['Reflective',  $distribusi['active_reflective']['reflective']   ?? 0, '#2E7D32'],
            ['Sensing',     $distribusi['sensing_intuitive']['sensing']      ?? 0, '#6A1B9A'],
            ['Intuitive',   $distribusi['sensing_intuitive']['intuitive']    ?? 0, '#C9A84C'],
            ['Visual',      $distribusi['visual_verbal']['visual']           ?? 0, '#0277BD'],
            ['Verbal',      $distribusi['visual_verbal']['verbal']           ?? 0, '#558B2F'],
            ['Sequential',  $distribusi['sequential_global']['sequential']   ?? 0, '#D84315'],
            ['Global',      $distribusi['sequential_global']['global']       ?? 0, '#00838F'],
        ] as [$lbl, $val, $col])
        <div class="sum-card">
            <div class="sum-val" style="color:{{ $col }}">{{ $val }}</div>
            <div class="sum-lbl">{{ $lbl }}</div>
        </div>
        @endforeach
    </div>

    {{-- Chart Grid --}}
    <div class="chart-grid">

        {{-- 1: Active/Reflective Donut --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <h3><i class="bi bi-pie-chart-fill"></i> Active / Reflective</h3>
                <p>Distribusi kutub dimensi pertama</p>
            </div>
            <div class="chart-body"><canvas id="chart_ar"></canvas></div>
            <div class="legend-row">
                <div class="legend-pill"><div class="legend-dot" style="background:#1565C0"></div> Active ({{ $distribusi['active_reflective']['active'] ?? 0 }})</div>
                <div class="legend-pill"><div class="legend-dot" style="background:#2E7D32"></div> Reflective ({{ $distribusi['active_reflective']['reflective'] ?? 0 }})</div>
            </div>
        </div>

        {{-- 2: Sensing/Intuitive Donut --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <h3><i class="bi bi-pie-chart-fill"></i> Sensing / Intuitive</h3>
                <p>Distribusi kutub dimensi kedua</p>
            </div>
            <div class="chart-body"><canvas id="chart_si"></canvas></div>
            <div class="legend-row">
                <div class="legend-pill"><div class="legend-dot" style="background:#6A1B9A"></div> Sensing ({{ $distribusi['sensing_intuitive']['sensing'] ?? 0 }})</div>
                <div class="legend-pill"><div class="legend-dot" style="background:#C9A84C"></div> Intuitive ({{ $distribusi['sensing_intuitive']['intuitive'] ?? 0 }})</div>
            </div>
        </div>

        {{-- 3: Visual/Verbal Donut --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <h3><i class="bi bi-pie-chart-fill"></i> Visual / Verbal</h3>
                <p>Distribusi kutub dimensi ketiga</p>
            </div>
            <div class="chart-body"><canvas id="chart_vv"></canvas></div>
            <div class="legend-row">
                <div class="legend-pill"><div class="legend-dot" style="background:#0277BD"></div> Visual ({{ $distribusi['visual_verbal']['visual'] ?? 0 }})</div>
                <div class="legend-pill"><div class="legend-dot" style="background:#558B2F"></div> Verbal ({{ $distribusi['visual_verbal']['verbal'] ?? 0 }})</div>
            </div>
        </div>

        {{-- 4: Sequential/Global Donut --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <h3><i class="bi bi-pie-chart-fill"></i> Sequential / Global</h3>
                <p>Distribusi kutub dimensi keempat</p>
            </div>
            <div class="chart-body"><canvas id="chart_sg"></canvas></div>
            <div class="legend-row">
                <div class="legend-pill"><div class="legend-dot" style="background:#D84315"></div> Sequential ({{ $distribusi['sequential_global']['sequential'] ?? 0 }})</div>
                <div class="legend-pill"><div class="legend-dot" style="background:#00838F"></div> Global ({{ $distribusi['sequential_global']['global'] ?? 0 }})</div>
            </div>
        </div>

        {{-- 5: Bar - Rata-rata Skor Absolut --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <h3><i class="bi bi-bar-chart-fill"></i> Rata-rata Skor Absolut</h3>
                <p>Kekuatan preferensi tiap dimensi (0–11)</p>
            </div>
            <div class="chart-body"><canvas id="chart_avg"></canvas></div>
        </div>

        {{-- 6: Bar - Distribusi Kategori --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <h3><i class="bi bi-layers-fill"></i> Distribusi Kategori</h3>
                <p>Mild / Moderate / Strong per dimensi</p>
            </div>
            <div class="chart-body"><canvas id="chart_kat"></canvas></div>
        </div>

        {{-- 7: Radar --}}
        <div class="chart-card radar-card">
            <div class="chart-card-header">
                <h3><i class="bi bi-broadcast-pin"></i> Profil Radar Gaya Belajar</h3>
                <p>Visualisasi rata-rata skor semua dimensi</p>
            </div>
            <div class="radar-body"><canvas id="chart_radar"></canvas></div>
        </div>

    </div>

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

    /* Header */
    .hk-header {
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 60%, var(--mcu-blue) 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .hk-header::before { content:''; position:absolute; top:-70px; right:-70px; width:250px; height:250px; background:rgba(201,168,76,.1); border-radius:50%; }
    .hk-header h1 { color:var(--white); font-size:1.75rem; font-weight:700; margin:0 0 .25rem; position:relative; z-index:1; }
    .hk-header p  { color:rgba(255,255,255,.6); margin:0; font-size:.95rem; position:relative; z-index:1; }
    .hk-header-inner { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }

    /* Filter pill */
    .filter-pill { display:flex; align-items:center; gap:.7rem; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2); border-radius:10px; padding:.5rem 1rem; }
    .filter-pill label { color:rgba(255,255,255,.75); font-size:.8rem; font-weight:600; margin:0; white-space:nowrap; }
    .filter-pill select { background:transparent; color:var(--white); border:none; font-size:.9rem; font-weight:700; outline:none; cursor:pointer; }
    .filter-pill select option { color:var(--text-main); background:var(--white); }

    .btn-back { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); color:var(--white); border-radius:8px; padding:.5rem 1.1rem; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:.4rem; transition:background .2s; }
    .btn-back:hover { background:rgba(255,255,255,.25); color:var(--white); }

    /* Summary Row */
    .summary-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:1rem; margin-bottom:2rem; }
    .sum-card { background:var(--white); border-radius:12px; border:1px solid var(--border); padding:1.1rem 1.25rem; text-align:center; box-shadow:0 2px 8px rgba(13,31,60,.05); }
    .sum-card .sum-val { font-size:1.8rem; font-weight:800; color:var(--itb-navy); line-height:1; }
    .sum-card .sum-lbl { font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-top:.3rem; font-weight:600; }

    /* Grid Charts */
    .chart-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(340px,1fr)); gap:1.5rem; margin-bottom:2rem; }
    .chart-card { background:var(--white); border-radius:14px; border:1px solid var(--border); overflow:hidden; box-shadow:0 2px 12px rgba(13,31,60,.06); }
    .chart-card-header { background:var(--itb-navy); padding:1rem 1.5rem; }
    .chart-card-header h3 { color:var(--white); font-size:.9rem; font-weight:700; margin:0; display:flex; align-items:center; gap:.5rem; }
    .chart-card-header p { color:rgba(255,255,255,.55); font-size:.78rem; margin:.2rem 0 0; }
    .chart-body { padding:1.5rem; position:relative; height:260px; display:flex; align-items:center; justify-content:center; }

    /* Radar card */
    .radar-card { grid-column: span 2; }
    @media(max-width:900px) { .radar-card { grid-column: span 1; } }
    .radar-body { padding:1.5rem; height:340px; display:flex; align-items:center; justify-content:center; }

    /* Legend pills */
    .legend-row { display:flex; flex-wrap:wrap; gap:.5rem; padding:0 1.5rem 1.25rem; }
    .legend-pill { display:inline-flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:600; color:var(--text-main); }
    .legend-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
</style>
@endsection

@section('ExtraJS')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const BLUE   = '#1565C0';
const GREEN  = '#2E7D32';
const GOLD   = '#C9A84C';
const PURPLE = '#6A1B9A';
const TEAL   = '#00838F';
const ORANGE = '#D84315';
const LBLUE  = '#0277BD';
const LGREEN = '#558B2F';

Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";
Chart.defaults.color = '#6B7A99';

/* ── Donut helper ── */
function donut(id, labels, data, colors) {
    new Chart(document.getElementById(id), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{ data, backgroundColor: colors, borderWidth: 3, borderColor: '#fff', hoverOffset: 8 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} mahasiswa` } }
            }
        }
    });
}

donut('chart_ar',
    ['Active', 'Reflective'],
    [{{ $distribusi['active_reflective']['active'] ?? 0 }}, {{ $distribusi['active_reflective']['reflective'] ?? 0 }}],
    [BLUE, GREEN]
);
donut('chart_si',
    ['Sensing', 'Intuitive'],
    [{{ $distribusi['sensing_intuitive']['sensing'] ?? 0 }}, {{ $distribusi['sensing_intuitive']['intuitive'] ?? 0 }}],
    [PURPLE, GOLD]
);
donut('chart_vv',
    ['Visual', 'Verbal'],
    [{{ $distribusi['visual_verbal']['visual'] ?? 0 }}, {{ $distribusi['visual_verbal']['verbal'] ?? 0 }}],
    [LBLUE, LGREEN]
);
donut('chart_sg',
    ['Sequential', 'Global'],
    [{{ $distribusi['sequential_global']['sequential'] ?? 0 }}, {{ $distribusi['sequential_global']['global'] ?? 0 }}],
    [ORANGE, TEAL]
);

/* ── Bar: Avg Skor ── */
new Chart(document.getElementById('chart_avg'), {
    type: 'bar',
    data: {
        labels: ['Active/Reflective', 'Sensing/Intuitive', 'Visual/Verbal', 'Sequential/Global'],
        datasets: [{
            label: 'Rata-rata Skor',
            data: [
                {{ $avgSkor['active_reflective'] }},
                {{ $avgSkor['sensing_intuitive'] }},
                {{ $avgSkor['visual_verbal'] }},
                {{ $avgSkor['sequential_global'] }}
            ],
            backgroundColor: [BLUE, PURPLE, LBLUE, ORANGE],
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, max: 11, grid: { color: '#E8EDF5' }, ticks: { stepSize: 2 } },
            x: { grid: { display: false } }
        }
    }
});

/* ── Bar: Kategori ── */
@php
$dims = ['active_reflective', 'sensing_intuitive', 'visual_verbal', 'sequential_global'];
$dimLabels = ['Active/Reflective', 'Sensing/Intuitive', 'Visual/Verbal', 'Sequential/Global'];
$mildData     = array_map(fn($d) => $kategori[$d]['mild']     ?? 0, $dims);
$moderateData = array_map(fn($d) => $kategori[$d]['moderate'] ?? 0, $dims);
$strongData   = array_map(fn($d) => $kategori[$d]['strong']   ?? 0, $dims);
@endphp
new Chart(document.getElementById('chart_kat'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($dimLabels) !!},
        datasets: [
            { label: 'Mild',     data: {!! json_encode($mildData) !!},     backgroundColor: '#A5D6A7', borderRadius: 6 },
            { label: 'Moderate', data: {!! json_encode($moderateData) !!}, backgroundColor: GOLD,      borderRadius: 6 },
            { label: 'Strong',   data: {!! json_encode($strongData) !!},   backgroundColor: '#EF9A9A', borderRadius: 6 },
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } } },
        scales: {
            y: { beginAtZero: true, stacked: false, grid: { color: '#E8EDF5' } },
            x: { grid: { display: false } }
        }
    }
});

/* ── Radar ── */
new Chart(document.getElementById('chart_radar'), {
    type: 'radar',
    data: {
        labels: ['Active', 'Reflective', 'Sensing', 'Intuitive', 'Visual', 'Verbal', 'Sequential', 'Global'],
        datasets: [{
            label: 'Rata-rata Responden',
            data: [
                {{ $distribusi['active_reflective']['active']     ?? 0 }},
                {{ $distribusi['active_reflective']['reflective'] ?? 0 }},
                {{ $distribusi['sensing_intuitive']['sensing']    ?? 0 }},
                {{ $distribusi['sensing_intuitive']['intuitive']  ?? 0 }},
                {{ $distribusi['visual_verbal']['visual']         ?? 0 }},
                {{ $distribusi['visual_verbal']['verbal']         ?? 0 }},
                {{ $distribusi['sequential_global']['sequential'] ?? 0 }},
                {{ $distribusi['sequential_global']['global']     ?? 0 }},
            ],
            fill: true,
            backgroundColor: 'rgba(21,101,192,.12)',
            borderColor: BLUE,
            pointBackgroundColor: BLUE,
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: BLUE,
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        scales: {
            r: {
                beginAtZero: true,
                grid: { color: '#E0E8F5' },
                ticks: { backdropColor: 'transparent', stepSize: 5 },
                pointLabels: { font: { size: 12, weight: '700' }, color: '#1A2035' }
            }
        }
    }
});
</script>
@endsection