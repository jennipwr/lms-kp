@extends('layouts.index')

@section('content')
<div class="tes-header">
    <div class="container">
        <div class="breadcrumb-nav">
            <a href="{{ route('mahasiswa.tes-index') }}">Daftar Tes</a>
            <span>›</span>
            <span>Pengisian Kuesioner</span>
        </div>
        <h1>Isi Kuesioner Gaya Belajar</h1>
        <p>Pilih jawaban yang paling mencerminkan cara belajarmu. Tidak ada jawaban benar atau salah.</p>
    </div>
    <div class="progress-wrap">
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" id="progressFill" style="width:0%"></div>
        </div>
        <div class="progress-label" id="progressLabel">0 / {{ count($pertanyaan) }} dijawab</div>
    </div>
</div>

<div class="form-wrap">
    @if(session('error'))
        <div class="alert-warn">⚠ {{ session('error') }}</div>
    @endif

    <form action="{{ route('mahasiswa.tes-submit', $list_id) }}" method="POST" id="tesForm">
        @csrf

        @foreach($pertanyaan as $p)
        <div class="q-card" style="animation-delay:{{ $loop->index * 0.06 }}s">
            <!-- <div class="q-dim-badge">{{ $p->dimensi }}</div> -->
            <div class="q-header">
                <span class="q-num">{{ $loop->iteration }}</span>
                <span class="q-text">{{ $p->pertanyaan }}</span>
            </div>
            <div class="option-wrap">
                <label class="option-label">
                    <input type="radio"
                           name="jawaban[{{ $p->id_kuesioner }}]"
                           value="A"
                           class="ans-radio"
                           {{ isset($jawabanUser[$p->id_kuesioner]) && $jawabanUser[$p->id_kuesioner] == 'A' ? 'checked' : '' }}>
                    <div class="radio-visual"></div>
                    <span class="opt-letter">A</span>
                    <span>{{ $p->opsi_a }}</span>
                </label>
                <label class="option-label">
                    <input type="radio"
                           name="jawaban[{{ $p->id_kuesioner }}]"
                           value="B"
                           class="ans-radio"
                           {{ isset($jawabanUser[$p->id_kuesioner]) && $jawabanUser[$p->id_kuesioner] == 'B' ? 'checked' : '' }}>
                    <div class="radio-visual"></div>
                    <span class="opt-letter">B</span>
                    <span>{{ $p->opsi_b }}</span>
                </label>
            </div>
        </div>
        @endforeach

        <div style="height:80px"></div>

        <div class="submit-footer">
            <div class="answered-count">
                Terjawab: <strong id="answeredNum">0</strong> / {{ count($pertanyaan) }}
            </div>
            <button type="submit" class="btn-submit" id="submitBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Kirim Jawaban
            </button>
        </div>
    </form>
