@extends('layouts.index')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <button type="button" class="back-btn" onclick="konfirmasiKembali()" title="Kembali">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <h2 class="page-title">Buat Kuesioner Baru</h2>
    </div>

    <form id="formKuesioner" action="{{ route('admin.store-kuesioner') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Informasi Kuesioner
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nama Kuesioner</label>
                    <input type="text" name="nama_kuesioner" class="form-control" placeholder="Contoh: Kuesioner Gaya Belajar 2025" required>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Daftar Pertanyaan
            </div>
            <div class="card-body">
                <div id="pertanyaan-container"></div>

                <button type="button" class="btn btn-add" onclick="tambahPertanyaan()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Pertanyaan
                </button>

                <div class="form-actions">
                    <button type="submit" name="action" value="draft" class="btn btn-draft">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Draft
                    </button>
                    <button type="submit" name="action" value="publish" class="btn btn-publish">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan & Publish
                    </button>
                    <button type="button" class="btn btn-back" onclick="konfirmasiKembali()">
                        Batal
                    </button>
                </div>
            </div>
        </div>

    </form>
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

    .page-wrapper {
        /* padding: 2rem; */
        background: var(--bg-light);
        min-height: 100vh;
    }

    .page-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--white);
        color: var(--text-muted);
        text-decoration: none;
        transition: all 0.15s ease;
        cursor: pointer;
    }

    .back-btn:hover {
        background: var(--itb-navy);
        color: var(--white);
        border-color: var(--itb-navy);
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--itb-navy);
        letter-spacing: -0.3px;
        position: relative;
        padding-left: 1rem;
        margin: 0;
    }

    .page-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 10%;
        height: 80%;
        width: 4px;
        background: linear-gradient(to bottom, var(--mcu-gold), var(--mcu-blue));
        border-radius: 4px;
    }

    .card {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 2px 16px rgba(13, 31, 60, 0.08);
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-header {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--itb-navy), var(--itb-navy-mid));
        color: var(--white);
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-label {
        display: block;
        font-size: 0.825rem;
        font-weight: 600;
        color: var(--itb-navy);
        margin-bottom: 0.4rem;
        letter-spacing: 0.2px;
    }

    .form-control {
        width: 100%;
        padding: 0.6rem 0.9rem;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 0.9rem;
        color: var(--text-main);
        background: var(--bg-light);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        box-sizing: border-box;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--mcu-blue);
        box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.12);
        background: var(--white);
    }

    .form-select {
        width: 100%;
        padding: 0.6rem 0.9rem;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 0.9rem;
        color: var(--text-main);
        background: var(--bg-light);
        transition: border-color 0.15s ease;
        outline: none;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7A99' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        padding-right: 2.2rem;
    }

    .form-select:focus {
        border-color: var(--mcu-blue);
        box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.12);
        background-color: var(--white);
    }

    .pertanyaan-item {
        background: var(--bg-light);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .pertanyaan-item:first-child .remove-btn {
        display: none;
    }

    .pertanyaan-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .pertanyaan-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--itb-navy-mid);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .remove-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        border: 1px solid #ffcdd2;
        background: #ffebee;
        color: var(--danger);
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .remove-btn:hover {
        background: var(--danger);
        color: var(--white);
        border-color: var(--danger);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 1.2rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.18s ease;
    }

    .btn-add {
        background: transparent;
        color: var(--mcu-blue);
        border: 1.5px dashed var(--mcu-blue);
        width: 100%;
        justify-content: center;
        padding: 0.65rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .btn-add:hover {
        background: rgba(21, 101, 192, 0.06);
    }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }

    .btn-draft {
        background: var(--white);
        color: var(--itb-navy);
        border: 1.5px solid var(--border);
        box-shadow: 0 1px 4px rgba(13,31,60,0.06);
    }

    .btn-draft:hover {
        border-color: var(--mcu-gold);
        color: var(--itb-navy);
        background: #fffdf4;
    }

    .btn-publish {
        background: linear-gradient(135deg, var(--mcu-green), #1b5e20);
        color: var(--white);
        box-shadow: 0 3px 10px rgba(46, 125, 50, 0.3);
    }

    .btn-publish:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(46, 125, 50, 0.4);
    }

    .btn-back {
        background: transparent;
        color: var(--text-muted);
        border: 1.5px solid var(--border);
        margin-left: auto;
    }

    .btn-back:hover {
        background: #f5f5f5;
        color: var(--text-main);
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-back {
            margin-left: 0;
        }
    }
