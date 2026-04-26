<?php

namespace App\Http\Controllers;

use App\Models\HasilKuesioner;
use App\Models\JawabanKuesioner;
use App\Models\ListKuesioner;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class HasilKuesionerController extends Controller
{
    /**
     * Tampilkan daftar semua hasil kuesioner (ringkasan per mahasiswa).
     */
    public function index(Request $request)
    {
        $listKuesioner = ListKuesioner::all();

        $query = HasilKuesioner::with(['mahasiswa.user', 'listKuesioner']);

        if ($request->filled('id_list')) {
            $query->where('id_list', $request->id_list);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa.user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhere('mahasiswa_nrp', 'like', "%{$search}%");
        }

        $hasil = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistik ringkasan
        $stats = $this->getStatistik($request->id_list);

        return view('admin.hasil_kuesioner', compact('hasil', 'listKuesioner', 'stats'));
    }

    /**
     * Detail hasil kuesioner satu mahasiswa beserta jawaban lengkap.
     */
    public function show($id)
    {
        $hasil = HasilKuesioner::with([
            'mahasiswa.user',
            'listKuesioner',
        ])->findOrFail($id);

        // Ambil semua jawaban mahasiswa untuk kuesioner ini
        $jawaban = JawabanKuesioner::with('kuesioner')
            ->where('mahasiswa_nrp', $hasil->mahasiswa_nrp)
            ->whereHas('kuesioner', fn($q) => $q->where('id_list', $hasil->id_list))
            ->get()
            ->groupBy('kuesioner.dimensi');

        $ringkasan = $hasil->getRingkasanDimensi();

        return view('admin.show_hasil', compact('hasil', 'jawaban', 'ringkasan'));
    }

    /**
     * Halaman grafik & analitik agregat semua mahasiswa.
     */
    public function grafik(Request $request)
    {
        $listKuesioner = ListKuesioner::all();
        $idList = $request->id_list ?? optional($listKuesioner->first())->id_list;

        $data = HasilKuesioner::where('id_list', $idList)->get();

        // Distribusi kutub tiap dimensi
        $distribusi = [
            'active_reflective' => $this->distribusiKutub($data, 'hasil_active_reflective'),
            'sensing_intuitive' => $this->distribusiKutub($data, 'hasil_sensing_intuitive'),
            'visual_verbal'     => $this->distribusiKutub($data, 'hasil_visual_verbal'),
            'sequential_global' => $this->distribusiKutub($data, 'hasil_sequential_global'),
        ];

        // Distribusi kategori (Mild / Moderate / Strong)
        $kategori = [
            'active_reflective' => $this->distribusiKategori($data, 'kategori_active_reflective'),
            'sensing_intuitive' => $this->distribusiKategori($data, 'kategori_sensing_intuitive'),
            'visual_verbal'     => $this->distribusiKategori($data, 'kategori_visual_verbal'),
            'sequential_global' => $this->distribusiKategori($data, 'kategori_sequential_global'),
        ];

        // Rata-rata skor absolut tiap dimensi
        $avgSkor = [
            'active_reflective' => round($data->avg(fn($d) => abs($d->skor_active_reflective)), 2),
            'sensing_intuitive' => round($data->avg(fn($d) => abs($d->skor_sensing_intuitive)), 2),
            'visual_verbal'     => round($data->avg(fn($d) => abs($d->skor_visual_verbal)), 2),
            'sequential_global' => round($data->avg(fn($d) => abs($d->skor_sequential_global)), 2),
        ];

        return view('admin.grafik_hasil', compact(
            'listKuesioner', 'idList', 'distribusi', 'kategori', 'avgSkor', 'data'
        ));
    }

    // ── Private Helpers ──────────────────────────────────────────────────────

    private function distribusiKutub($data, string $kolom): array
    {
        return $data->groupBy($kolom)
            ->map(fn($g) => $g->count())
            ->toArray();
    }

    private function distribusiKategori($data, string $kolom): array
    {
        return $data->groupBy($kolom)
            ->map(fn($g) => $g->count())
            ->toArray();
    }

    private function getStatistik(?int $idList): array
    {
        $query = HasilKuesioner::query();
        if ($idList) $query->where('id_list', $idList);

        $total = $query->count();

        return [
            'total'              => $total,
            'active'             => $query->where('hasil_active_reflective', 'active')->count(),
            'reflective'         => $query->where('hasil_active_reflective', 'reflective')->count(),
            'sensing'            => $query->where('hasil_sensing_intuitive', 'sensing')->count(),
            'intuitive'          => $query->where('hasil_sensing_intuitive', 'intuitive')->count(),
            'visual'             => $query->where('hasil_visual_verbal', 'visual')->count(),
            'verbal'             => $query->where('hasil_visual_verbal', 'verbal')->count(),
            'sequential'         => $query->where('hasil_sequential_global', 'sequential')->count(),
            'global'             => $query->where('hasil_sequential_global', 'global')->count(),
        ];
    }
}