</div>
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

    /* ── Header ── */
    .tes-header {
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 50%, var(--mcu-blue) 100%);
        padding: 36px 0 80px;
        position: relative; overflow: hidden;
    }
    .tes-header::before {
        content:''; position:absolute; top:-80px; right:-80px;
        width:320px; height:320px; border-radius:50%;
        background:rgba(201,168,76,.1);
    }
    .breadcrumb-nav {
        font-family:'DM Sans',sans-serif; font-size:.82rem;
        color:rgba(255,255,255,.55); margin-bottom:14px;
    }
    .breadcrumb-nav a { color:rgba(255,255,255,.7); text-decoration:none; }
    .breadcrumb-nav span { margin:0 6px; }
    .tes-header h1 {
        font-family:'Playfair Display',serif;
        font-size:2rem; color:#fff; margin-bottom:8px;
    }
    .tes-header p {
        font-family:'DM Sans',sans-serif;
        color:rgba(255,255,255,.65); font-size:.9rem;
    }

    /* ── Progress bar ── */
    .progress-wrap {
        max-width:860px; margin:0 auto; padding:0 24px;
        position:relative; z-index:5; margin-bottom: -12px;
    }
    .progress-bar-bg {
        height:6px; background:rgba(255,255,255,.15);
        border-radius:10px; overflow:hidden; margin-top:18px;
    }
    .progress-bar-fill {
        height:100%;
        background: linear-gradient(90deg, var(--mcu-gold), #E8C46A);
        border-radius:10px;
        transition: width .4s;
    }
    .progress-label {
        font-family:'DM Sans',sans-serif; font-size:.78rem;
        color:rgba(255,255,255,.6); margin-top:6px; text-align:right;
    }

    /* ── Content wrapper ── */
    .form-wrap {
        max-width:860px; margin:0 auto; padding:32px 24px 80px;
    }

    /* ── Question card ── */
    .q-card {
        background:var(--white);
        border:1px solid var(--border);
        border-radius:16px;
        box-shadow:0 2px 16px rgba(13,31,60,.06);
        padding:28px 32px;
        margin-bottom:20px;
        opacity:0; transform:translateY(18px);
        animation: fadeUp .45s forwards;
    }
    @keyframes fadeUp {
        to { opacity:1; transform:translateY(0); }
    }
    .q-num {
        display:inline-flex; align-items:center; justify-content:center;
        width:30px; height:30px; border-radius:50%;
        background:linear-gradient(135deg,var(--itb-navy-mid),var(--mcu-blue));
        color:#fff; font-family:'DM Sans',sans-serif;
        font-size:.78rem; font-weight:600;
        margin-right:10px; flex-shrink:0;
    }
    .q-header { display:flex; align-items:flex-start; gap:0; margin-bottom:20px; }
    .q-text {
        font-family:'DM Sans',sans-serif;
        font-size:.97rem; font-weight:500;
        color:var(--text-main); line-height:1.55;
    }
    .q-dim-badge {
        display:inline-block;
        background:rgba(21,101,192,.08);
        color:var(--mcu-blue);
        border:1px solid rgba(21,101,192,.2);
        font-family:'DM Sans',sans-serif;
        font-size:.73rem; font-weight:600;
        letter-spacing:.8px; text-transform:uppercase;
        padding:3px 10px; border-radius:20px;
        margin-bottom:14px; margin-left:40px;
    }

    /* ── Radio option ── */
    .option-wrap {
        display:flex; flex-direction:column; gap:10px;
    }
    .option-label {
        display:flex; align-items:center; gap:14px;
        padding:14px 18px;
        border:1.5px solid var(--border);
        border-radius:12px;
        cursor:pointer;
        transition:border-color .2s, background .2s, box-shadow .2s;
        font-family:'DM Sans',sans-serif;
        font-size:.93rem; color:var(--text-main);
    }
    .option-label:hover {
        border-color:var(--mcu-blue);
        background:rgba(21,101,192,.04);
    }
    .option-label input[type="radio"] { display:none; }
    .radio-visual {
        width:20px; height:20px; border-radius:50%;
        border:2px solid var(--border);
        flex-shrink:0; position:relative;
        transition:border-color .2s;
    }
    .option-label:has(input:checked) {
        border-color:var(--mcu-blue);
        background:rgba(21,101,192,.06);
        box-shadow:0 2px 12px rgba(21,101,192,.12);
    }
    .option-label:has(input:checked) .radio-visual {
        border-color:var(--mcu-blue);
        background:var(--mcu-blue);
        box-shadow:0 0 0 4px rgba(21,101,192,.15);
    }
    .option-label:has(input:checked) .radio-visual::after {
        content:''; position:absolute;
        top:50%; left:50%; transform:translate(-50%,-50%);
        width:8px; height:8px; border-radius:50%;
        background:#fff;
    }
    .opt-letter {
        font-weight:700; color:var(--text-muted);
        font-size:.82rem; width:16px; flex-shrink:0;
    }
    .option-label:has(input:checked) .opt-letter { color:var(--mcu-blue); }

    /* ── Sticky footer ── */
    .submit-footer {
        position:fixed; bottom:0; left:0; right:0;
        background:var(--white);
        border-top:1px solid var(--border);
        padding:16px 24px;
        display:flex; align-items:center; justify-content:space-between;
        z-index:100;
        box-shadow:0 -4px 24px rgba(13,31,60,.1);
    }
    .submit-footer .answered-count {
        font-family:'DM Sans',sans-serif;
        font-size:.87rem; color:var(--text-muted);
    }
    .submit-footer .answered-count strong { color:var(--text-main); }
    .btn-submit {
        background:linear-gradient(135deg,var(--itb-navy-mid),var(--mcu-blue));
        color:#fff; border:none;
        font-family:'DM Sans',sans-serif; font-weight:600;
        font-size:.93rem; padding:12px 32px;
        border-radius:10px; cursor:pointer;
        transition:opacity .2s, transform .15s;
        display:flex; align-items:center; gap:8px;
    }
    .btn-submit:hover { opacity:.9; transform:translateY(-1px); }
    .btn-submit:disabled { opacity:.5; cursor:not-allowed; transform:none; }

    /* alert */
    .alert-warn {
        background:rgba(201,168,76,.1); border:1px solid rgba(201,168,76,.35);
        color:#7a5c00; border-radius:10px; padding:14px 18px;
        font-family:'DM Sans',sans-serif; font-size:.87rem; margin-bottom:24px;
        display:flex; align-items:center; gap:10px;
    }
</style>
@endsection

@section('ExtraJS')
<script>
const total = {{ count($pertanyaan) }};
function updateProgress() {
    const checked = document.querySelectorAll('.ans-radio:checked').length;
    document.getElementById('answeredNum').textContent = checked;
    document.getElementById('progressFill').style.width = (checked/total*100) + '%';
    document.getElementById('progressLabel').textContent = checked + ' / ' + total + ' dijawab';
}
document.querySelectorAll('.ans-radio').forEach(r => r.addEventListener('change', updateProgress));
updateProgress();

document.getElementById('tesForm').addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('.ans-radio:checked').length;
    if (checked < total) {
        e.preventDefault();
        alert('Harap jawab semua pertanyaan terlebih dahulu (' + (total - checked) + ' pertanyaan belum dijawab).');
    }
});
</script>
@endsection