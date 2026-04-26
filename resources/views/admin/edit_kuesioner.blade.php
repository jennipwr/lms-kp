@extends('layouts.index')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <a href="{{ route('admin.list-kuesioner') }}" class="back-btn" title="Kembali ke Daftar">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <h2 class="page-title">Edit: {{ $list->nama_kuesioner }}</h2>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.update-kuesioner', $list->id_list) }}" method="POST">
        @csrf

        {{-- Informasi Kuesioner --}}
        <div class="card">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Informasi Kuesioner
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nama Kuesioner</label>
                    <input type="text" name="nama_kuesioner" class="form-control" value="{{ $list->nama_kuesioner }}" required>
                </div>
                <input type="hidden" name="status" value="{{ $list->status }}">
            </div>
        </div>

        {{-- Pertanyaan yang sudah ada --}}
        <div class="card">
            <div class="card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Daftar Pertanyaan
            </div>
            <div class="card-body">

                <div id="pertanyaan-existing">
                    @foreach($pertanyaan as $index => $p)
                    <div class="pertanyaan-item">
                        <input type="hidden" name="id_kuesioner[]" value="{{ $p->id_kuesioner }}">

                        <div class="pertanyaan-header">
                            <span class="pertanyaan-label">Pertanyaan {{ $index + 1 }}</span>
                            <div class="pertanyaan-actions">
                                <!-- Hapus pertanyaan: gunakan JS untuk membuat form DELETE agar tidak menempel di dalam form utama -->
                                <button type="button" class="delete-form-btn" onclick="confirmDeletePertanyaan({{ $p->id_kuesioner }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pertanyaan</label>
                            <input type="text" name="pertanyaan[]" class="form-control" value="{{ $p->pertanyaan }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Dimensi</label>
                            <select name="dimensi[]" class="form-select">
                                <option value="profil"            @if($p->dimensi=='profil')            selected @endif>Profil</option>
                                <option value="active_reflective" @if($p->dimensi=='active_reflective') selected @endif>Active / Reflective</option>
                                <option value="sensing_intuitive" @if($p->dimensi=='sensing_intuitive') selected @endif>Sensing / Intuitive</option>
                                <option value="visual_verbal"     @if($p->dimensi=='visual_verbal')     selected @endif>Visual / Verbal</option>
                                <option value="sequential_global" @if($p->dimensi=='sequential_global') selected @endif>Sequential / Global</option>
                            </select>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Kutub A</label>
                                <select name="kutub_a[]" class="form-select">
                                    <option value="active"     @if($p->kutub_a=='active')     selected @endif>Active</option>
                                    <option value="reflective" @if($p->kutub_a=='reflective') selected @endif>Reflective</option>
                                    <option value="sensing"    @if($p->kutub_a=='sensing')    selected @endif>Sensing</option>
                                    <option value="intuitive"  @if($p->kutub_a=='intuitive')  selected @endif>Intuitive</option>
                                    <option value="visual"     @if($p->kutub_a=='visual')     selected @endif>Visual</option>
                                    <option value="verbal"     @if($p->kutub_a=='verbal')     selected @endif>Verbal</option>
                                    <option value="sequential" @if($p->kutub_a=='sequential') selected @endif>Sequential</option>
                                    <option value="global"     @if($p->kutub_a=='global')     selected @endif>Global</option>
                                    <option value="profil"     @if($p->kutub_a=='profil')     selected @endif>Profil</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kutub B</label>
                                <select name="kutub_b[]" class="form-select">
                                    <option value="active"     @if($p->kutub_b=='active')     selected @endif>Active</option>
                                    <option value="reflective" @if($p->kutub_b=='reflective') selected @endif>Reflective</option>
                                    <option value="sensing"    @if($p->kutub_b=='sensing')    selected @endif>Sensing</option>
                                    <option value="intuitive"  @if($p->kutub_b=='intuitive')  selected @endif>Intuitive</option>
                                    <option value="visual"     @if($p->kutub_b=='visual')     selected @endif>Visual</option>
                                    <option value="verbal"     @if($p->kutub_b=='verbal')     selected @endif>Verbal</option>
                                    <option value="sequential" @if($p->kutub_b=='sequential') selected @endif>Sequential</option>
                                    <option value="global"     @if($p->kutub_b=='global')     selected @endif>Global</option>
                                    <option value="profil"     @if($p->kutub_b=='profil')     selected @endif>Profil</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Opsi A</label>
                                <input type="text" name="opsi_a[]" class="form-control" value="{{ $p->opsi_a }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Opsi B</label>
                                <input type="text" name="opsi_b[]" class="form-control" value="{{ $p->opsi_b }}" required>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Container untuk pertanyaan baru --}}
                <div id="pertanyaan-new-container"></div>

                <button type="button" class="btn btn-add" onclick="tambahPertanyaan()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Pertanyaan Baru
                </button>

                <div class="form-actions">
                    <button type="submit" class="btn btn-update">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.list-kuesioner') }}" class="btn btn-cancel">Batal</a>
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
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    .form-row-2 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1rem;
    }

    .pertanyaan-item {
        background: var(--bg-light);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        position: relative;
        transition: border-color 0.15s ease;
    }

    .pertanyaan-item:hover {
        border-color: #b0c4e8;
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

    .pertanyaan-actions {
        display: flex;
        gap: 0.5rem;
    }

    .remove-new-btn {
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

    .remove-new-btn:hover {
        background: var(--danger);
        color: var(--white);
        border-color: var(--danger);
    }

    .delete-form-btn {
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

    .delete-form-btn:hover {
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

    .btn-update {
        background: linear-gradient(135deg, var(--mcu-blue), var(--itb-navy-mid));
        color: var(--white);
        box-shadow: 0 3px 10px rgba(21, 101, 192, 0.3);
    }

    .btn-update:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 16px rgba(21, 101, 192, 0.4);
    }

    .btn-cancel {
        background: transparent;
        color: var(--text-muted);
        border: 1.5px solid var(--border);
        margin-left: auto;
    }

    .btn-cancel:hover {
        background: #f5f5f5;
        color: var(--text-main);
    }

    .alert-success {
        background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
        border: 1px solid #a5d6a7;
        border-left: 4px solid var(--mcu-green);
        color: var(--mcu-green);
        padding: 0.8rem 1.2rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    @media (max-width: 640px) {
        .form-row, .form-row-2 {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-cancel {
            margin-left: 0;
        }
    }
</style>
@endsection

@section('ExtraJS')
<script>
let newCount = 0;

const dimensiOptions = [
    { value: 'profil',             label: 'Profil' },
    { value: 'active_reflective',  label: 'Active / Reflective' },
    { value: 'sensing_intuitive',  label: 'Sensing / Intuitive' },
    { value: 'visual_verbal',      label: 'Visual / Verbal' },
    { value: 'sequential_global',  label: 'Sequential / Global' },
];

function buildDimensiOptions(selected = '') {
    return dimensiOptions.map(o => `<option value="${o.value}" ${selected === o.value ? 'selected' : ''}>${o.label}</option>`).join('');
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
    return kutubOptions.map(o => `<option value="${o.value}" ${selected === o.value ? 'selected' : ''}>${o.label}</option>`).join('');
}

function tambahPertanyaan() {
    newCount++;
    const container = document.getElementById('pertanyaan-new-container');
    const existingCount = document.querySelectorAll('#pertanyaan-existing .pertanyaan-item').length;
    const totalNum = existingCount + newCount;

    const html = `
    <div class="pertanyaan-item" id="pnew-${newCount}" style="border-color: #b0c4e8; background: #f0f4ff;">
        <div class="pertanyaan-header">
            <span class="pertanyaan-label" style="color: var(--mcu-blue);">Pertanyaan Baru ${newCount}</span>
            <button type="button" class="remove-new-btn" onclick="hapusPertanyaanBaru('pnew-${newCount}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Hapus
            </button>
        </div>

        <div class="form-group">
            <label class="form-label">Pertanyaan</label>
            <input type="text" name="pertanyaan[]" class="form-control" placeholder="Tuliskan pertanyaan baru..." required>
        </div>

        <div class="form-group">
            <label class="form-label">Dimensi</label>
            <select name="dimensi[]" class="form-select">
                ${buildDimensiOptions()}
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Opsi A</label>
                <input type="text" name="opsi_a[]" class="form-control" placeholder="Pilihan A" required>
                <label class="form-label" style="margin-top:0.5rem; font-size:0.8rem;">Kutub A</label>
                <select name="kutub_a[]" class="form-select">${buildKutubOptions()}</select>
            </div>
            <div class="form-group">
                <label class="form-label">Opsi B</label>
                <input type="text" name="opsi_b[]" class="form-control" placeholder="Pilihan B" required>
                <label class="form-label" style="margin-top:0.5rem; font-size:0.8rem;">Kutub B</label>
                <select name="kutub_b[]" class="form-select">${buildKutubOptions()}</select>
            </div>
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);
}

function hapusPertanyaanBaru(id) {
    document.getElementById(id)?.remove();
}

// Confirm and submit DELETE for existing pertanyaan (avoid nested forms)
function confirmDeletePertanyaan(id) {
    if (!confirm('Hapus pertanyaan ini?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('/admin/kuesioner') }}/${id}/delete`;
    form.style.display = 'none';
    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = '{{ csrf_token() }}';
    form.appendChild(token);
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection