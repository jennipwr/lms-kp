@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-1">Kelompok Saya</h4>
                <p class="text-muted mb-0">
                    Pilih kelas untuk melihat kelompok belajar dan kelompok tugas hasil generate ML.
                </p>
            </div>

            <a href="{{ route('mahasiswa.lihat-kelas') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i>
                Kembali ke Kelas
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($kelasList->isEmpty())
            <div class="alert alert-warning mb-0">
                Kamu belum terdaftar di kelas mana pun.
            </div>
        @else
            <div class="row">
                @foreach ($kelasList as $kelas)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border shadow-sm h-100">
                            <div class="card-body d-flex flex-column">

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <h5 class="fw-semibold mb-1">
                                            {{ $kelas->nama_kelas ?? 'Nama kelas tidak tersedia' }}
                                        </h5>

                                        @if ($kelas->total_kelompok > 0)
                                            <span class="badge bg-success">
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Belum Ada
                                            </span>
                                        @endif
                                    </div>

                                    @if (!empty($kelas->kode_kelas))
                                        <p class="text-muted mb-0">
                                            <i class="ti ti-code me-1"></i>
                                            Kode: {{ $kelas->kode_kelas }}
                                        </p>
                                    @endif

                                    <p class="text-muted mb-0">
                                        <i class="ti ti-user me-1"></i>
                                        Dosen: {{ $kelas->dosen->user->nama ?? '-' }}
                                    </p>
                                </div>

                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <h6 class="mb-0">{{ $kelas->total_kelompok }}</h6>
                                            <small class="text-muted">Total</small>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <h6 class="mb-0">{{ $kelas->total_kelompok_homogen }}</h6>
                                            <small class="text-muted">Belajar</small>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <h6 class="mb-0">{{ $kelas->total_kelompok_heterogen }}</h6>
                                            <small class="text-muted">Tugas</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto d-grid">
                                    @if ($kelas->total_kelompok > 0)
                                        <a href="{{ route('mahasiswa.kelompok.show', $kelas->id_kelas) }}"
                                           class="btn btn-primary">
                                            <i class="ti ti-users-group me-1"></i>
                                            Lihat Kelompok Saya
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-secondary" disabled>
                                            <i class="ti ti-clock me-1"></i>
                                            Belum Digenerate
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

@endsection