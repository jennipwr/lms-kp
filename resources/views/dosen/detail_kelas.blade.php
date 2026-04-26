@extends('layouts.index')

@section('content')

<div class="dk-wrapper">

    {{-- Breadcrumb --}}
    <div class="dk-breadcrumb">
        <a href="{{ route('dosen.lihat-kelas') }}" class="dk-breadcrumb-link">
            <i class="bi bi-collection-fill"></i> Kelas Saya
        </a>
        <i class="bi bi-chevron-right dk-breadcrumb-sep"></i>
        <span class="dk-breadcrumb-current">{{ $kelas->nama_kelas }}</span>
    </div>

    {{-- Hero Kelas --}}
    <div class="dk-hero">
        <div class="dk-hero-bg"></div>
        <div class="dk-hero-content">
            <span class="dk-hero-badge">{{ strtoupper($kelas->kelas_label ?? 'KELAS') }}</span>
            <h1 class="dk-hero-title" style="color:white">{{ $kelas->nama_kelas }}</h1>
            <div class="dk-hero-meta">
                <span><i class="bi bi-hash"></i> {{ $kelas->kode_kelas }}</span>
                <span class="dk-hero-sep">•</span>
                <span>
                    <i class="bi bi-key-fill"></i>
                    Token: <strong>{{ $kelas->join_token }}</strong>
                </span>
                <span class="dk-hero-sep">•</span>
                <span><i class="bi bi-people-fill"></i> {{ $kelas->kelasMahasiswa->count() }} Mahasiswa</span>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="dk-tabs">
        <button class="dk-tab active" onclick="switchTab('konten', this)">
            <i class="bi bi-journals"></i> Konten Kelas
        </button>
        <button class="dk-tab" onclick="switchTab('anggota', this)">
            <i class="bi bi-people-fill"></i> Anggota Kelas
        </button>
    </div>

    {{-- Panel: Konten Kelas --}}
    <div id="tab-konten" class="dk-panel dk-panel-active">

        {{-- Toolbar upload (dosen only) --}}
        <div class="dk-konten-toolbar">
            <span class="dk-konten-info">
                <i class="bi bi-info-circle"></i>
                Kelola materi dan konten untuk kelas ini.
            </span>
            <button class="dk-btn-upload" {{-- route belum tersedia --}} disabled title="Fitur segera hadir">
                <i class="bi bi-plus-lg"></i>
                <span>Upload Materi</span>
            </button>
        </div>

        <div class="dk-empty-konten">
            <div class="dk-empty-icon">
                <i class="bi bi-journals"></i>
            </div>
            <h3>Belum Ada Konten Tersedia</h3>
            <p>Klik tombol <strong>Upload Materi</strong> di atas untuk mulai menambahkan konten ke kelas ini.</p>
        </div>
    </div>

    {{-- Panel: Anggota Kelas --}}
    <div id="tab-anggota" class="dk-panel">

        <div class="dk-section-label">
            <i class="bi bi-person-badge-fill"></i> Dosen Pengampu
        </div>
        <div class="dk-member-list">
            <div class="dk-member-card dk-member-dosen">
                <div class="dk-member-avatar dk-avatar-dosen">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="dk-member-info">
                    <span class="dk-member-name">
                        {{ $dosen->user->nama ?? 'Nama tidak tersedia' }}
                        <span class="dk-self-tag">Anda</span>
                    </span>
                    <span class="dk-member-role">NIK: {{ $dosen->nik }}</span>
                </div>
                <span class="dk-member-badge-dosen">Dosen</span>
            </div>
        </div>

        <div class="dk-section-label" style="margin-top: 1.75rem;">
            <i class="bi bi-people-fill"></i> Mahasiswa Terdaftar
            <span class="dk-count-badge">{{ $kelas->kelasMahasiswa->count() }}</span>
        </div>

        @if($kelas->kelasMahasiswa->isEmpty())
            <div class="dk-empty-member">
                <i class="bi bi-person-x"></i>
                <span>Belum ada mahasiswa yang bergabung ke kelas ini.</span>
            </div>
        @else
            <div class="dk-member-list">
                @foreach($kelas->kelasMahasiswa as $index => $km)
                    <div class="dk-member-card">
                        <div class="dk-member-avatar">
                            {{ strtoupper(substr($km->mahasiswa->user->nama ?? 'M', 0, 1)) }}
                        </div>
                        <div class="dk-member-info">
                            <span class="dk-member-name">
                                {{ $km->mahasiswa->user->nama ?? 'Nama tidak tersedia' }}
                            </span>
                            <span class="dk-member-role">NRP: {{ $km->mahasiswa_nrp }}</span>
                        </div>
                        <span class="dk-member-number">{{ $index + 1 }}</span>
                    </div>
                @endforeach
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

    .dk-wrapper {
        padding: 1.75rem 2.5rem;
        min-height: 80vh;
    }

    /* ── Breadcrumb ── */
    .dk-breadcrumb {
        display: flex; align-items: center; gap: .5rem;
        font-size: .85rem; margin-bottom: 1.5rem;
    }
    .dk-breadcrumb-link {
        color: var(--mcu-blue); text-decoration: none;
        display: flex; align-items: center; gap: .35rem;
        font-weight: 500; transition: opacity .15s;
    }
    .dk-breadcrumb-link:hover { opacity: .75; }
    .dk-breadcrumb-sep { color: var(--text-muted); font-size: .75rem; }
    .dk-breadcrumb-current { color: var(--text-muted); }

    /* ── Hero ── */
    .dk-hero {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 1.75rem;
        padding: 2.5rem 2rem;
        color: white;
    }
    .dk-hero-bg {
        position: absolute; inset: 0;
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 45%, var(--mcu-blue) 100%);
        z-index: 0;
    }
    .dk-hero-bg::after {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .dk-hero-content { position: relative; z-index: 1; }
    .dk-hero-badge {
        display: inline-block;
        padding: .25rem .85rem;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 20px;
        font-size: .72rem; font-weight: 700; letter-spacing: .06em;
        margin-bottom: .85rem;
    }
    .dk-hero-title {
        font-size: 1.85rem; font-weight: 800;
        margin-bottom: .75rem; line-height: 1.2;
    }
    .dk-hero-meta {
        display: flex; align-items: center; flex-wrap: wrap; gap: .5rem;
        font-size: .85rem; color: rgba(255,255,255,.85);
    }
    .dk-hero-meta i { margin-right: .25rem; }
    .dk-hero-meta strong { color: white; letter-spacing: .06em; }
    .dk-hero-sep { opacity: .4; }

    /* ── Tabs ── */
    .dk-tabs {
        display: flex; gap: .5rem;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid var(--border);
    }
    .dk-tab {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .65rem 1.25rem;
        background: transparent;
        border: none; border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        font-size: .88rem; font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: color .2s, border-color .2s;
        border-radius: 8px 8px 0 0;
    }
    .dk-tab:hover { color: var(--mcu-blue); background: rgba(21,101,192,.05); }
    .dk-tab.active {
        color: var(--mcu-blue);
        border-bottom-color: var(--mcu-blue);
        background: rgba(21,101,192,.06);
    }

    /* ── Panels ── */
    .dk-panel { display: none; }
    .dk-panel-active { display: block; }

    /* ── Konten Toolbar ── */
    .dk-konten-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem;
        padding: .85rem 1.25rem;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 1.25rem;
    }
    .dk-konten-info {
        display: flex; align-items: center; gap: .45rem;
        font-size: .85rem; color: var(--text-muted);
    }

    /* ── Tombol Upload Materi ── */
    .dk-btn-upload {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .6rem 1.3rem;
        background: linear-gradient(135deg, var(--mcu-blue), var(--itb-navy-mid));
        color: white;
        border: none; border-radius: 9px;
        font-weight: 600; font-size: .88rem;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(21,101,192,.35);
        transition: transform .15s, box-shadow .15s, opacity .15s;
    }
    .dk-btn-upload:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(21,101,192,.5);
    }
    .dk-btn-upload:disabled {
        opacity: .55;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* ── Empty Konten ── */
    .dk-empty-konten {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border: 2px dashed var(--border);
        border-radius: 16px;
        color: var(--text-muted);
    }
    .dk-empty-icon { font-size: 3.5rem; color: var(--border); margin-bottom: .85rem; }
    .dk-empty-konten h3 { color: var(--text-main); margin-bottom: .5rem; font-size: 1.15rem; }
    .dk-empty-konten p { max-width: 360px; margin: 0 auto; font-size: .88rem; line-height: 1.6; }

    /* ── Section Label ── */
    .dk-section-label {
        display: flex; align-items: center; gap: .5rem;
        font-size: .82rem; font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .07em;
        margin-bottom: .85rem;
    }
    .dk-count-badge {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 22px; height: 22px; padding: 0 .45rem;
        background: var(--mcu-blue); color: white;
        border-radius: 20px; font-size: .72rem; font-weight: 700;
    }

    /* ── Member List ── */
    .dk-member-list { display: flex; flex-direction: column; gap: .6rem; }
    .dk-member-card {
        display: flex; align-items: center; gap: 1rem;
        padding: .85rem 1.25rem;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        transition: box-shadow .15s, border-color .15s;
    }
    .dk-member-card:hover {
        box-shadow: 0 4px 14px rgba(21,101,192,.1);
        border-color: rgba(21,101,192,.3);
    }
    .dk-member-dosen {
        background: linear-gradient(135deg, rgba(13,31,60,.03), rgba(21,101,192,.04));
        border-color: rgba(21,101,192,.2);
    }

    .dk-member-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--itb-navy-mid), var(--mcu-blue));
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1rem;
        flex-shrink: 0;
    }
    .dk-avatar-dosen {
        background: linear-gradient(135deg, var(--itb-navy), var(--itb-navy-mid));
        font-size: 1.1rem;
    }
    .dk-member-info { flex: 1; display: flex; flex-direction: column; gap: .15rem; }
    .dk-member-name {
        font-weight: 600; font-size: .92rem; color: var(--text-main);
        display: flex; align-items: center; gap: .5rem;
    }
    .dk-member-role { font-size: .78rem; color: var(--text-muted); }

    .dk-self-tag {
        display: inline-block;
        padding: .1rem .5rem;
        background: rgba(201,168,76,.15);
        color: #7a5c00;
        border-radius: 20px;
        font-size: .68rem; font-weight: 700;
    }
    .dk-member-badge-dosen {
        padding: .25rem .75rem;
        border-radius: 20px;
        font-size: .72rem; font-weight: 700;
        background: rgba(13,31,60,.08);
        color: var(--itb-navy);
    }
    .dk-member-number {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: var(--bg-light);
        border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700;
        color: var(--text-muted);
        flex-shrink: 0;
    }

    .dk-empty-member {
        display: flex; align-items: center; gap: .6rem;
        padding: 1.5rem;
        background: var(--bg-light);
        border-radius: 10px;
        color: var(--text-muted); font-size: .88rem;
    }
    .dk-empty-member i { font-size: 1.25rem; }

    @media (max-width: 576px) {
        .dk-wrapper { padding: 1.25rem 1rem; }
        .dk-hero-title { font-size: 1.4rem; }
        .dk-hero-meta { flex-direction: column; align-items: flex-start; }
        .dk-konten-toolbar { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection

@section('ExtraJS')
<script>
    function switchTab(tabName, el) {
        document.querySelectorAll('.dk-panel').forEach(p => p.classList.remove('dk-panel-active'));
        document.querySelectorAll('.dk-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('dk-panel-active');
        el.classList.add('active');
    }
</script>
@endsection