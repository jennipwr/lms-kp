<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Kelas;
use App\Models\Kelompok;
use App\Models\KelompokMahasiswa;
use App\Models\HasilKuesioner;
use App\Models\KelasMahasiswa;

class KelompokController extends Controller
{
    /**
     * URL Flask ML API
     * Set di .env:
     * ML_API_URL=http://127.0.0.1:5000
     */
    private function mlUrl(): string
    {
        return rtrim(env('ML_API_URL', 'http://127.0.0.1:5000'), '/');
    }

    // =========================================================================
    // ADMIN — Daftar semua kelas untuk lihat/generate kelompok ML
    // =========================================================================

    public function adminIndex()
    {
        $kelasList = Kelas::with([
            'dosen.user',
            'kelompok.kelompokMahasiswa.mahasiswa.user',
        ])
        ->orderBy('nama_kelas')
        ->get();

        return view('admin.kelompok.index', compact('kelasList'));
    }

    // =========================================================================
    // ADMIN — Detail kelompok satu kelas
    // =========================================================================

    public function show($kelas_id)
    {
        $kelas = Kelas::with([
            'dosen.user',
            'kelompok' => function ($q) {
                $q->orderBy('tipe')->orderBy('nama_kelompok');
            },
            'kelompok.kelompokMahasiswa.mahasiswa.user',
        ])->findOrFail($kelas_id);

        $homogenGroups = $kelas->kelompok->where('tipe', 'homogen');
        $heterogenGroups = $kelas->kelompok->where('tipe', 'heterogen');
        $sudahDikelompokkan = $kelas->kelompok->isNotEmpty();

        return view('admin.kelompok.show', compact(
            'kelas',
            'homogenGroups',
            'heterogenGroups',
            'sudahDikelompokkan'
        ));
    }

    // =========================================================================
    // DOSEN — Daftar kelas milik dosen untuk lihat/generate kelompok ML
    // =========================================================================

    public function index()
    {
        $user = auth()->user();
        $dosen = $user->dosen;

        abort_if(!$dosen, 403, 'Data dosen tidak ditemukan.');

        $kelasList = Kelas::withCount([
            'kelompok as total_kelompok',
            'kelompok as total_kelompok_homogen' => function ($q) {
                $q->where('tipe', 'homogen');
            },
            'kelompok as total_kelompok_heterogen' => function ($q) {
                $q->where('tipe', 'heterogen');
            },
        ])
        ->where('dosen_nik', $dosen->nik)
        ->orderBy('nama_kelas')
        ->get();

        return view('dosen.kelompok.index', compact('kelasList', 'dosen'));
    }

    // =========================================================================
    // DOSEN — Detail kelompok satu kelas miliknya
    // =========================================================================

    public function dosenShow($kelas_id)
    {
        $user = auth()->user();
        $dosen = $user->dosen;

        abort_if(!$dosen, 403, 'Data dosen tidak ditemukan.');

        $kelas = Kelas::with([
            'dosen.user',
            'kelompok' => function ($q) {
                $q->orderBy('tipe')->orderBy('nama_kelompok');
            },
            'kelompok.kelompokMahasiswa.mahasiswa.user',
        ])
        ->where('dosen_nik', $dosen->nik)
        ->findOrFail($kelas_id);

        $homogenGroups = $kelas->kelompok->where('tipe', 'homogen');
        $heterogenGroups = $kelas->kelompok->where('tipe', 'heterogen');
        $sudahDikelompokkan = $kelas->kelompok->isNotEmpty();

        return view('dosen.kelompok.show', compact(
            'kelas',
            'homogenGroups',
            'heterogenGroups',
            'sudahDikelompokkan',
            'dosen'
        ));
    }

    // =========================================================================
    // MAHASISWA — Daftar kelas mahasiswa untuk lihat kelompok ML
    // =========================================================================

    public function mahasiswaIndex()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        abort_if(!$mahasiswa, 403, 'Data mahasiswa tidak ditemukan.');

        $kelasList = Kelas::with([
            'dosen.user',
        ])
        ->withCount([
            'kelompok as total_kelompok',
            'kelompok as total_kelompok_homogen' => function ($q) {
                $q->where('tipe', 'homogen');
            },
            'kelompok as total_kelompok_heterogen' => function ($q) {
                $q->where('tipe', 'heterogen');
            },
        ])
        ->whereHas('kelasMahasiswa', function ($q) use ($mahasiswa) {
            $q->where('mahasiswa_nrp', $mahasiswa->nrp);
        })
        ->orderBy('nama_kelas')
        ->get();

