@extends('layouts.index')

@section('content')
<div class="kelas-wrapper">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <p class="page-eyebrow">Manajemen Kelas</p>
            <h1>Daftar <span>Kelas</span></h1>
        </div>
        <a href="{{ route('admin.create-kelas') }}" class="btn-add">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Kelas
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="alert-success-kelas">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats Row --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon navy">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $kelas->count() }}</div>
                <div class="stat-label">Total Kelas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $kelas->pluck('dosen_nik')->unique()->count() }}</div>
                <div class="stat-label">Dosen Terlibat</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div>
                <div class="stat-value">{{ $kelas->sum(fn($k) => $k->kelasMahasiswa->count()) }}</div>
                <div class="stat-label">Total Mahasiswa</div>
            </div>
        </div>
    </div>

    {{-- List Card --}}
    <div class="list-card">
        <div class="gold-accent"></div>

        @if($kelas->isEmpty())
            <div class="empty-state">
                <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <strong>Belum ada kelas</strong>
                <p>Klik tombol "Tambah Kelas" untuk membuat kelas pertama.</p>
            </div>
        @else
            <div class="list-toolbar">
                <div class="search-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="tableSearch" placeholder="Cari nama kelas, kode, atau dosen...">
                </div>
                <span class="list-count">Menampilkan <strong id="visibleCount">{{ $kelas->count() }}</strong> kelas</span>
            </div>

            <table class="kelas-table" id="kelasTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kelas</th>
                        <th>Kode</th>
                        <th>Dosen</th>
                        <th>Mahasiswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach($kelas as $k)
                    {{-- Build mahasiswa JSON for modal --}}
                    @php
                        $mhsList = $k->kelasMahasiswa->map(function($km) {
                            return [
                                'nama' => $km->mahasiswa->user->nama ?? '-',
                                'nrp'  => $km->mahasiswa_nrp,
                            ];
                        })->values()->toJson();
                        $dosenNama = $k->dosen->user->nama ?? null;
                        $dosenNik  = $k->dosen_nik ?? '-';
                    @endphp
                    <tr data-search="{{ strtolower($k->nama_kelas . ' ' . $k->kode_kelas . ' ' . ($dosenNama ?? '')) }}">
                        <td><span class="id-badge">#{{ $k->id_kelas }}</span></td>
                        <td><div class="kelas-name">{{ $k->nama_kelas }}</div></td>
                        <td><span class="kode-badge">{{ $k->kode_kelas }}</span></td>
                        <td>
                            @if($dosenNama)
                                <div class="dosen-cell">
                                    <div class="dosen-avatar">{{ substr($dosenNama, 0, 2) }}</div>
                                    <span class="dosen-name">{{ $dosenNama }}</span>
                                </div>
                            @else
                                <span style="color:var(--text-muted);font-size:.83rem;">—</span>
                            @endif
                        </td>
                        <td>
                            @php $count = $k->kelasMahasiswa->count(); @endphp
                            <span class="mhs-pill {{ $count === 0 ? 'zero' : '' }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                                {{ $count }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                {{-- Detail button --}}
                                <button type="button" class="btn-detail"
                                    data-id="{{ $k->id_kelas }}"
                                    data-nama="{{ $k->nama_kelas }}"
                                    data-kode="{{ $k->kode_kelas }}"
                                    data-label="{{ $k->kelas_label ?? '' }}"
                                    data-token="{{ $k->join_token ?? '' }}"
                                    data-dosen-nama="{{ $dosenNama }}"
                                    data-dosen-nik="{{ $dosenNik }}"
                                    data-edit-url="{{ route('admin.edit-kelas', $k->id_kelas) }}"
                                    data-mahasiswa='{!! $mhsList !!}'
                                    onclick="openModalFromButton(this)">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                    Detail
                                </button>
                                <a href="{{ route('admin.edit-kelas', $k->id_kelas) }}" class="btn-edit">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.destroy-kelas', $k->id_kelas) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete"
                                        onclick="confirmDelete(this, {{ json_encode($k->nama_kelas) }})">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div id="noSearchResult">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--border);margin-bottom:.7rem"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p>Tidak ada kelas yang cocok dengan pencarian.</p>
            </div>
        @endif
    </div>
