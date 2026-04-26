@extends('layouts.index')

@section('content')
<div class="hk-page">

    {{-- Header --}}
    <div class="hk-header">
        <div class="hk-header-content d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1><i class="bi bi-clipboard2-data-fill me-2"></i>Hasil Kuesioner</h1>
                <p>Pantau dan analisis hasil pengisian kuesioner gaya belajar mahasiswa</p>
            </div>
            <a href="{{ auth()->user()->role_id === 1 ? route('admin.grafik-kuesioner') : route('dosen.grafik-kuesioner') }}" class="btn-grafik">
                <i class="bi bi-bar-chart-line-fill"></i> Lihat Grafik Analitik
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon navy"><i class="bi bi-people-fill"></i></div>
            <div>
                <p class="stat-label">Total Responden</p>
                <p class="stat-value">{{ $hasil->total() }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-lightning-charge-fill"></i></div>
            <div>
                <p class="stat-label">Active</p>
                <p class="stat-value">{{ $stats['active'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gold"><i class="bi bi-eye-fill"></i></div>
            <div>
                <p class="stat-label">Visual</p>
                <p class="stat-value">{{ $stats['visual'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-sort-numeric-down"></i></div>
            <div>
                <p class="stat-label">Sequential</p>
                <p class="stat-value">{{ $stats['sequential'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ auth()->user()->role === 'admin' ? route('admin.hasil-kuesioner') : route('dosen.hasil-kuesioner') }}">
        <div class="filter-bar">
            <div class="form-group">
                <label><i class="bi bi-journal-bookmark me-1"></i>Kuesioner</label>
                <select name="id_list">
                    <option value="">Semua Kuesioner</option>
                    @foreach($listKuesioner as $lk)
                        <option value="{{ $lk->id_list }}" {{ request('id_list') == $lk->id_list ? 'selected' : '' }}>
                            {{ $lk->nama_kuesioner }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label><i class="bi bi-search me-1"></i>Cari Mahasiswa</label>
                <input type="text" name="search" placeholder="Nama atau NRP..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn-filter"><i class="bi bi-funnel-fill"></i> Filter</button>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.hasil-kuesioner') : route('dosen.hasil-kuesioner') }}" class="btn-reset">Reset</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>NRP</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kuesioner</th>
                    <th>Active/Reflective</th>
                    <th>Sensing/Intuitive</th>
                    <th>Visual/Verbal</th>
                    <th>Sequential/Global</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasil as $i => $h)
                @php
                    $dimLabels = [
                        ['hasil' => $h->hasil_active_reflective,  'skor' => $h->skor_active_reflective,  'kat' => $h->kategori_active_reflective],
                        ['hasil' => $h->hasil_sensing_intuitive,  'skor' => $h->skor_sensing_intuitive,  'kat' => $h->kategori_sensing_intuitive],
                        ['hasil' => $h->hasil_visual_verbal,      'skor' => $h->skor_visual_verbal,      'kat' => $h->kategori_visual_verbal],
                        ['hasil' => $h->hasil_sequential_global,  'skor' => $h->skor_sequential_global,  'kat' => $h->kategori_sequential_global],
                    ];
                @endphp
                <tr>
                    <td>{{ $hasil->firstItem() + $i }}</td>
                    <td class="td-nrp">{{ $h->mahasiswa_nrp }}</td>
                    <td class="td-name">{{ optional(optional($h->mahasiswa)->user)->nama ?? '-' }}</td>
                    <td class="td-kuesioner">{{ optional($h->listKuesioner)->nama_kuesioner ?? '-' }}</td>
                    @foreach($dimLabels as $dim)
                    <td>
                        <span class="dim-badge {{ $dim['hasil'] }}">{{ ucfirst($dim['hasil']) }}</span>
                        <span class="text-muted ms-1" style="font-size:.75rem">({{ $dim['skor'] > 0 ? '+' : '' }}{{ $dim['skor'] }})</span>
                        <br>
                        <span class="kat-badge {{ strtolower($dim['kat'] ?? '') }}">{{ ucfirst($dim['kat'] ?? '') }}</span>
                    </td>
                    @endforeach
                    <td>
                        <a href="{{ route('admin.detail-hasil-kuesioner', $h->id_hasil) }}" class="btn-detail">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>Belum ada data hasil kuesioner.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($hasil->hasPages())
        <div class="pagi-wrap">
            {{ $hasil->appends(request()->query())->links() }}
        </div>
        @endif
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

    /* ── Page Header ── */
    .hk-header {
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 60%, var(--mcu-blue) 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .hk-header::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: rgba(201,168,76,.12);
        border-radius: 50%;
    }
    .hk-header::after {
        content: '';
        position: absolute;
        bottom: -40px; left: 30%;
        width: 160px; height: 160px;
        background: rgba(21,101,192,.18);
        border-radius: 50%;
    }
    .hk-header-content { position: relative; z-index: 1; }
    .hk-header h1 { color: var(--white); font-size: 1.75rem; font-weight: 700; margin: 0 0 .25rem; }
    .hk-header p  { color: rgba(255,255,255,.65); margin: 0; font-size: .95rem; }
    .btn-grafik {
        background: var(--mcu-gold);
        color: var(--itb-navy);
        border: none;
        border-radius: 8px;
        padding: .6rem 1.4rem;
        font-weight: 700;
        font-size: .9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: opacity .2s, transform .15s;
    }
    .btn-grafik:hover { opacity: .9; transform: translateY(-1px); color: var(--itb-navy); }

    /* ── Stat Cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: var(--white);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 8px rgba(13,31,60,.05);
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 6px 20px rgba(13,31,60,.1); transform: translateY(-2px); }
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .stat-icon.navy  { background: rgba(13,31,60,.08); color: var(--itb-navy); }
    .stat-icon.blue  { background: rgba(21,101,192,.1); color: var(--mcu-blue); }
    .stat-icon.gold  { background: rgba(201,168,76,.12); color: #a07d28; }
    .stat-icon.green { background: rgba(46,125,50,.1); color: var(--mcu-green); }
    .stat-label { font-size: .78rem; color: var(--text-muted); margin: 0; text-transform: uppercase; letter-spacing: .05em; }
    .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--text-main); margin: .1rem 0 0; line-height: 1; }

    /* ── Filter Bar ── */
    .filter-bar {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    .filter-bar .form-group { display: flex; flex-direction: column; gap: .35rem; flex: 1; min-width: 180px; }
    .filter-bar label { font-size: .8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .04em; }
    .filter-bar select,
    .filter-bar input[type="text"] {
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: .5rem .85rem;
        font-size: .9rem;
        color: var(--text-main);
        background: var(--bg-light);
        outline: none;
        transition: border-color .2s;
    }
    .filter-bar select:focus,
    .filter-bar input:focus { border-color: var(--mcu-blue); }
    .btn-filter {
        background: var(--mcu-blue);
        color: var(--white);
        border: none;
        border-radius: 8px;
        padding: .55rem 1.25rem;
        font-weight: 600;
        cursor: pointer;
        align-self: flex-end;
        transition: background .2s;
        display: inline-flex; align-items: center; gap: .4rem;
    }
    .btn-filter:hover { background: var(--itb-navy-mid); }
    .btn-reset {
        background: transparent;
        color: var(--text-muted);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: .55rem 1.1rem;
        font-weight: 600;
        cursor: pointer;
        align-self: flex-end;
        text-decoration: none;
        font-size: .88rem;
        transition: border-color .2s, color .2s;
    }
    .btn-reset:hover { border-color: var(--mcu-blue); color: var(--mcu-blue); }

    /* ── Table ── */
    .table-card {
        background: var(--white);
        border-radius: 14px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(13,31,60,.06);
    }
    .table-card table { width: 100%; border-collapse: collapse; }
    .table-card thead th {
        background: var(--itb-navy);
        color: rgba(255,255,255,.85);
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: .9rem 1.1rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .table-card tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #f0f4ff; }
    .table-card tbody td { padding: .85rem 1.1rem; font-size: .88rem; color: var(--text-main); vertical-align: middle; }
    .td-nrp { font-family: 'Courier New', monospace; font-size: .82rem; color: var(--text-muted); }
    .td-name { font-weight: 600; }
    .td-kuesioner { font-size: .82rem; color: var(--text-muted); }

    /* Badge dimensi */
    .dim-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .75rem;
        font-weight: 700;
        padding: .22rem .65rem;
        border-radius: 20px;
        text-transform: capitalize;
        letter-spacing: .02em;
    }
    .dim-badge.active, .dim-badge.sensing, .dim-badge.visual, .dim-badge.sequential {
        background: rgba(21,101,192,.12); color: var(--mcu-blue);
    }
    .dim-badge.reflective, .dim-badge.intuitive, .dim-badge.verbal, .dim-badge.global {
        background: rgba(46,125,50,.12); color: var(--mcu-green);
    }

    /* Kategori */
    .kat-badge {
        font-size: .72rem;
        font-weight: 600;
        padding: .18rem .55rem;
        border-radius: 4px;
        text-transform: capitalize;
    }
    .kat-badge.mild     { background: #e8f5e9; color: #2e7d32; }
    .kat-badge.moderate { background: #fff8e1; color: #f57f17; }
    .kat-badge.strong   { background: #fce4ec; color: #c62828; }

    .btn-detail {
        background: var(--itb-navy);
        color: var(--white);
        border: none;
        border-radius: 7px;
        padding: .38rem .9rem;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: background .2s;
    }
    .btn-detail:hover { background: var(--mcu-blue); color: var(--white); }

    /* Pagination */
    .pagi-wrap { padding: 1rem 1.5rem; display: flex; justify-content: flex-end; border-top: 1px solid var(--border); }
    .pagi-wrap .pagination { margin: 0; }
    .page-link { color: var(--mcu-blue); border-color: var(--border); }
    .page-item.active .page-link { background: var(--mcu-blue); border-color: var(--mcu-blue); }

    /* Empty state */
    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }
    .empty-state i { font-size: 3rem; opacity: .3; margin-bottom: 1rem; display: block; }
</style>
@endsection

@section('ExtraJS')
@endsection