</style>
@endsection

@section('ExtraJS')
<script>
let pertanyaanCount = 0;

const dimensiOptions = [
    { value: 'profil',             label: 'Profil' },
    { value: 'active_reflective',  label: 'Active / Reflective' },
    { value: 'sensing_intuitive',  label: 'Sensing / Intuitive' },
    { value: 'visual_verbal',      label: 'Visual / Verbal' },
    { value: 'sequential_global',  label: 'Sequential / Global' },
];

function buildDimensiOptions(selected = '') {
    return dimensiOptions.map(o =>
        `<option value="${o.value}" ${selected === o.value ? 'selected' : ''}>${o.label}</option>`
    ).join('');
}

const kutubOptions = [
    { value: 'active',     label: 'Active' },
    { value: 'reflective', label: 'Reflective' },
    { value: 'sensing',    label: 'Sensing' },
    { value: 'intuitive',  label: 'Intuitive' },
    { value: 'visual',     label: 'Visual' },
    { value: 'verbal',     label: 'Verbal' },
    { value: 'sequential', label: 'Sequential' },
    { value: 'global',     label: 'Global' },
    { value: 'profil',     label: 'Profil' },
];

function buildKutubOptions(selected = '') {
    return kutubOptions.map(o =>
        `<option value="${o.value}" ${selected === o.value ? 'selected' : ''}>${o.label}</option>`
    ).join('');
}

function tambahPertanyaan(data = {}) {
    pertanyaanCount++;
    const container = document.getElementById('pertanyaan-container');
    const isFirst = container.children.length === 0;

    const html = `
    <div class="pertanyaan-item" id="pitem-${pertanyaanCount}">
        <div class="pertanyaan-header">
            <span class="pertanyaan-label">Pertanyaan ${pertanyaanCount}</span>
            ${!isFirst ? `<button type="button" class="remove-btn" onclick="hapusPertanyaan('pitem-${pertanyaanCount}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Hapus
            </button>` : ''}
        </div>

        <div class="form-group">
            <label class="form-label">Pertanyaan</label>
            <input type="text" name="pertanyaan[]" class="form-control" placeholder="Tuliskan pertanyaan di sini..." value="${data.pertanyaan || ''}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Dimensi</label>
            <select name="dimensi[]" class="form-select">
                ${buildDimensiOptions(data.dimensi || '')}
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Opsi A</label>
                <input type="text" name="opsi_a[]" class="form-control" placeholder="Pilihan A" value="${data.opsi_a || ''}" required>
                <label class="form-label" style="margin-top:0.5rem; font-size:0.8rem;">Kutub A</label>
                <select name="kutub_a[]" class="form-select">${buildKutubOptions(data.kutub_a || '')}</select>
            </div>
            <div class="form-group">
                <label class="form-label">Opsi B</label>
                <input type="text" name="opsi_b[]" class="form-control" placeholder="Pilihan B" value="${data.opsi_b || ''}" required>
                <label class="form-label" style="margin-top:0.5rem; font-size:0.8rem;">Kutub B</label>
                <select name="kutub_b[]" class="form-select">${buildKutubOptions(data.kutub_b || '')}</select>
            </div>
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);
    renumberPertanyaan();
}

function hapusPertanyaan(id) {
    document.getElementById(id)?.remove();
    renumberPertanyaan();
}

function renumberPertanyaan() {
    document.querySelectorAll('#pertanyaan-container .pertanyaan-label').forEach((el, i) => {
        el.textContent = `Pertanyaan ${i + 1}`;
    });
}

function konfirmasiKembali() {
    if (confirm("Apakah ingin menyimpan sebagai draft sebelum keluar?")) {
        document.querySelector('button[value="draft"]').click();
    } else {
        window.location.href = "{{ route('admin.list-kuesioner') }}";
    }
}

// Init with one empty question
tambahPertanyaan();
</script>
@endsection