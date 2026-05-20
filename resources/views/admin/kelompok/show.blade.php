@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-1">Detail Kelompok Hasil ML</h4>
                <p class="text-muted mb-0">
                    Kelas: <strong>{{ $kelas->nama_kelas ?? '-' }}</strong>
                </p>
                <p class="text-muted mb-0">
                    Dosen: <strong>{{ $kelas->dosen->user->nama ?? '-' }}</strong>
                </p>
            </div>

            <a href="{{ route('admin.kelompok.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i>
                Kembali
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

        @if (!$sudahDikelompokkan)
            <div class="alert alert-warning">
                Kelompok untuk kelas ini belum digenerate.
            </div>

            <form action="{{ route('admin.kelompok.proses', $kelas->id_kelas) }}" method="POST" class="mb-0">
                @csrf

                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="group_size" class="form-label">Jumlah anggota per kelompok</label>
                        <input type="number"
                               name="group_size"
                               id="group_size"
                               class="form-control"
                               value="5"
                               min="2">
                    </div>

                    <div class="col-md-4 mt-3 mt-md-0">
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-sparkles me-1"></i>
                            Generate Kelompok
                        </button>
                    </div>
                </div>
            </form>
        @else

            <div class="d-flex flex-wrap gap-2 mb-4">
                <form action="{{ route('admin.kelompok.proses', $kelas->id_kelas) }}"
                      method="POST"
                      onsubmit="return confirm('Generate ulang kelompok? Data lama akan diganti.')">
                    @csrf

                    <input type="hidden" name="group_size" value="5">

                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-refresh me-1"></i>
                        Generate Ulang
                    </button>
                </form>

                <form action="{{ route('admin.kelompok.reset', $kelas->id_kelas) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin mau reset semua kelompok kelas ini?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>
                        Reset Kelompok
                    </button>
                </form>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border">
                        <div class="card-body text-center">
                            <h4 class="mb-0">{{ $kelas->kelompok->count() }}</h4>
                            <small class="text-muted">Total Kelompok</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border">
                        <div class="card-body text-center">
                            <h4 class="mb-0">{{ $homogenGroups->count() }}</h4>
                            <small class="text-muted">Kelompok Belajar</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border">
                        <div class="card-body text-center">
                            <h4 class="mb-0">{{ $heterogenGroups->count() }}</h4>
                            <small class="text-muted">Kelompok Tugas</small>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mb-3">
                <i class="ti ti-users me-1"></i>
                Kelompok Belajar / Homogen
            </h5>

            <div class="row">
                @forelse ($homogenGroups as $kelompok)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border shadow-sm h-100">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-semibold mb-0">
                                        {{ $kelompok->nama_kelompok }}
                                    </h6>

                                    <span class="badge bg-primary">
                                        {{ $kelompok->kelompokMahasiswa->count() }} anggota
                                    </span>
                                </div>

                                @if ($kelompok->cluster_profile)
                                    <p class="text-muted mb-3">
                                        Profil: {{ $kelompok->cluster_profile }}
                                    </p>
                                @endif

                                <ul class="list-group list-group-flush">
                                    @foreach ($kelompok->kelompokMahasiswa as $anggota)
                                        <li class="list-group-item px-0">
                                            <div class="fw-semibold">
                                                {{ $anggota->mahasiswa->user->nama ?? $anggota->mahasiswa_nrp }}
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

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border">
                            Belum ada kelompok homogen.
                        </div>
                    </div>
                @endforelse
            </div>

            <hr class="my-4">

            <h5 class="mb-3">
                <i class="ti ti-users-group me-1"></i>
                Kelompok Tugas / Heterogen
            </h5>

            <div class="row">
                @forelse ($heterogenGroups as $kelompok)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border shadow-sm h-100">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-semibold mb-0">
                                        {{ $kelompok->nama_kelompok }}
                                    </h6>

                                    <span class="badge bg-success">
                                        {{ $kelompok->kelompokMahasiswa->count() }} anggota
                                    </span>
                                </div>

                                <ul class="list-group list-group-flush">
                                    @foreach ($kelompok->kelompokMahasiswa as $anggota)
                                        <li class="list-group-item px-0">
                                            <div class="fw-semibold">
                                                {{ $anggota->mahasiswa->user->nama ?? $anggota->mahasiswa_nrp }}
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

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border">
                            Belum ada kelompok heterogen.
                        </div>
                    </div>
                @endforelse
            </div>

        @endif

    </div>
</div>

@endsection