        return view('mahasiswa.kelompok.index', compact('kelasList', 'mahasiswa'));
    }

    // =========================================================================
    // MAHASISWA — Lihat kelompok miliknya sendiri di satu kelas
    // =========================================================================

    public function mahasiswaShow($kelas_id)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        abort_if(!$mahasiswa, 403, 'Data mahasiswa tidak ditemukan.');

        $terdaftar = KelasMahasiswa::where('kelas_id', $kelas_id)
            ->where('mahasiswa_nrp', $mahasiswa->nrp)
            ->exists();

        abort_unless($terdaftar, 403, 'Anda tidak terdaftar di kelas ini.');

        $kelas = Kelas::with('dosen.user')->findOrFail($kelas_id);

        $kelompokBelajar = KelompokMahasiswa::with([
            'kelompok.kelompokMahasiswa.mahasiswa.user',
        ])
        ->where('mahasiswa_nrp', $mahasiswa->nrp)
        ->whereHas('kelompok', function ($q) use ($kelas_id) {
            $q->where('kelas_id', $kelas_id)
              ->where('tipe', 'homogen');
        })
        ->first()?->kelompok;

        $kelompokTugas = KelompokMahasiswa::with([
            'kelompok.kelompokMahasiswa.mahasiswa.user',
        ])
        ->where('mahasiswa_nrp', $mahasiswa->nrp)
        ->whereHas('kelompok', function ($q) use ($kelas_id) {
            $q->where('kelas_id', $kelas_id)
              ->where('tipe', 'heterogen');
        })
        ->first()?->kelompok;

        $sudahDikelompokkan = $kelompokBelajar || $kelompokTugas;

        return view('mahasiswa.kelompok.show', compact(
            'kelas',
            'kelompokBelajar',
            'kelompokTugas',
            'sudahDikelompokkan',
            'mahasiswa'
        ));
    }

    // =========================================================================
    // PROSES CLUSTERING — Dipanggil Admin/Dosen
    // =========================================================================

    public function proses(Request $request, $kelas_id)
    {
        $kelas = Kelas::with('kelasMahasiswa.mahasiswa.user')->findOrFail($kelas_id);

        if (auth()->user()->role_id === 2) {
            $dosen = auth()->user()->dosen;

            abort_if(!$dosen, 403, 'Data dosen tidak ditemukan.');

            abort_unless(
                $kelas->dosen_nik === $dosen->nik,
                403,
                'Anda tidak berhak generate kelompok untuk kelas ini.'
            );
        }

        $kelasMahasiswaList = $kelas->kelasMahasiswa;

        if ($kelasMahasiswaList->isEmpty()) {
            return back()->with('error', 'Tidak ada mahasiswa di kelas ini.');
        }

        $mahasiswaPayload = [];

        foreach ($kelasMahasiswaList as $km) {
            $nrp = $km->mahasiswa_nrp;
            $nama = $km->mahasiswa?->user?->nama ?? $nrp;

            $hasil = HasilKuesioner::where('mahasiswa_nrp', $nrp)
                ->latest()
                ->first();

            if (!$hasil) {
                continue;
            }

            $mahasiswaPayload[] = [
                'nrp'                    => $nrp,
                'nama'                   => $nama,
                'skor_active_reflective' => (int) $hasil->skor_active_reflective,
                'skor_sensing_intuitive' => (int) $hasil->skor_sensing_intuitive,
                'skor_visual_verbal'     => (int) $hasil->skor_visual_verbal,
                'skor_sequential_global' => (int) $hasil->skor_sequential_global,
            ];
        }

        if (count($mahasiswaPayload) < 2) {
            return back()->with('error', 'Minimal 2 mahasiswa sudah mengisi kuesioner untuk dilakukan pengelompokan.');
        }

        $groupSize = (int) $request->input('group_size', 5);

        if ($groupSize < 2) {
            $groupSize = 2;
        }

        try {
            $response = Http::timeout(60)->post($this->mlUrl() . '/cluster', [
                'kelas_id'   => $kelas_id,
                'group_size' => $groupSize,
                'mahasiswa'  => $mahasiswaPayload,
            ]);
        } catch (\Exception $e) {
            Log::error('ML API tidak bisa dihubungi: ' . $e->getMessage());

            return back()->with(
                'error',
                'ML API tidak bisa dihubungi. Pastikan server Python sudah berjalan di ' . $this->mlUrl()
            );
        }

        if (!$response->successful()) {
            $msg = $response->json('message') ?? 'Respons tidak dikenal dari ML API.';

            return back()->with('error', 'ML API error: ' . $msg);
        }

        $result = $response->json();

        DB::transaction(function () use ($kelas_id, $result) {

            $oldIds = Kelompok::where('kelas_id', $kelas_id)->pluck('id_kelompok');

            if ($oldIds->isNotEmpty()) {
                KelompokMahasiswa::whereIn('kelompok_id', $oldIds)->delete();
                Kelompok::where('kelas_id', $kelas_id)->delete();
            }

            $clusterMap = [];

            foreach ($result['cluster_info'] ?? [] as $ci) {
                if (isset($ci['nrp'])) {
                    $clusterMap[$ci['nrp']] = $ci;
                }
            }

            foreach ($result['homogen'] ?? [] as $namaKelompok => $anggota) {
                $anggota = collect($anggota)->values();

                if ($anggota->isEmpty()) {
                    continue;
                }

                $firstNrp = $anggota[0]['nrp'] ?? null;

                $kelompok = Kelompok::create([
                    'kelas_id'        => $kelas_id,
                    'nama_kelompok'   => ucwords(str_replace('_', ' ', $namaKelompok)),
                    'tipe'            => 'homogen',
                    'cluster_profile' => $firstNrp ? ($clusterMap[$firstNrp]['cluster_profile'] ?? null) : null,
                ]);

                foreach ($anggota as $mhs) {
                    if (!isset($mhs['nrp'])) {
                        continue;
                    }

                    KelompokMahasiswa::create([
                        'kelompok_id'   => $kelompok->id_kelompok,
                        'mahasiswa_nrp' => $mhs['nrp'],
                        'cluster_id'    => $clusterMap[$mhs['nrp']]['cluster'] ?? null,
                    ]);
                }
            }

            foreach ($result['heterogen'] ?? [] as $namaKelompok => $anggota) {
                $anggota = collect($anggota)->values();

                if ($anggota->isEmpty()) {
                    continue;
                }

                $kelompok = Kelompok::create([
                    'kelas_id'        => $kelas_id,
                    'nama_kelompok'   => ucwords(str_replace('_', ' ', $namaKelompok)),
                    'tipe'            => 'heterogen',
                    'cluster_profile' => null,
                ]);

                foreach ($anggota as $mhs) {
                    if (!isset($mhs['nrp'])) {
                        continue;
                    }

                    KelompokMahasiswa::create([
                        'kelompok_id'   => $kelompok->id_kelompok,
                        'mahasiswa_nrp' => $mhs['nrp'],
                        'cluster_id'    => $clusterMap[$mhs['nrp']]['cluster'] ?? null,
                    ]);
                }
            }
        });

        if (auth()->user()->role_id === 1) {
            $route = route('admin.kelompok.show', $kelas_id);
        } else {
            $route = route('dosen.kelompok.show', $kelas_id);
        }

        return redirect($route)->with(
            'success',
            'Pengelompokan berhasil! ' .
            count($result['homogen'] ?? []) . ' kelompok belajar & ' .
            count($result['heterogen'] ?? []) . ' kelompok tugas dibuat.'
        );
    }

    // =========================================================================
    // RESET KELOMPOK — Admin/Dosen
    // =========================================================================

    public function reset($kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);

        if (auth()->user()->role_id === 2) {
            $dosen = auth()->user()->dosen;

            abort_if(!$dosen, 403, 'Data dosen tidak ditemukan.');

            abort_unless(
                $kelas->dosen_nik === $dosen->nik,
                403,
                'Anda tidak berhak reset kelompok untuk kelas ini.'
            );
        }

        DB::transaction(function () use ($kelas_id) {
            $ids = Kelompok::where('kelas_id', $kelas_id)->pluck('id_kelompok');

            if ($ids->isNotEmpty()) {
                KelompokMahasiswa::whereIn('kelompok_id', $ids)->delete();
                Kelompok::where('kelas_id', $kelas_id)->delete();
            }
        });

        return back()->with('success', 'Kelompok berhasil direset.');
    }
}