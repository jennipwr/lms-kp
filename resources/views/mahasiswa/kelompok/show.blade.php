@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-1">Detail Kelompok Saya</h4>
                <p class="text-muted mb-0">
                    Kelas: <strong>{{ $kelas->nama_kelas ?? '-' }}</strong>
                </p>
                <p class="text-muted mb-0">
                    Dosen: <strong>{{ $kelas->dosen->user->nama ?? '-' }}</strong>
                </p>
            </div>

            <a href="{{ route('mahasiswa.kelompok.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
            </a>
        </div>

        @if (!$sudahDikelompokkan)
            <div class="alert alert-warning mb-0">
                Kelompok untuk kelas ini belum tersedia. Silakan tunggu admin atau dosen melakukan generate kelompok.
            </div>
        @else

            <div class="row">

                <div class="col-lg-6 mb-4">
                    <div class="card border shadow-sm h-100">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-semibold mb-1">
                                        Kelompok Belajar
                                    </h5>
                                    <p class="text-muted mb-0">
                                        Kelompok homogen berdasarkan kemiripan gaya belajar.
                                    </p>
                                </div>

                                <span class="badge bg-primary">
                                    Homogen
                                </span>
                            </div>

                            @if ($kelompokBelajar)
                                <div class="mb-3">
                                    <h6 class="fw-semibold mb-1">
                                        {{ $kelompokBelajar->nama_kelompok }}
                                    </h6>

                                    @if ($kelompokBelajar->cluster_profile)
                                        <p class="text-muted mb-0">
                                            Profil: {{ $kelompokBelajar->cluster_profile }}
                                        </p>
                                    @endif
                                </div>

                                <ul class="list-group list-group-flush">
                                    @foreach ($kelompokBelajar->kelompokMahasiswa as $anggota)
                                        @php
                                            $isMe = $anggota->mahasiswa_nrp === $mahasiswa->nrp;
                                        @endphp

                                        <li class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-semibold">
                                                        {{ $anggota->mahasiswa->user->nama ?? $anggota->mahasiswa_nrp }}

                                                        @if ($isMe)
                                                            <span class="badge bg-info ms-1">Saya</span>
                                                        @endif
                                                    </div>

                                                    <small class="text-muted">
                                                        NRP: {{ $anggota->mahasiswa_nrp }}
                                                        @if (!is_null($anggota->cluster_id))
                                                            | Cluster: {{ $anggota->cluster_id }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-light border mb-0">
                                    Kamu belum masuk kelompok belajar.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card border shadow-sm h-100">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-semibold mb-1">
                                        Kelompok Tugas
                                    </h5>
                                    <p class="text-muted mb-0">
                                        Kelompok heterogen berdasarkan variasi gaya belajar.
                                    </p>
                                </div>

                                <span class="badge bg-success">
                                    Heterogen
                                </span>
                            </div>

                            @if ($kelompokTugas)
                                <div class="mb-3">
                                    <h6 class="fw-semibold mb-1">
                                        {{ $kelompokTugas->nama_kelompok }}
                                    </h6>
                                </div>

                                <ul class="list-group list-group-flush">
                                    @foreach ($kelompokTugas->kelompokMahasiswa as $anggota)
                                        @php
                                            $isMe = $anggota->mahasiswa_nrp === $mahasiswa->nrp;
                                        @endphp

                                        <li class="list-group-item px-0">
                                            <div class="fw-semibold">
                                                {{ $anggota->mahasiswa->user->nama ?? $anggota->mahasiswa_nrp }}

                                                @if ($isMe)
                                                    <span class="badge bg-info ms-1">Saya</span>
                                                @endif
                                            </div>

                                            <small class="text-muted">
                                                NRP: {{ $anggota->mahasiswa_nrp }}
                                                @if (!is_null($anggota->cluster_id))
                                                    | Cluster: {{ $anggota->cluster_id }}
                                                @endif
                                            </small>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-light border mb-0">
                                    Kamu belum masuk kelompok tugas.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>

            <div class="alert alert-info mb-0">
                <strong>Catatan:</strong> Kelompok belajar biasanya berisi mahasiswa dengan gaya belajar yang mirip,
                sedangkan kelompok tugas dibuat lebih bervariasi agar anggota kelompok saling melengkapi.
            </div>

        @endif

    </div>
</div>

@endsection