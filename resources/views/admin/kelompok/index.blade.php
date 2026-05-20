@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-1">Kelompok Hasil Generate ML</h4>
                <p class="text-muted mb-0">
                    Daftar semua kelas dan status kelompok hasil generate machine learning.
                </p>
            </div>

            <a href="{{ route('admin.list-kelas') }}" class="btn btn-outline-primary">
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
                Belum ada data kelas.
            </div>
        @else
            <div class="row">
                @foreach ($kelasList as $kelas)
                    @php
                        $totalKelompok = $kelas->kelompok->count();
                        $totalHomogen = $kelas->kelompok->where('tipe', 'homogen')->count();
                        $totalHeterogen = $kelas->kelompok->where('tipe', 'heterogen')->count();
                    @endphp

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border shadow-sm h-100">
                            <div class="card-body d-flex flex-column">

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <h5 class="fw-semibold mb-1">
                                            {{ $kelas->nama_kelas ?? 'Nama kelas tidak tersedia' }}
                                        </h5>

                                        @if ($totalKelompok > 0)
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
                                            <h6 class="mb-0">{{ $totalKelompok }}</h6>
                                            <small class="text-muted">Total</small>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <h6 class="mb-0">{{ $totalHomogen }}</h6>
                                            <small class="text-muted">Belajar</small>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <h6 class="mb-0">{{ $totalHeterogen }}</h6>
                                            <small class="text-muted">Tugas</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto d-grid gap-2">
                                    <a href="{{ route('admin.kelompok.show', $kelas->id_kelas) }}"
                                       class="btn btn-primary">
                                        <i class="ti ti-users-group me-1"></i>
                                        Lihat Kelompok
                                    </a>

                                    <form action="{{ route('admin.kelompok.proses', $kelas->id_kelas) }}"
                                          method="POST"
                                          onsubmit="return confirm('Generate kelompok untuk kelas ini? Data kelompok lama akan diganti jika sudah ada.')">
                                        @csrf

                                        <input type="hidden" name="group_size" value="5">

                                        <button type="submit"
                                                class="btn {{ $totalKelompok > 0 ? 'btn-outline-warning' : 'btn-outline-success' }} w-100">
                                            @if ($totalKelompok > 0)
                                                <i class="ti ti-refresh me-1"></i>
                                                Generate Ulang
                                            @else
                                                <i class="ti ti-sparkles me-1"></i>
                                                Generate Kelompok
                                            @endif
                                        </button>
                                    </form>

                                    @if ($totalKelompok > 0)
                                        <form action="{{ route('admin.kelompok.reset', $kelas->id_kelas) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin mau reset kelompok kelas ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                <i class="ti ti-trash me-1"></i>
                                                Reset Kelompok
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

@endsection