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
                <p class="lk-subtitle">Daftar kelas yang Anda ikuti</p>
            </div>
        </div>
        <button class="lk-btn-join" data-bs-toggle="modal" data-bs-target="#modalJoinKelas">
            <i class="bi bi-plus-lg"></i>
            <span>Gabung Kelas</span>
        </button>
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
            <p>Anda belum terdaftar di kelas manapun. Klik tombol <strong>Gabung Kelas</strong> untuk mulai.</p>
            <button class="lk-btn-join mt-3" data-bs-toggle="modal" data-bs-target="#modalJoinKelas">
                <i class="bi bi-plus-lg"></i>
                <span>Gabung Kelas Sekarang</span>
            </button>
        </div>
    @else
        <div class="lk-grid">
            @foreach($kelasList as $kelas)
                <a href="{{ route('mahasiswa.detail-kelas', $kelas->id_kelas) }}" class="lk-card" style="text-decoration: none;">
                    <div class="lk-card-accent" style="background: {{ $loop->index % 4 === 0 ? 'linear-gradient(135deg,#1565C0,#0D1F3C)' : ($loop->index % 4 === 1 ? 'linear-gradient(135deg,#1A3560,#1565C0)' : ($loop->index % 4 === 2 ? 'linear-gradient(135deg,#0D1F3C,#1A3560)' : 'linear-gradient(135deg,#1565C0,#1A3560)')) }}"></div>
                    <div class="lk-card-body">
                        <div class="lk-card-badge">
                            {{ strtoupper($kelas->kelas_label ?? 'KELAS') }}
                        </div>
                        <h3 class="lk-card-title">{{ $kelas->nama_kelas }}</h3>
                        <p class="lk-card-code">
                            <i class="bi bi-hash"></i> {{ $kelas->kode_kelas }}
                        </p>
                        <div class="lk-card-dosen">
                            <div class="lk-dosen-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span>
                                {{ $kelas->dosen->user->nama ?? 'Dosen tidak tersedia' }}
                            </span>
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

{{-- Modal Join Kelas --}}
<div class="modal fade" id="modalJoinKelas" tabindex="-1" aria-labelledby="modalJoinLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content lk-modal-content">
            <div class="lk-modal-header">
                <div class="lk-modal-icon">
                    <i class="bi bi-key-fill"></i>
                </div>
                <div>
                    <h5 class="lk-modal-title" id="modalJoinLabel">Gabung Kelas</h5>
                    <p class="lk-modal-subtitle">Masukkan token yang diberikan oleh dosen Anda</p>
                </div>
                <button type="button" class="lk-modal-close" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('mahasiswa.join-kelas') }}" method="POST">
                @csrf
                <div class="lk-modal-body">
                    <label class="lk-input-label" for="joinToken">Token Kelas</label>
                    <div class="lk-input-group">
                        <span class="lk-input-icon"><i class="bi bi-ticket-perforated-fill"></i></span>
                        <input
                            type="text"
                            id="joinToken"
                            name="join_token"
                            class="lk-input @error('join_token') is-invalid @enderror"
                            placeholder="Contoh: ABC123XYZ"
                            autocomplete="off"
                            required
                        >
                    </div>
                    @error('join_token')
                        <span class="lk-input-error">{{ $message }}</span>
                    @enderror
                    <p class="lk-input-hint">
                        <i class="bi bi-info-circle"></i>
                        Token kelas bersifat unik dan diberikan oleh dosen pengampu.
                    </p>
                </div>
                <div class="lk-modal-footer">
                    <button type="button" class="lk-btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="lk-btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Gabung Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('ExtraCSS')
