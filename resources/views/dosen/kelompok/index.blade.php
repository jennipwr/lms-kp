@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="card-title mb-1">Kelompok Hasil Generate ML</h4>
                    <p class="text-muted mb-0">
                        Pilih kelas untuk melihat hasil kelompok belajar dan kelompok tugas.
                    </p>
                </div>

                <a href="{{ route('dosen.lihat-kelas') }}" class="btn btn-outline-primary">
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
                    Belum ada kelas yang terdaftar untuk dosen ini.
                </div>
            @else
                <div class="row">
                    @foreach ($kelasList as $kelas)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body d-flex flex-column">

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="fw-semibold mb-1">
                                                {{ $kelas->nama_kelas ?? 'Nama kelas tidak tersedia' }}
                                            </h5>

                                            @if ($kelas->total_kelompok > 0)
                                                <span class="badge bg-success">
                                                    Sudah Generate
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    Belum Generate
                                                </span>
                                            @endif
                                        </div>

                                        @if (!empty($kelas->kode_kelas))
                                            <p class="text-muted mb-0">
                                                Kode: {{ $kelas->kode_kelas }}
                                            </p>
                                        @endif
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

                                    <div class="mt-auto d-grid gap-2">
                                        <a href="{{ route('dosen.kelompok.show', $kelas->id_kelas) }}"
                                           class="btn btn-primary">
                                            <i class="ti ti-users-group me-1"></i>
                                            Lihat Kelompok
                                        </a>

                                        @if ($kelas->total_kelompok <= 0)
                                            <form action="{{ route('dosen.kelompok.proses', $kelas->id_kelas) }}"
                                                  method="POST">
                                                @csrf

                                                <input type="hidden" name="group_size" value="5">

                                                <button type="submit" class="btn btn-outline-success w-100">
                                                    <i class="ti ti-sparkles me-1"></i>
                                                    Generate Kelompok
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('dosen.kelompok.proses', $kelas->id_kelas) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Generate ulang kelompok untuk kelas ini? Data kelompok lama akan diganti.')">
                                                @csrf

                                                <input type="hidden" name="group_size" value="5">

                                                <button type="submit" class="btn btn-outline-warning w-100">
                                                    <i class="ti ti-refresh me-1"></i>
                                                    Generate Ulang
                                                </button>
                                            </form>
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

</div>
@endsection