</div>

<div class="modal-overlay" id="detailModal" onclick="closeOnBackdrop(event)">
    <div class="modal-box">
        <div class="modal-gold-bar"></div>

        <div class="modal-header">
            <div class="modal-header-top">
                <div>
                    <div class="modal-badge">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        Detail Kelas
                    </div>
                    <h2 class="modal-title" id="m-nama">—</h2>
                    <span class="modal-kode" id="m-kode">—</span>
                </div>
                <button class="modal-close" onclick="closeModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </div>

        <div class="modal-body">

            {{-- Info grid --}}
            <div class="modal-info-grid">
                <div class="modal-info-item">
                    <div class="modal-info-label">ID Kelas</div>
                    <div class="modal-info-value mono" id="m-id">—</div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-label">Kode Kelas</div>
                    <div class="modal-info-value mono" id="m-kode-val">—</div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-label">Label Kelas</div>
                    <div class="modal-info-value mono" id="m-label">—</div>
                </div>
                <div class="modal-info-item">
                    <div class="modal-info-label">Token Kelas</div>
                    <div class="modal-info-value mono" id="m-token">—</div>
                </div>
                <div class="modal-info-item" style="grid-column: span 2">
                    <div class="modal-info-label">Nama Kelas</div>
                    <div class="modal-info-value" id="m-nama-val">—</div>
                </div>
            </div>

            {{-- Dosen --}}
            <p class="modal-section-title">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Dosen Pengampu
            </p>
            <div class="modal-dosen-card">
                <div class="modal-dosen-avatar" id="m-dosen-avatar">—</div>
                <div>
                    <div class="modal-dosen-name" id="m-dosen-nama">—</div>
                    <div class="modal-dosen-nik"  id="m-dosen-nik">—</div>
                </div>
            </div>

            {{-- Mahasiswa --}}
            <div class="modal-mhs-header">
                <p class="modal-section-title" style="margin:0;border:none;padding:0">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    Daftar Mahasiswa
                </p>
                <span class="modal-mhs-count" id="m-mhs-count">0</span>
            </div>
            <div class="modal-mhs-list" id="m-mhs-list"></div>

        </div>

        <div class="modal-footer">
            <a href="#" class="modal-btn-edit" id="m-edit-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Kelas
            </a>
            <button class="modal-btn-close" onclick="closeModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('ExtraCSS')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap');

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

    .kelas-wrapper {
        min-height: 100vh;
        background: var(--bg-light);
        padding: 2.5rem 1rem;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Page Header ── */
    .page-header {
        max-width: 960px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-header-left .page-eyebrow {
        font-size: .72rem; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; color: var(--text-muted); margin-bottom: .3rem;
    }
    .page-header-left h1 {
        /* font-family: 'Playfair Display', serif; */
        font-size: 2rem; font-weight: 900;
        color: var(--itb-navy); margin: 0; line-height: 1.15;
    }
    .page-header-left h1 span { color: var(--mcu-blue); }

    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, var(--itb-navy), var(--mcu-blue));
        color: var(--white); border: none; border-radius: 10px;
        padding: .7rem 1.5rem; font-size: .88rem; font-weight: 600;
        cursor: pointer; text-decoration: none; font-family: 'DM Sans', sans-serif;
        box-shadow: 0 4px 14px rgba(13,31,60,.22);
        transition: opacity .2s, transform .15s; white-space: nowrap;
    }
    .btn-add:hover { opacity: .9; transform: translateY(-1px); color: var(--white); text-decoration: none; }

    /* ── Alert ── */
    .alert-success-kelas {
        max-width: 960px; margin: 0 auto 1.2rem;
        background: #E8F5E9; border-left: 4px solid var(--mcu-green);
        border-radius: 10px; padding: .8rem 1.2rem; color: #1B5E20;
        font-size: .88rem; display: flex; align-items: center; gap: 8px;
    }

    /* ── Stats Row ── */
    .stats-row {
        max-width: 960px; margin: 0 auto 1.4rem;
        display: grid; grid-template-columns: repeat(3, 1fr); gap: .9rem;
    }
    .stat-card {
        background: var(--white); border-radius: 14px;
        padding: 1.1rem 1.3rem; box-shadow: 0 2px 12px rgba(13,31,60,.07);
        display: flex; align-items: center; gap: 12px;
    }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-icon.blue  { background: #E3F2FD; color: var(--mcu-blue); }
    .stat-icon.navy  { background: #E8EAF6; color: var(--itb-navy); }
    .stat-icon.green { background: #E8F5E9; color: var(--mcu-green); }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-main); line-height: 1; margin-bottom: 2px; }
    .stat-label { font-size: .76rem; color: var(--text-muted); font-weight: 500; }

    /* ── Main Card ── */
    .list-card {
        max-width: 960px; margin: 0 auto; background: var(--white);
        border-radius: 20px; box-shadow: 0 8px 40px rgba(13,31,60,.09); overflow: hidden;
    }
    .gold-accent { height: 3px; background: linear-gradient(90deg, var(--mcu-gold), transparent); }

    /* ── Toolbar ── */
    .list-toolbar {
        display: flex; align-items: center; gap: 10px;
        padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--border); flex-wrap: wrap;
    }
    .search-wrap {
        display: flex; align-items: center; gap: 8px;
        flex: 1; min-width: 200px; background: var(--bg-light);
        border: 1.5px solid var(--border); border-radius: 9px; padding: .5rem .9rem;
    }
    .search-wrap input {
        border: none; outline: none; background: transparent;
        font-size: .88rem; font-family: 'DM Sans', sans-serif; color: var(--text-main); width: 100%;
    }
    .search-wrap input::placeholder { color: var(--text-muted); }
    .search-wrap svg { color: var(--text-muted); flex-shrink: 0; }
    .list-count { font-size: .8rem; color: var(--text-muted); white-space: nowrap; }
    .list-count strong { color: var(--itb-navy); }

    /* ── Table ── */
    .kelas-table { width: 100%; border-collapse: collapse; }
    .kelas-table thead tr { background: #F8FAFD; border-bottom: 2px solid var(--border); }
    .kelas-table thead th {
        padding: .85rem 1.2rem; font-size: .72rem; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase; color: var(--text-muted);
        text-align: left; white-space: nowrap;
    }
    .kelas-table tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
    .kelas-table tbody tr:last-child { border-bottom: none; }
    .kelas-table tbody tr:hover { background: #F4F7FF; }
    .kelas-table tbody tr.row-hidden { display: none; }
    .kelas-table td { padding: 1rem 1.2rem; font-size: .88rem; color: var(--text-main); vertical-align: middle; }

    .id-badge {
        display: inline-block; background: #EEF3FC; color: var(--itb-navy-mid);
        font-size: .75rem; font-weight: 700; padding: 2px 9px; border-radius: 6px; font-family: monospace;
    }
    .kelas-name { font-weight: 600; color: var(--text-main); }
    .kode-badge {
        display: inline-block; background: #FFF8E1; color: #92681A;
        border: 1px solid #FFE082; font-size: .78rem; font-weight: 600;
        padding: 3px 10px; border-radius: 20px; letter-spacing: .03em;
    }
    .dosen-cell { display: flex; align-items: center; gap: 8px; }
    .dosen-avatar {
        width: 30px; height: 30px; border-radius: 50%;
        background: linear-gradient(135deg, var(--itb-navy), var(--mcu-blue));
        color: white; font-size: .72rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; text-transform: uppercase;
    }
    .dosen-name { font-size: .86rem; font-weight: 500; }
    .mhs-pill {
        display: inline-flex; align-items: center; gap: 5px;
        background: #E8F5E9; color: var(--mcu-green); border: 1px solid #C8E6C9;
        font-size: .8rem; font-weight: 600; padding: 4px 11px; border-radius: 20px;
    }
    .mhs-pill.zero { background: #F5F5F5; color: var(--text-muted); border-color: var(--border); }

    /* ── Action buttons ── */
    .action-group { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
    .btn-detail {
        display: inline-flex; align-items: center; gap: 5px;
        background: #E3F2FD; color: var(--mcu-blue); border: 1px solid #90CAF9;
        border-radius: 8px; padding: 5px 11px; font-size: .8rem; font-weight: 600;
        font-family: 'DM Sans', sans-serif; cursor: pointer;
        transition: background .15s; white-space: nowrap;
    }
    .btn-detail:hover { background: #BBDEFB; border-color: #64B5F6; }
    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        background: #FFF8E1; color: #92681A; border: 1px solid #FFE082;
        border-radius: 8px; padding: 5px 11px; font-size: .8rem; font-weight: 600;
        text-decoration: none; font-family: 'DM Sans', sans-serif;
        transition: background .15s; white-space: nowrap; cursor: pointer;
    }
    .btn-edit:hover { background: #FFE082; border-color: #FFD54F; color: #6D4C0E; text-decoration: none; }
    .btn-delete {
        display: inline-flex; align-items: center; gap: 5px;
        background: #FFEBEE; color: var(--danger); border: 1px solid #FFCDD2;
        border-radius: 8px; padding: 5px 11px; font-size: .8rem; font-weight: 600;
        font-family: 'DM Sans', sans-serif; transition: background .15s;
        cursor: pointer; white-space: nowrap;
    }
    .btn-delete:hover { background: #FFCDD2; border-color: #EF9A9A; }

    /* ── Empty / No-result ── */
    .empty-state { text-align: center; padding: 3.5rem 2rem; }
    .empty-state svg { color: var(--border); margin-bottom: 1rem; }
    .empty-state p { color: var(--text-muted); font-size: .9rem; margin: 0; }
    .empty-state strong { display: block; font-size: 1rem; color: var(--text-main); margin-bottom: .3rem; }
    #noSearchResult {
        display: none; text-align: center; padding: 2.5rem;
        color: var(--text-muted); font-size: .88rem;
    }

    /* ══════════════════════════════════
       MODAL
    ══════════════════════════════════ */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0; z-index: 1000;
        background: rgba(13,31,60,.45);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.open { display: flex; }

    .modal-box {
        background: var(--white);
        border-radius: 20px;
        width: 100%;
        max-width: 580px;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 80px rgba(13,31,60,.3);
        animation: modalIn .22s cubic-bezier(.34,1.3,.64,1);
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(18px) scale(.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Modal header */
    .modal-header {
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 55%, var(--mcu-blue) 100%);
        padding: 1.6rem 1.8rem 1.4rem;
        position: relative;
        flex-shrink: 0;
    }
    .modal-header::before {
        content: '';
        position: absolute; right: -30px; top: -30px;
        width: 130px; height: 130px; border-radius: 50%;
        background: rgba(201,168,76,.12);
    }
    .modal-gold-bar {
        height: 3px;
        background: linear-gradient(90deg, var(--mcu-gold), transparent);
        flex-shrink: 0;
    }
    .modal-header-top {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 10px;
    }
    .modal-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(201,168,76,.18); border: 1px solid rgba(201,168,76,.35);
        color: var(--mcu-gold); font-size: .7rem; font-weight: 600;
        letter-spacing: .07em; text-transform: uppercase;
        padding: 3px 10px; border-radius: 20px; margin-bottom: .7rem;
    }
    .modal-title {
        /* font-family: 'Playfair Display', serif; */
        font-size: 1.45rem; font-weight: 700; color: var(--white);
        margin: 0 0 .2rem; line-height: 1.25;
    }
    .modal-kode {
        display: inline-block; background: rgba(255,255,255,.12);
        color: rgba(255,255,255,.75); font-size: .78rem; font-weight: 600;
        padding: 2px 10px; border-radius: 20px; margin-top: 4px;
        letter-spacing: .04em;
    }
    .modal-close {
        background: rgba(255,255,255,.12); border: none; cursor: pointer;
        color: rgba(255,255,255,.8); border-radius: 8px;
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: background .15s;
    }
    .modal-close:hover { background: rgba(255,255,255,.22); color: white; }

    /* Modal body */
    .modal-body {
        overflow-y: auto;
        padding: 1.5rem 1.8rem 1.8rem;
        flex: 1;
    }
    .modal-body::-webkit-scrollbar { width: 5px; }
    .modal-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

    /* Info grid */
    .modal-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .9rem;
        margin-bottom: 1.4rem;
    }
    .modal-info-item {
        background: var(--bg-light);
        border-radius: 11px;
        padding: .85rem 1rem;
    }
    .modal-info-label {
        font-size: .68rem; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: var(--text-muted); margin-bottom: .3rem;
    }
    .modal-info-value {
        font-size: .9rem; font-weight: 600; color: var(--text-main);
    }
    .modal-info-value.mono { font-family: monospace; font-size: .88rem; }

    /* Dosen section */
    .modal-section-title {
        font-size: .7rem; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; color: var(--text-muted);
        margin: 0 0 .8rem; padding-bottom: .45rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 6px;
    }
    .modal-dosen-card {
        display: flex; align-items: center; gap: 12px;
        background: var(--bg-light); border-radius: 12px;
        padding: .9rem 1.1rem; margin-bottom: 1.4rem;
    }
    .modal-dosen-avatar {
        width: 42px; height: 42px; border-radius: 50%;
        background: linear-gradient(135deg, var(--itb-navy), var(--mcu-blue));
        color: white; font-size: .9rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        text-transform: uppercase; flex-shrink: 0;
    }
    .modal-dosen-name { font-size: .92rem; font-weight: 600; color: var(--text-main); }
    .modal-dosen-nik  { font-size: .78rem; color: var(--text-muted); margin-top: 2px; font-family: monospace; }

    /* Mahasiswa list */
    .modal-mhs-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: .8rem; padding-bottom: .45rem; border-bottom: 1px solid var(--border);
    }
    .modal-mhs-count {
        background: var(--mcu-blue); color: white;
        font-size: .72rem; font-weight: 700;
        padding: 2px 9px; border-radius: 20px; min-width: 24px; text-align: center;
    }
    .modal-mhs-list { display: flex; flex-direction: column; gap: .45rem; }
    .modal-mhs-item {
        display: flex; align-items: center; gap: 10px;
        background: var(--bg-light); border-radius: 9px;
        padding: .6rem .9rem;
    }
    .modal-mhs-num {
        width: 20px; height: 20px; border-radius: 50%;
        background: var(--border); color: var(--text-muted);
        font-size: .68rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .modal-mhs-name { font-size: .86rem; font-weight: 500; color: var(--text-main); flex: 1; }
    .modal-mhs-nrp  { font-size: .75rem; color: var(--text-muted); font-family: monospace; }
    .modal-mhs-empty {
        text-align: center; padding: 1.4rem;
        color: var(--text-muted); font-size: .85rem;
        background: var(--bg-light); border-radius: 10px;
    }

    /* Modal footer */
    .modal-footer {
        padding: 1rem 1.8rem 1.4rem;
        border-top: 1px solid var(--border);
        display: flex; gap: .7rem; flex-wrap: wrap;
        flex-shrink: 0;
    }
    .modal-btn-edit {
        display: inline-flex; align-items: center; gap: 7px;
        background: linear-gradient(135deg, var(--itb-navy), var(--mcu-blue));
        color: white; border: none; border-radius: 10px;
        padding: .65rem 1.4rem; font-size: .86rem; font-weight: 600;
        font-family: 'DM Sans', sans-serif; text-decoration: none;
        cursor: pointer; transition: opacity .2s;
    }
    .modal-btn-edit:hover { opacity: .88; color: white; text-decoration: none; }
    .modal-btn-close {
        display: inline-flex; align-items: center; gap: 7px;
        background: none; color: var(--text-muted);
        border: 1.5px solid var(--border); border-radius: 10px;
        padding: .63rem 1.2rem; font-size: .86rem; font-weight: 500;
        font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .15s;
    }
    .modal-btn-close:hover { color: var(--text-main); border-color: #9AABCC; background: var(--bg-light); }

    @media (max-width: 640px) {
        .stats-row { grid-template-columns: 1fr; }
        .kelas-table thead th:nth-child(1), .kelas-table td:nth-child(1) { display: none; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .modal-info-grid { grid-template-columns: 1fr; }
        .modal-box { max-height: 95vh; border-radius: 16px; }
    }
</style>
@endsection

@section('ExtraJS')
<script>
    /* ── Table search ── */
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#tableBody tr');
            let visible = 0;
            rows.forEach(row => {
                const match = row.dataset.search.includes(q);
                row.classList.toggle('row-hidden', !match);
                if (match) visible++;
            });
            document.getElementById('visibleCount').textContent = visible;
            document.getElementById('noSearchResult').style.display = visible === 0 ? 'block' : 'none';
        });
    }

    /* ── Delete confirm ── */
    function confirmDelete(btn, namaKelas) {
        if (confirm(`Yakin ingin menghapus kelas "${namaKelas}"?\nSemua data mahasiswa di kelas ini juga akan dihapus.`)) {
            btn.closest('form').submit();
        }
    }

    /* ── Modal ── */
    function openModalFromButton(btn) {
        try {
            const mahasiswaRaw = btn.getAttribute('data-mahasiswa') || '[]';
            const mahasiswa = mahasiswaRaw ? JSON.parse(mahasiswaRaw) : [];
            const data = {
                id: btn.getAttribute('data-id'),
                nama: btn.getAttribute('data-nama'),
                kode: btn.getAttribute('data-kode'),
                label: btn.getAttribute('data-label'),   // ✅ TAMBAH
                token: btn.getAttribute('data-token'), 
                dosenNama: btn.getAttribute('data-dosen-nama') || null,
                dosenNik: btn.getAttribute('data-dosen-nik') || '-',
                editUrl: btn.getAttribute('data-edit-url') || '#',
                mahasiswa: mahasiswa
            };
            openModal(data);
        } catch (err) {
            console.error('Gagal membuka modal detail kelas:', err);
            alert('Terjadi kesalahan saat membuka detail kelas. Cek console untuk detail.');
        }
    }
    function openModal(data) {
        // Header
        document.getElementById('m-nama').textContent     = data.nama;
        document.getElementById('m-kode').textContent     = data.kode;
        // Info grid
        document.getElementById('m-id').textContent       = '#' + data.id;
        document.getElementById('m-kode-val').textContent = data.kode;
        document.getElementById('m-nama-val').textContent = data.nama;
        document.getElementById('m-label').textContent = data.label;
        document.getElementById('m-token').textContent = data.token;
        // Dosen
        const dosenNama = data.dosenNama || '—';
        document.getElementById('m-dosen-nama').textContent   = dosenNama;
        document.getElementById('m-dosen-nik').textContent    = 'NIK: ' + data.dosenNik;
        document.getElementById('m-dosen-avatar').textContent = dosenNama !== '—'
            ? dosenNama.substring(0, 2).toUpperCase() : '?';
        // Mahasiswa
        const list = document.getElementById('m-mhs-list');
        document.getElementById('m-mhs-count').textContent = data.mahasiswa.length;
        if (data.mahasiswa.length === 0) {
            list.innerHTML = '<div class="modal-mhs-empty">Belum ada mahasiswa yang terdaftar di kelas ini.</div>';
        } else {
            list.innerHTML = data.mahasiswa.map((m, i) => `
                <div class="modal-mhs-item">
                    <span class="modal-mhs-num">${i + 1}</span>
                    <span class="modal-mhs-name">${m.nama}</span>
                    <span class="modal-mhs-nrp">${m.nrp}</span>
                </div>
            `).join('');
        }
        // Edit link
        document.getElementById('m-edit-link').href = data.editUrl;
        // Open
        document.getElementById('detailModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('detailModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    function closeOnBackdrop(e) {
        if (e.target === document.getElementById('detailModal')) closeModal();
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endsection