<style>
    /* ── Variabel Warna ── */
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

    /* ── Wrapper ── */
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

    /* ── Tombol Join (header) ── */
    .lk-btn-join {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .65rem 1.4rem;
        background: linear-gradient(135deg, var(--mcu-blue), var(--itb-navy-mid));
        color: white;
        border: none; border-radius: 10px;
        font-weight: 600; font-size: .9rem;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(21,101,192,.4);
        transition: transform .15s, box-shadow .15s;
    }
    .lk-btn-join:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(21,101,192,.55);
    }

    /* ── Alert ── */
    .lk-alert {
        display: flex; align-items: center; gap: .75rem;
        padding: .85rem 1.25rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: .9rem;
        font-weight: 500;
    }
    .lk-alert-success {
        background: #E8F5E9; color: #1B5E20; border: 1px solid #A5D6A7;
    }
    .lk-alert-danger {
        background: #FFEBEE; color: var(--danger); border: 1px solid #EF9A9A;
    }

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
    .lk-card-accent {
        height: 6px;
        width: 100%;
    }
    .lk-card-body {
        padding: 1.25rem 1.5rem 1rem;
        flex: 1;
    }
    .lk-card-badge {
        display: inline-block;
        padding: .25rem .75rem;
        background: rgba(21,101,192,.1);
        color: var(--mcu-blue);
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .05em;
        margin-bottom: .75rem;
    }
    .lk-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--itb-navy);
        margin-bottom: .4rem;
        line-height: 1.35;
    }
    .lk-card-code {
        font-size: .82rem;
        color: var(--text-muted);
        margin-bottom: .9rem;
    }
    .lk-card-dosen {
        display: flex; align-items: center; gap: .6rem;
        font-size: .85rem;
        color: var(--text-main);
    }
    .lk-dosen-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--itb-navy-mid), var(--mcu-blue));
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: .75rem;
        flex-shrink: 0;
    }
    .lk-card-footer {
        display: flex; align-items: center; justify-content: space-between;
        padding: .85rem 1.5rem;
        border-top: 1px solid var(--border);
        background: var(--bg-light);
    }
    .lk-member-count {
        font-size: .82rem;
        color: var(--text-muted);
        display: flex; align-items: center; gap: .35rem;
    }
    .lk-card-arrow {
        color: var(--mcu-blue);
        font-size: 1.25rem;
        transition: transform .2s;
    }
    .lk-card:hover .lk-card-arrow {
        transform: translateX(4px);
    }

    /* ── Empty State ── */
    .lk-empty {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--text-muted);
    }
    .lk-empty-icon {
        font-size: 4rem;
        color: var(--border);
        margin-bottom: 1rem;
    }
    .lk-empty h3 {
        color: var(--itb-navy);
        font-size: 1.3rem;
        margin-bottom: .5rem;
    }
    .lk-empty p {
        max-width: 380px;
        margin: 0 auto;
        font-size: .9rem;
        line-height: 1.6;
    }

    /* ── Modal ── */
    .lk-modal-content {
        border-radius: 18px;
        border: none;
        box-shadow: 0 20px 60px rgba(13,31,60,.25);
        overflow: hidden;
    }
    .lk-modal-header {
        display: flex; align-items: flex-start; gap: 1rem;
        padding: 1.75rem 1.75rem 1rem;
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 50%, var(--mcu-blue) 100%);
        color: white;
        position: relative;
    }
    .lk-modal-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: rgba(255,255,255,.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .lk-modal-title {
        color: white; font-weight: 700; margin: 0; font-size: 1.1rem;
    }
    .lk-modal-subtitle {
        color: rgba(255,255,255,.7); font-size: .82rem; margin: .15rem 0 0;
    }
    .lk-modal-close {
        position: absolute; top: 1rem; right: 1rem;
        background: rgba(255,255,255,.15);
        border: none; border-radius: 8px;
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        color: white; cursor: pointer;
        transition: background .15s;
    }
    .lk-modal-close:hover { background: rgba(255,255,255,.25); }

    .lk-modal-body {
        padding: 1.5rem 1.75rem;
    }
    .lk-input-label {
        display: block;
        font-size: .85rem; font-weight: 600;
        color: var(--itb-navy);
        margin-bottom: .5rem;
    }
    .lk-input-group {
        display: flex; align-items: center;
        border: 2px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        transition: border-color .2s;
    }
    .lk-input-group:focus-within {
        border-color: var(--mcu-blue);
        box-shadow: 0 0 0 3px rgba(21,101,192,.12);
    }
    .lk-input-icon {
        padding: 0 .9rem;
        color: var(--text-muted);
        font-size: 1rem;
        background: var(--bg-light);
        height: 100%;
        display: flex; align-items: center;
        border-right: 1px solid var(--border);
    }
    .lk-input {
        flex: 1;
        border: none; outline: none;
        padding: .75rem 1rem;
        font-size: .9rem;
        color: var(--text-main);
        background: transparent;
        letter-spacing: .05em;
    }
    .lk-input-error { font-size: .8rem; color: var(--danger); margin-top: .4rem; display: block; }
    .lk-input-hint {
        font-size: .78rem;
        color: var(--text-muted);
        margin: .75rem 0 0;
        display: flex; align-items: flex-start; gap: .4rem;
        line-height: 1.5;
    }

    .lk-modal-footer {
        display: flex; justify-content: flex-end; gap: .75rem;
        padding: 1rem 1.75rem 1.5rem;
        border-top: 1px solid var(--border);
    }
    .lk-btn-secondary {
        padding: .65rem 1.25rem;
        background: var(--bg-light);
        border: 1px solid var(--border);
        border-radius: 9px;
        color: var(--text-muted);
        font-weight: 600; font-size: .88rem;
        cursor: pointer;
        transition: background .15s;
    }
    .lk-btn-secondary:hover { background: var(--border); }
    .lk-btn-primary {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .65rem 1.4rem;
        background: linear-gradient(135deg, var(--mcu-blue), var(--itb-navy-mid));
        color: white; border: none; border-radius: 9px;
        font-weight: 600; font-size: .88rem;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(21,101,192,.35);
        transition: transform .15s, box-shadow .15s;
    }
    .lk-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(21,101,192,.5);
    }

    @media (max-width: 576px) {
        .lk-wrapper { padding: 1.25rem 1rem; }
        .lk-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('ExtraJS')
<script>
    // Auto-dismiss alerts setelah 4 detik
    setTimeout(() => {
        document.querySelectorAll('.lk-alert').forEach(el => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 4000);
</script>
@endsection