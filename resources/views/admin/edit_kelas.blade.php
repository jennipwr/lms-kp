@extends('layouts.index')

@section('content')
<div class="kelas-wrapper">
    <div class="kelas-card">
        <div class="gold-accent"></div>

        <div class="kelas-header">
            <div class="header-badge">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Kelas
            </div>
            <h1 class="header-title">
                {{ $kelas->nama_kelas }}
                <span class="kelas-code">{{ $kelas->kode_kelas }}</span>
            </h1>
            <p class="header-sub">Perbarui informasi kelas, dosen, dan daftar mahasiswa</p>
        </div>

        <div class="kelas-body">

            @if($errors->any())
            <div class="alert-kelas">
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="edit-info-chip">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Anda sedang mengedit kelas — perubahan akan langsung diterapkan setelah disimpan
            </div>

            <form action="{{ route('admin.update-kelas', $kelas->id_kelas) }}" method="POST">
                @csrf
                @method('PUT')

                <p class="form-section-title">Informasi Kelas</p>

                <div class="field-group">
                    <label class="field-label">
                        <span class="dot"></span> Nama Kelas <span class="req">*</span>
                    </label>
                    <input type="text" name="nama_kelas" class="field-input"
                           value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span class="dot"></span> Kode Kelas <span class="req">*</span>
                    </label>
                    <input type="text" name="kode_kelas" class="field-input"
                           value="{{ old('kode_kelas', $kelas->kode_kelas) }}" required>
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span class="dot"></span> Label Kelas <span class="req">*</span>
                    </label>
                    <select name="kelas_label" class="field-input" required>
                        <option value="" disabled {{ old('kelas_label') ? '' : 'selected' }}>— Pilih Kelas —</option>
                        <option value="A" {{ old('kelas_label', $kelas->kelas_label) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('kelas_label', $kelas->kelas_label) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('kelas_label', $kelas->kelas_label) == 'C' ? 'selected' : '' }}>C</option>
                    </select>
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span class="dot"></span> Join Token <span class="req">*</span>
                    </label>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <input type="text" name="join_token" id="joinTokenInput" class="field-input"
                            placeholder="cth. AB12CD"
                            value="{{ old('join_token', $kelas->join_token) }}"
                            style="flex:1; margin-bottom:0;"
                            required>
                        <button type="button" onclick="regenerateToken()"
                            title="Generate ulang token"
                            style="flex-shrink:0; height:42px; width:42px; border-radius:8px;
                                background:#f5f0e8; border:1px solid #d4b896; cursor:pointer;
                                display:flex; align-items:center; justify-content:center;">
                            <svg id="regenIcon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="#8B6914" stroke-width="2.2">
                                <polyline points="23 4 23 10 17 10"/>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <hr class="section-divider">
                <p class="form-section-title">Pengajar &amp; Peserta</p>

                <div class="field-group">
                    <label class="field-label">
                        <span class="dot"></span> Dosen Pengampu <span class="req">*</span>
                    </label>
                    <select name="dosen_nik" class="field-input" required>
                        <option value="" disabled>— Pilih Dosen —</option>
                        @foreach($dosen as $d)
                            <option value="{{ $d->nik }}"
                                {{ old('dosen_nik', $kelas->dosen_nik) == $d->nik ? 'selected' : '' }}>
                                {{ $d->user->nama }} ({{ $d->nik }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ── Mahasiswa Checkbox Picker ── --}}
                <div class="field-group">
                    <label class="field-label">
                        <span class="dot"></span> Mahasiswa
                    </label>

                    <div class="picker-box">
                        <div class="picker-toolbar">
                            <svg class="picker-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" class="picker-search" id="mhsSearch" placeholder="Cari nama atau NRP mahasiswa...">
                        </div>

                        <div class="picker-actions">
                            <button type="button" class="btn-select-all" onclick="selectAll()">Pilih Semua</button>
                            <button type="button" class="btn-clear-all"  onclick="clearAll()">Hapus Pilihan</button>
                            <span class="picker-count">Terpilih: <strong id="selectedCount">0</strong> mahasiswa</span>
                        </div>

                        <div class="picker-list" id="pickerList">
                            @forelse($mahasiswa as $m)
                                @php
                                    // Prefer old() input (after validation fail), else use existing kelas data
                                    if (old('mahasiswa') !== null) {
                                        $checked = collect(old('mahasiswa'))->contains($m->nrp);
                                    } else {
                                        $checked = $kelas->kelasMahasiswa->contains('mahasiswa_nrp', $m->nrp);
                                    }
                                @endphp
                                <label class="picker-item {{ $checked ? 'is-checked' : '' }}"
                                       data-name="{{ strtolower($m->user->nama) }}"
                                       data-nrp="{{ strtolower($m->nrp) }}">
                                    <span class="picker-cb">
                                        <input type="checkbox" name="mahasiswa[]"
                                               value="{{ $m->nrp }}"
                                               {{ $checked ? 'checked' : '' }}
                                               onchange="onCheck(this)">
                                        <span class="cb-box">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                        </span>
                                    </span>
                                    <span class="picker-item-name">{{ $m->user->nama }}</span>
                                    <span class="picker-item-nrp">{{ $m->nrp }}</span>
                                </label>
                            @empty
                                <p class="picker-empty" style="display:block;">Belum ada data mahasiswa.</p>
                            @endforelse
                        </div>

                        <p class="picker-empty" id="noResult">Tidak ada mahasiswa yang cocok.</p>
                    </div>
                </div>

                <div class="btn-row">
                    <button type="submit" class="btn-update">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.list-kelas') }}" class="btn-back">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        Kembali
                    </a>
                </div>

            </form>
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
    .kelas-card {
        max-width: 700px;
        margin: 0 auto;
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(13,31,60,0.10);
        overflow: hidden;
    }
    .gold-accent {
        height: 3px;
        background: linear-gradient(270deg, var(--mcu-gold), transparent);
    }
    .kelas-header {
        background: linear-gradient(135deg, var(--itb-navy-mid) 0%, var(--itb-navy) 55%, #0A2856 100%);
        padding: 2.2rem 2.5rem 1.8rem;
        position: relative;
        overflow: hidden;
    }
    .kelas-header::before {
        content: '';
        position: absolute;
        left: -50px; bottom: -50px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(201,168,76,.08);
    }
    .header-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(46,125,50,.22);
        border: 1px solid rgba(46,125,50,.40);
        color: #81C784;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: .9rem;
    }
    .header-title {
        /* font-family: 'Playfair Display', serif; */
        font-size: 1.85rem;
        font-weight: 700;
        color: var(--white);
        margin: 0 0 .3rem;
        line-height: 1.2;
    }
    .header-title .kelas-code {
        font-size: 1rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        color: rgba(255,255,255,.5);
        margin-left: 8px;
    }
    .header-sub { color: rgba(255,255,255,.58); font-size: .88rem; margin: 0; }
    .kelas-body { padding: 2.2rem 2.5rem 2.5rem; }
    .alert-kelas {
        background: #FFEBEE;
        border-left: 4px solid var(--danger);
        border-radius: 10px;
        padding: 1rem 1.2rem;
        margin-bottom: 1.6rem;
    }
    .alert-kelas ul { margin: 0; padding-left: 1.2rem; color: var(--danger); font-size: .88rem; }
    .form-section-title {
        font-size: .7rem; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; color: var(--text-muted);
        margin: 0 0 1.1rem; padding-bottom: .5rem;
        border-bottom: 1px solid var(--border);
    }
    .field-group { margin-bottom: 1.4rem; }
    .field-label {
        display: flex; align-items: center; gap: 6px;
        font-size: .83rem; font-weight: 600; color: var(--text-main);
        margin-bottom: .45rem;
    }
    .field-label .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--mcu-blue); flex-shrink: 0; }
    .field-label .req { color: var(--danger); margin-left: 1px; }
    .field-input {
        width: 100%; padding: .7rem 1rem;
        border: 1.5px solid var(--border); border-radius: 10px;
        font-size: .9rem; color: var(--text-main); background: #FAFBFD;
        transition: border-color .2s, box-shadow .2s;
        outline: none; box-sizing: border-box; font-family: 'DM Sans', sans-serif;
    }
    .field-input:focus {
        border-color: var(--mcu-blue);
        box-shadow: 0 0 0 3px rgba(21,101,192,.12);
        background: var(--white);
    }
    select.field-input {
        cursor: pointer; appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7A99' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
        background-size: 16px; padding-right: 2.5rem;
    }
    .edit-info-chip {
        display: inline-flex; align-items: center; gap: 6px;
        background: #E3F2FD; border: 1px solid #90CAF9;
        color: #1565C0; font-size: .77rem; font-weight: 500;
        padding: 4px 10px; border-radius: 8px; margin-bottom: 1.5rem;
    }
    hr.section-divider { border: none; border-top: 1px dashed var(--border); margin: 1.8rem 0; }

    /* ── Mahasiswa Picker ── */
    .picker-box {
        border: 1.5px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        background: #FAFBFD;
    }
    .picker-toolbar {
        display: flex; align-items: center; gap: 8px;
        padding: .65rem .9rem;
        background: var(--white);
        border-bottom: 1px solid var(--border);
    }
    .picker-search {
        flex: 1; border: none; outline: none;
        font-size: .88rem; font-family: 'DM Sans', sans-serif;
        color: var(--text-main); background: transparent;
    }
    .picker-search::placeholder { color: var(--text-muted); }
    .picker-search-icon { color: var(--text-muted); flex-shrink: 0; }
    .picker-actions {
        display: flex; align-items: center; gap: 6px;
        padding: .5rem .9rem;
        background: var(--white);
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
    }
    .picker-count { font-size: .78rem; color: var(--text-muted); margin-left: auto; }
    .picker-count strong { color: var(--mcu-blue); }
    .btn-select-all, .btn-clear-all {
        font-size: .75rem; font-weight: 600; font-family: 'DM Sans', sans-serif;
        padding: 3px 10px; border-radius: 6px; cursor: pointer;
        border: 1px solid var(--border); background: var(--white);
        color: var(--text-muted); transition: all .15s;
    }
    .btn-select-all:hover { background: var(--mcu-blue); color: var(--white); border-color: var(--mcu-blue); }
    .btn-clear-all:hover  { background: #FFEBEE; color: var(--danger); border-color: #FFCDD2; }
    .picker-list { max-height: 220px; overflow-y: auto; padding: .4rem; }
    .picker-list::-webkit-scrollbar { width: 5px; }
    .picker-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
    .picker-item {
        display: flex; align-items: center; gap: 10px;
        padding: .55rem .75rem; border-radius: 8px;
        cursor: pointer; transition: background .12s; user-select: none;
    }
    .picker-item:hover { background: #EEF3FC; }
    .picker-item.is-checked { background: #E8F0FE; }
    .picker-item.hidden { display: none; }
    .picker-cb { position: relative; flex-shrink: 0; width: 18px; height: 18px; }
    .picker-cb input[type="checkbox"] {
        position: absolute; opacity: 0;
        width: 100%; height: 100%; margin: 0; cursor: pointer;
    }
    .picker-cb .cb-box {
        width: 18px; height: 18px;
        border: 2px solid var(--border); border-radius: 5px;
        background: var(--white);
        display: flex; align-items: center; justify-content: center;
        transition: background .15s, border-color .15s;
        pointer-events: none;
    }
    .picker-cb input:checked ~ .cb-box { background: var(--mcu-blue); border-color: var(--mcu-blue); }
    .picker-cb .cb-box svg { display: none; }
    .picker-cb input:checked ~ .cb-box svg { display: block; }
    .picker-item-name { font-size: .88rem; font-weight: 500; color: var(--text-main); flex: 1; }
    .picker-item-nrp  { font-size: .76rem; color: var(--text-muted); }
    .picker-empty { text-align: center; padding: 1.5rem; color: var(--text-muted); font-size: .85rem; display: none; }

    /* Buttons */
    .btn-row { display: flex; align-items: center; gap: .8rem; flex-wrap: wrap; margin-top: 2rem; }
    .btn-update {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, var(--mcu-green), #1B5E20);
        color: var(--white); border: none; border-radius: 10px;
        padding: .75rem 1.8rem; font-size: .9rem; font-weight: 600;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
        box-shadow: 0 4px 14px rgba(46,125,50,.3);
        transition: opacity .2s, transform .15s;
        text-decoration: none;
    }
    .btn-update:hover { opacity: .9; transform: translateY(-1px); }
    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--text-muted); background: none;
        border: 1.5px solid var(--border); border-radius: 10px;
        padding: .72rem 1.4rem; font-size: .88rem; font-weight: 500;
        cursor: pointer; text-decoration: none; font-family: 'DM Sans', sans-serif;
        transition: color .2s, border-color .2s, background .2s;
    }
    .btn-back:hover { color: var(--text-main); border-color: #9AABCC; background: var(--bg-light); }
</style>
@endsection

@section('ExtraJS')
<script>
    document.getElementById('mhsSearch').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        const items = document.querySelectorAll('#pickerList .picker-item');
        let visible = 0;
        items.forEach(item => {
            const match = item.dataset.name.includes(q) || item.dataset.nrp.includes(q);
            item.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        document.getElementById('noResult').style.display = visible === 0 ? 'block' : 'none';
    });

    function updateCount() {
        const n = document.querySelectorAll('#pickerList input[type="checkbox"]:checked').length;
        document.getElementById('selectedCount').textContent = n;
    }

    function onCheck(cb) {
        cb.closest('.picker-item').classList.toggle('is-checked', cb.checked);
        updateCount();
    }

    function selectAll() {
        document.querySelectorAll('#pickerList .picker-item:not(.hidden) input[type="checkbox"]').forEach(cb => {
            cb.checked = true;
            cb.closest('.picker-item').classList.add('is-checked');
        });
        updateCount();
    }

    function clearAll() {
        document.querySelectorAll('#pickerList input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
            cb.closest('.picker-item').classList.remove('is-checked');
        });
        updateCount();
    }

    updateCount();

    function regenerateToken() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let token = '';
        for (let i = 0; i < 6; i++) {
            token += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('joinTokenInput').value = token;

        // Animasi putar ikon
        const icon = document.getElementById('regenIcon');
        icon.style.transition = 'transform 0.4s ease';
        icon.style.transform = 'rotate(360deg)';
        setTimeout(() => {
            icon.style.transition = 'none';
            icon.style.transform = 'rotate(0deg)';
        }, 400);
    }
</script>
@endsection
