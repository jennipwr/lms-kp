@extends('layouts.index')

@section('content')
<div class="tes-hero">
    <div class="container">
        <div class="badge-tag">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm.5 11.5h-1v-5h1v5zm0-6.5h-1V4h1v1z"/></svg>
            Felder–Silverman Learning Style
        </div>
        <h1>Tes Gaya Belajar</h1>
        <p>Kenali gaya belajarmu dan tingkatkan efektivitas studi melalui model Felder-Silverman.</p>
    </div>
</div>

<div class="section-wrap">
    @if($kuesioners->isEmpty())
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p>Belum ada kuesioner yang tersedia saat ini.</p>
        </div>
    @else
        @foreach($kuesioners as $k)
        @php $sudahIsi = isset($sudahIsiMap[$k->id_list]) && $sudahIsiMap[$k->id_list]; @endphp
        <a href="{{ $sudahIsi ? route('mahasiswa.tes-hasil', $k->id_list) : route('mahasiswa.tes-show', $k->id_list) }}"
           class="k-card {{ $sudahIsi ? 'done' : '' }}">
            <div class="k-card-inner">
                <div class="k-icon">
                    @if($sudahIsi)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    @endif
                </div>
                <div>
                    <div class="k-title">{{ $k->nama_kuesioner }}</div>
                    <div class="k-meta">
                        @if($sudahIsi)
                            <span class="status-dot" style="background:var(--mcu-green)"></span>Sudah diisi — Lihat Hasil
                        @else
                            <span class="status-dot" style="background:var(--mcu-gold);animation:none"></span>Belum diisi — Klik untuk mulai
                        @endif
                    </div>
                </div>
                <div class="k-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    @endif
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

    .tes-hero {
        background: linear-gradient(135deg, var(--itb-navy) 0%, var(--itb-navy-mid) 45%, var(--mcu-blue) 100%);
        padding: 56px 0 72px;
        position: relative;
        overflow: hidden;
    }
    .tes-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: rgba(201,168,76,.12);
    }
    .tes-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -40px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .tes-hero .badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(201,168,76,.18);
        border: 1px solid rgba(201,168,76,.4);
        color: var(--mcu-gold);
        font-family: 'DM Sans', sans-serif;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 20px;
        margin-bottom: 18px;
    }
    .tes-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.6rem;
        color: #fff;
        margin-bottom: 12px;
        line-height: 1.2;
    }
    .tes-hero p {
        font-family: 'DM Sans', sans-serif;
        color: rgba(255,255,255,.7);
        font-size: 1rem;
        max-width: 520px;
    }

    .section-wrap {
        max-width: 800px;
        margin: -36px auto 60px;
        padding: 0 24px;
        position: relative;
        z-index: 10;
    }

    .k-card {
        background: var(--white);
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 24px rgba(13,31,60,.08);
        overflow: hidden;
        transition: box-shadow .25s, transform .25s;
        text-decoration: none;
        display: block;
        margin-bottom: 16px;
    }
    .k-card:hover {
        box-shadow: 0 12px 40px rgba(21,101,192,.18);
        transform: translateY(-3px);
        text-decoration: none;
    }
    .k-card-inner {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 24px 28px;
    }
    .k-icon {
        width: 54px; height: 54px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--itb-navy-mid), var(--mcu-blue));
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .k-icon svg { color: #fff; width: 26px; height: 26px; }
    .k-title {
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--text-main);
        margin-bottom: 4px;
    }
    .k-meta {
        font-family: 'DM Sans', sans-serif;
        font-size: .82rem;
        color: var(--text-muted);
    }
    .k-arrow {
        margin-left: auto;
        color: var(--mcu-blue);
        flex-shrink: 0;
    }
    .k-card.done .k-icon {
        background: linear-gradient(135deg, var(--mcu-green), #43A047);
    }
    .k-card.done .k-arrow { color: var(--mcu-green); }
    .status-dot {
        display: inline-block;
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--mcu-green);
        margin-right: 5px;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%,100% { opacity: 1; }
        50% { opacity: .4; }
    }
    .empty-state {
        background: var(--white);
        border-radius: 16px;
        border: 1px solid var(--border);
        padding: 60px 32px;
        text-align: center;
    }
    .empty-state svg { color: var(--border); margin-bottom: 16px; }
    .empty-state p { font-family: 'DM Sans', sans-serif; color: var(--text-muted); }
</style>
@endsection