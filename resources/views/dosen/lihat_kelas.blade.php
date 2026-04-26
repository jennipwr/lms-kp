@extends('layouts.index')

@section('content')

<div class="lk-wrapper">

    {{-- Header --}}
    <div class="lk-header">
        <div class="lk-header-left">
            <div class="lk-header-icon">
                <i class="bi bi-collection-fill"></i>
            </div>
            <div>
                <h1 class="lk-title">Kelas Saya</h1>
                <p class="lk-subtitle">Daftar kelas yang Anda ampu</p>
            </div>
        </div>
        <div class="lk-header-info">
            <i class="bi bi-mortarboard-fill"></i>
            <span>{{ $dosen->user->nama ?? 'Dosen' }}</span>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="lk-alert lk-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="lk-alert lk-alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Grid Kelas --}}
    @if($kelasList->isEmpty())
        <div class="lk-empty">
            <div class="lk-empty-icon">
                <i class="bi bi-journal-x"></i>
            </div>
            <h3>Belum Ada Kelas</h3>
            <p>Anda belum mengampu kelas manapun. Hubungi administrator untuk membuat kelas baru.</p>
        </div>
    @else
        <div class="lk-grid">
            @foreach($kelasList as $kelas)
                <a href="{{ route('dosen.detail-kelas', $kelas->id_kelas) }}" class="lk-card" style="text-decoration: none;">
                    <div class="lk-card-accent" style="background: {{ $loop->index % 4 === 0 ? 'linear-gradient(135deg,#1565C0,#0D1F3C)' : ($loop->index % 4 === 1 ? 'linear-gradient(135deg,#1A3560,#1565C0)' : ($loop->index % 4 === 2 ? 'linear-gradient(135deg,#0D1F3C,#1A3560)' : 'linear-gradient(135deg,#1565C0,#1A3560)')) }}"></div>
                    <div class="lk-card-body">
                        <div class="lk-card-badge">
                            {{ strtoupper($kelas->kelas_label ?? 'KELAS') }}
                        </div>
                        <h3 class="lk-card-title">{{ $kelas->nama_kelas }}</h3>
                        <p class="lk-card-code">
                            <i class="bi bi-hash"></i> {{ $kelas->kode_kelas }}
                        </p>

                        {{-- Token join untuk dosen --}}
                        <div class="lk-card-token" onclick="event.preventDefault(); copyToken('{{ $kelas->join_token }}', this)">
                            <i class="bi bi-key-fill"></i>
                            <span class="lk-token-value">{{ $kelas->join_token }}</span>
                            <span class="lk-token-copy"><i class="bi bi-clipboard"></i></span>
                        </div>
                    </div>
                    <div class="lk-card-footer">
                        <span class="lk-member-count">
                            <i class="bi bi-people-fill"></i>
                            {{ $kelas->kelasMahasiswa->count() }} Mahasiswa
                        </span>
                        <span class="lk-card-arrow">
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
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

    .lk-wrapper {
        padding: 2rem 2.5rem;
        min-height: 80vh;
    }

    /* ── Header ── */
    .lk-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .lk-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .lk-header-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--mcu-blue), var(--itb-navy));
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.4rem;
        box-shadow: 0 4px 16px rgba(21,101,192,.35);
    }
    .lk-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--itb-navy);
        margin: 0;
    }
    .lk-subtitle {
        color: var(--text-muted);
        margin: 0;
        font-size: .9rem;
    }

    /* ── Info dosen (pengganti tombol join) ── */
    .lk-header-info {
        display: inline-flex; align-items: center; gap: .55rem;
        padding: .6rem 1.2rem;
        background: rgba(13,31,60,.06);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: .88rem;
        font-weight: 600;
        color: var(--itb-navy);
    }
    .lk-header-info i { color: var(--mcu-blue); }

    /* ── Alert ── */
    .lk-alert {
        display: flex; align-items: center; gap: .75rem;
        padding: .85rem 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: .9rem;
        font-weight: 500;
    }
    .lk-alert-success { background: #E8F5E9; color: #1B5E20; border: 1px solid #A5D6A7; }
    .lk-alert-danger  { background: #FFEBEE; color: var(--danger); border: 1px solid #EF9A9A; }

    /* ── Grid ── */
    .lk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    /* ── Card ── */
    .lk-card {
        background: var(--white);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
        cursor: pointer;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 2px 10px rgba(13,31,60,.07);
        display: flex;
        flex-direction: column;
        color: inherit;
    }
    .lk-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(21,101,192,.18);
        border-color: var(--mcu-blue);
    }
    .lk-card-accent { height: 6px; width: 100%; }
    .lk-card-body { padding: 1.25rem 1.5rem 1rem; flex: 1; }
    .lk-card-badge {
        display: inline-block;
        padding: .25rem .75rem;
        background: rgba(21,101,192,.1);
        color: var(--mcu-blue);
        border-radius: 20px;
        font-size: .72rem; font-weight: 700; letter-spacing: .05em;
        margin-bottom: .75rem;
    }
    .lk-card-title {
        font-size: 1.05rem; font-weight: 700;
        color: var(--itb-navy); margin-bottom: .4rem; line-height: 1.35;
    }
    .lk-card-code {
        font-size: .82rem; color: var(--text-muted); margin-bottom: .85rem;
    }

    /* ── Token display ── */
    .lk-card-token {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .35rem .8rem;
        background: rgba(201,168,76,.1);
        border: 1px dashed rgba(201,168,76,.5);
        border-radius: 8px;
        font-size: .78rem;
        color: #7a5c00;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
        letter-spacing: .04em;
    }
    .lk-card-token:hover { background: rgba(201,168,76,.2); }
    .lk-token-copy { opacity: .6; font-size: .75rem; }
    .lk-token-copied { color: var(--mcu-green) !important; border-color: var(--mcu-green) !important; background: rgba(46,125,50,.08) !important; }

    .lk-card-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: .85rem 1.5rem;
        border-top: 1px solid var(--border);
        background: var(--bg-light);
    }
    .lk-member-count {
        font-size: .82rem; color: var(--text-muted);
        display: flex; align-items: center; gap: .35rem;
    }
    .lk-card-arrow {
        color: var(--mcu-blue); font-size: 1.25rem; transition: transform .2s;
    }
    .lk-card:hover .lk-card-arrow { transform: translateX(4px); }

    /* ── Empty State ── */
    .lk-empty {
        text-align: center; padding: 5rem 2rem; color: var(--text-muted);
    }
    .lk-empty-icon { font-size: 4rem; color: var(--border); margin-bottom: 1rem; }
    .lk-empty h3 { color: var(--itb-navy); font-size: 1.3rem; margin-bottom: .5rem; }
    .lk-empty p { max-width: 380px; margin: 0 auto; font-size: .9rem; line-height: 1.6; }

    @media (max-width: 576px) {
        .lk-wrapper { padding: 1.25rem 1rem; }
        .lk-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('ExtraJS')
<script>
    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.lk-alert').forEach(el => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 4000);

    // Copy token ke clipboard
    function copyToken(token, el) {
        navigator.clipboard.writeText(token).then(() => {
            el.classList.add('lk-token-copied');
            const icon = el.querySelector('.lk-token-copy i');
            icon.className = 'bi bi-clipboard-check';
            setTimeout(() => {
                el.classList.remove('lk-token-copied');
                icon.className = 'bi bi-clipboard';
            }, 2000);
        });
    }
</script>
@endsection