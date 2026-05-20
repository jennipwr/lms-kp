@extends('layouts.index')

@section('content')

@php
    $isAdmin = auth()->user()->role?->nama_role === 'admin';
    $prosesRoute = $isAdmin
        ? route('admin.kelompok.proses', $kelas->id_kelas)
        : route('dosen.kelompok.proses', $kelas->id_kelas);
    $resetRoute  = $isAdmin
        ? route('admin.kelompok.reset', $kelas->id_kelas)
        : route('dosen.kelompok.reset', $kelas->id_kelas);
    $backRoute   = $isAdmin ? route('admin.kelompok.index') : route('dosen.lihat-kelas');
@endphp

<style>
.klp-wrapper { padding: 1.5rem 2rem; }
.klp-breadcrumb { display:flex; align-items:center; gap:.5rem; font-size:.85rem; color:#64748b; margin-bottom:1.25rem; }
.klp-breadcrumb a { color:#6366f1; text-decoration:none; }
.klp-hero { background:linear-gradient(135deg,#6366f1,#818cf8); border-radius:.75rem; padding:1.5rem; color:#fff; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
.klp-hero h2 { margin:0; font-size:1.4rem; }
.klp-hero p  { margin:.3rem 0 0; opacity:.85; font-size:.85rem; }
.klp-actions { display:flex; gap:.75rem; flex-wrap:wrap; }
.btn-proses { background:#fff; color:#6366f1; border:none; padding:.6rem 1.3rem; border-radius:.5rem; font-weight:600; font-size:.85rem; cursor:pointer; display:flex; align-items:center; gap:.4rem; }
.btn-reset  { background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.4); padding:.6rem 1.3rem; border-radius:.5rem; font-weight:600; font-size:.85rem; cursor:pointer; display:flex; align-items:center; gap:.4rem; }
.btn-proses:hover { background:#f1f5f9; }
.btn-reset:hover  { background:rgba(255,255,255,.25); }
.klp-tabs { display:flex; border-bottom:2px solid #e2e8f0; margin-bottom:1.5rem; gap:.25rem; }
.klp-tab  { padding:.6rem 1.2rem; background:none; border:none; font-size:.875rem; color:#64748b; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-2px; font-weight:500; border-radius:.4rem .4rem 0 0; transition:.15s; }
.klp-tab.active { color:#6366f1; border-bottom-color:#6366f1; background:#f5f3ff; font-weight:600; }
.klp-tab:hover:not(.active) { background:#f8fafc; }
.klp-panel { display:none; }
.klp-panel.active { display:block; }
.klp-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:1rem; }
.klp-card { background:#fff; border:1px solid #e2e8f0; border-radius:.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.05); }
.klp-card-header { padding:.75rem 1rem; display:flex; align-items:center; justify-content:space-between; }
.klp-card-header.homogen   { background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-bottom:1px solid #bbf7d0; }
.klp-card-header.heterogen { background:linear-gradient(135deg,#eff6ff,#dbeafe); border-bottom:1px solid #bfdbfe; }
.klp-card-title   { font-weight:700; font-size:.9rem; }
.klp-card-title.homogen   { color:#166534; }
.klp-card-title.heterogen { color:#1e40af; }
.klp-badge { font-size:.72rem; padding:.2rem .5rem; border-radius:999px; font-weight:600; }
.klp-badge.homogen   { background:#bbf7d0; color:#14532d; }
.klp-badge.heterogen { background:#bfdbfe; color:#1e3a8a; }
.klp-member-list { padding:.5rem .75rem; }
.klp-member { display:flex; align-items:center; gap:.6rem; padding:.45rem .25rem; border-bottom:1px dashed #f1f5f9; }
.klp-member:last-child { border-bottom:none; }
.klp-avatar { width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:700; color:#fff; flex-shrink:0; }
.klp-member-info small { display:block; font-size:.72rem; color:#94a3b8; }
.klp-profile { font-size:.72rem; padding:.15rem .5rem; border-radius:999px; background:#f1f5f9; color:#475569; margin-top:.35rem; display:inline-block; }
.alert-success { background:#f0fdf4; border:1px solid #86efac; color:#166534; padding:.75rem 1rem; border-radius:.5rem; margin-bottom:1rem; }
.alert-error   { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:.75rem 1rem; border-radius:.5rem; margin-bottom:1rem; }
.empty-state { text-align:center; padding:3rem; color:#94a3b8; }
.empty-state i { font-size:3rem; display:block; margin-bottom:.75rem; }
.cluster-dot { display:inline-block; width:10px; height:10px; border-radius:50%; margin-right:.25rem; }
</style>

@php
$clusterColors = ['#6366f1','#f59e0b','#10b981','#ef4444'];
@endphp

<div class="klp-wrapper">

    {{-- Breadcrumb --}}
    <div class="klp-breadcrumb">
        <a href="{{ $backRoute }}"><i class="bi bi-collection-fill"></i> Kelas</a>
        <i class="bi bi-chevron-right"></i>
        <span>Kelompok — {{ $kelas->nama_kelas }}</span>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    {{-- Hero --}}
    <div class="klp-hero">
        <div>
            <h2>{{ $kelas->nama_kelas }}</h2>
            <p>
                <i class="bi bi-hash"></i> {{ $kelas->kode_kelas }}
                &nbsp;•&nbsp;
                <i class="bi bi-person-badge"></i> {{ $kelas->dosen?->user?->nama ?? '-' }}
                &nbsp;•&nbsp;
                <i class="bi bi-people"></i> {{ $kelas->kelasMahasiswa->count() }} Mahasiswa
            </p>
        </div>

        <div class="klp-actions">
            {{-- Form Proses --}}
            <form method="POST" action="{{ $prosesRoute }}" id="formProses" style="display:flex; align-items:center; gap:.5rem;">
                @csrf
                <select name="group_size" style="padding:.5rem .75rem; border:1px solid rgba(255,255,255,.4); border-radius:.5rem; background:rgba(255,255,255,.15); color:#fff; font-size:.82rem;">
                    <option value="3" style="color:#000;">3 per kelompok</option>
                    <option value="4" style="color:#000;">4 per kelompok</option>
                    <option value="5" selected style="color:#000;">5 per kelompok</option>
                    <option value="6" style="color:#000;">6 per kelompok</option>
                </select>
                <button type="submit" class="btn-proses"
                    onclick="return confirm('{{ $sudahDikelompokkan ? 'Kelompok lama akan dihapus dan dibuat ulang. Lanjutkan?' : 'Mulai proses pengelompokan ML?' }}')">
                    <i class="bi bi-cpu-fill"></i>
                    {{ $sudahDikelompokkan ? 'Kelompokkan Ulang' : 'Proses ML' }}
                </button>
            </form>

            {{-- Reset (hanya kalau sudah ada kelompok) --}}
            @if($sudahDikelompokkan)
                <form method="POST" action="{{ $resetRoute }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-reset"
                        onclick="return confirm('Hapus semua kelompok untuk kelas ini?')">
                        <i class="bi bi-trash3"></i> Reset
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(!$sudahDikelompokkan)
        {{-- Belum dikelompokkan --}}
        <div class="empty-state" style="background:#fff; border:1px solid #e2e8f0; border-radius:.75rem;">
            <i class="bi bi-diagram-3" style="color:#cbd5e1;"></i>
            <p style="font-size:1rem; font-weight:600; color:#475569;">Belum Ada Kelompok</p>
            <p style="font-size:.85rem;">Klik tombol <strong>Proses ML</strong> untuk mengelompokkan mahasiswa<br>berdasarkan hasil kuesioner gaya belajar Felder-Silverman.</p>
        </div>
    @else
        {{-- Tabs --}}
        <div class="klp-tabs">
            <button class="klp-tab active" onclick="switchKlpTab('homogen', this)">
                <i class="bi bi-people-fill"></i>
                Kelompok Belajar (Homogen)
                <span style="background:#d1fae5; color:#065f46; border-radius:999px; padding:.1rem .5rem; font-size:.75rem; margin-left:.25rem;">
                    {{ $homogenGroups->count() }}
                </span>
            </button>
            <button class="klp-tab" onclick="switchKlpTab('heterogen', this)">
                <i class="bi bi-diagram-3-fill"></i>
                Kelompok Tugas (Heterogen)
                <span style="background:#dbeafe; color:#1e40af; border-radius:999px; padding:.1rem .5rem; font-size:.75rem; margin-left:.25rem;">
                    {{ $heterogenGroups->count() }}
                </span>
            </button>
        </div>

        {{-- Panel Homogen --}}
        <div id="klp-panel-homogen" class="klp-panel active">
            <p style="font-size:.82rem; color:#64748b; margin-bottom:1rem;">
                <i class="bi bi-info-circle"></i>
                Mahasiswa dengan <strong>gaya belajar serupa</strong> dikelompokkan bersama untuk sesi belajar.
            </p>
            <div class="klp-grid">
                @foreach($homogenGroups as $kelompok)
                    <div class="klp-card">
                        <div class="klp-card-header homogen">
                            <div>
                                <div class="klp-card-title homogen">
                                    <i class="bi bi-people-fill"></i>
                                    {{ $kelompok->nama_kelompok }}
                                </div>
                                @if($kelompok->cluster_profile)
                                    <small style="color:#16a34a; font-size:.72rem;">{{ $kelompok->cluster_profile }}</small>
                                @endif
                            </div>
                            <span class="klp-badge homogen">
                                {{ $kelompok->kelompokMahasiswa->count() }} orang
                            </span>
                        </div>
                        <div class="klp-member-list">
                            @foreach($kelompok->kelompokMahasiswa as $km)
                                @php $color = $clusterColors[$km->cluster_id ?? 0] ?? '#6366f1'; @endphp
                                <div class="klp-member">
                                    <div class="klp-avatar" style="background:{{ $color }};">
                                        {{ strtoupper(substr($km->mahasiswa?->user?->nama ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size:.85rem; font-weight:500; color:#1e293b;">
                                            {{ $km->mahasiswa?->user?->nama ?? $km->mahasiswa_nrp }}
                                        </div>
                                        <small>{{ $km->mahasiswa_nrp }}</small>
                                    </div>
                                    @if($km->cluster_id !== null)
                                        <span style="margin-left:auto; font-size:.7rem; background:#f1f5f9; color:#475569; padding:.1rem .4rem; border-radius:999px;">
                                            C{{ $km->cluster_id }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Panel Heterogen --}}
        <div id="klp-panel-heterogen" class="klp-panel">
            <p style="font-size:.82rem; color:#64748b; margin-bottom:1rem;">
                <i class="bi bi-info-circle"></i>
                Mahasiswa dengan <strong>gaya belajar beragam</strong> dikelompokkan bersama untuk mengerjakan tugas.
            </p>
            <div class="klp-grid">
                @foreach($heterogenGroups as $kelompok)
                    <div class="klp-card">
                        <div class="klp-card-header heterogen">
                            <div class="klp-card-title heterogen">
                                <i class="bi bi-diagram-3-fill"></i>
                                {{ $kelompok->nama_kelompok }}
                            </div>
                            <span class="klp-badge heterogen">
                                {{ $kelompok->kelompokMahasiswa->count() }} orang
                            </span>
                        </div>
                        <div class="klp-member-list">
                            @foreach($kelompok->kelompokMahasiswa as $km)
                                @php $color = $clusterColors[$km->cluster_id ?? 0] ?? '#6366f1'; @endphp
                                <div class="klp-member">
                                    <div class="klp-avatar" style="background:{{ $color }};">
                                        {{ strtoupper(substr($km->mahasiswa?->user?->nama ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-size:.85rem; font-weight:500; color:#1e293b;">
                                            {{ $km->mahasiswa?->user?->nama ?? $km->mahasiswa_nrp }}
                                        </div>
                                        <small>{{ $km->mahasiswa_nrp }}</small>
                                    </div>
                                    @if($km->cluster_id !== null)
                                        <span style="margin-left:auto; font-size:.7rem; background:#f1f5f9; color:#475569; padding:.1rem .4rem; border-radius:999px;">
                                            C{{ $km->cluster_id }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @endif
</div>

<script>
function switchKlpTab(name, btn) {
    document.querySelectorAll('.klp-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.klp-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('klp-panel-' + name).classList.add('active');
}
</script>

@endsection
