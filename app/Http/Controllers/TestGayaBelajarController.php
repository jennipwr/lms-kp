<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListKuesioner;
use App\Models\Kuesioner;
use App\Models\JawabanKuesioner;
use App\Models\HasilKuesioner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestGayaBelajarController extends Controller
{
    /**
     * Definisi 4 dimensi Felder-Silverman.
     * Key     = nilai kolom 'dimensi' di tabel kuesioner (DB)
     * left    = nilai kutub kiri (DB)
     * right   = nilai kutub kanan (DB)
     * kolom   = nama kolom di tabel hasil_kuesioner
     */
    private array $dimensiMap = [
        'active_reflective' => [
            'left'  => 'active',
            'right' => 'reflective',
            'kolom' => 'active_reflective',
        ],
        'sensing_intuitive' => [
            'left'  => 'sensing',
            'right' => 'intuitive',
            'kolom' => 'sensing_intuitive',
        ],
        'visual_verbal' => [
            'left'  => 'visual',
            'right' => 'verbal',
            'kolom' => 'visual_verbal',
        ],
        'sequential_global' => [
            'left'  => 'sequential',
            'right' => 'global',
            'kolom' => 'sequential_global',
        ],
    ];

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Hitung kategori dari nilai skor absolut.
     * Threshold standar Felder-Silverman:
     *   1–3  = Seimbang
     *   5–7  = Moderat
     *   9–11 = Kuat
     */
    private function hitungKategori(int $absNet): string
    {
        if ($absNet <= 3) return 'Seimbang';
        if ($absNet <= 7) return 'Moderat';
        return 'Kuat';
    }

    /**
     * Hitung skor semua dimensi dari koleksi jawaban.
     * Kembalikan array siap pakai untuk insert ke hasil_kuesioner.
     */
    private function hitungSkor($jawaban): array
    {
        $akumulasi = [];
        foreach ($jawaban as $j) {
            $dim   = $j->kuesioner->dimensi ?? '';
            $kutub = $j->kutub ?? '';
            if ($dim === '' || $kutub === '' || !isset($this->dimensiMap[$dim])) continue;

            $akumulasi[$dim][$kutub] = ($akumulasi[$dim][$kutub] ?? 0) + 1;
        }

        $hasil = [];
        foreach ($this->dimensiMap as $dim => $cfg) {
            $left  = $akumulasi[$dim][$cfg['left']]  ?? 0;
            $right = $akumulasi[$dim][$cfg['right']] ?? 0;
            $net   = $left - $right;
            $abs   = abs($net);

            $kolom = $cfg['kolom'];
            $hasil["skor_{$kolom}"]     = $net;
            $hasil["hasil_{$kolom}"]    = $net >= 0 ? $cfg['left'] : $cfg['right'];
            $hasil["kategori_{$kolom}"] = $this->hitungKategori($abs);
        }

        return $hasil;
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Daftar kuesioner yang tersedia.
     */
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $nrp       = $mahasiswa?->nrp;

        $kuesioners  = ListKuesioner::where('status', 'published')->get();
        $sudahIsiMap = [];

        if ($nrp) {
            foreach ($kuesioners as $k) {
                $sudahIsiMap[$k->id_list] = HasilKuesioner::where('mahasiswa_nrp', $nrp)
                    ->where('id_list', $k->id_list)
                    ->exists();
            }
        }

        return view('mahasiswa.list_kuesioner', compact('kuesioners', 'sudahIsiMap'));
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Tampilkan form kuesioner.
     * Jika sudah pernah mengisi -> redirect ke hasil.
     */
    public function show($list_id)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'NRP mahasiswa belum terdaftar!');
        }

        $sudahIsi = HasilKuesioner::where('mahasiswa_nrp', $mahasiswa->nrp)
            ->where('id_list', $list_id)
            ->exists();

        if ($sudahIsi) {
            return redirect()->route('mahasiswa.tes-hasil', $list_id)
                ->with('info', 'Kamu sudah mengisi kuesioner ini. Berikut adalah hasil kuesionermu.');
        }

        $pertanyaan  = Kuesioner::where('id_list', $list_id)->get();
        $jawabanUser = [];

        return view('mahasiswa.show_kuesioner', compact('pertanyaan', 'jawabanUser', 'list_id'));
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Simpan jawaban & hitung skor -> insert ke hasil_kuesioner.
     * Hanya bisa dilakukan sekali per mahasiswa per kuesioner.
     */
    public function submit(Request $request, $list_id)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'NRP mahasiswa belum terdaftar!');
        }

        $nrp = $mahasiswa->nrp;

        // Proteksi ganda: cek tabel hasil
        $sudahIsi = HasilKuesioner::where('mahasiswa_nrp', $nrp)
            ->where('id_list', $list_id)
            ->exists();

        if ($sudahIsi) {
            return redirect()->route('mahasiswa.tes-hasil', $list_id)
                ->with('info', 'Kamu sudah mengisi kuesioner ini sebelumnya.');
        }

        $pertanyaan = Kuesioner::where('id_list', $list_id)->get();
        $data       = $request->input('jawaban'); // [id_kuesioner => 'A'/'B']

        DB::transaction(function () use ($nrp, $list_id, $pertanyaan, $data) {

            // 1. Simpan semua jawaban ke jawaban_kuesioner
            $jawabanDisimpan = collect();

            foreach ($data as $pertanyaan_id => $jawaban) {
                $soal  = $pertanyaan->firstWhere('id_kuesioner', $pertanyaan_id);
                $kutub = null;

                if ($soal) {
                    $kutub = strtoupper($jawaban) === 'A' ? $soal->kutub_a : $soal->kutub_b;
                }

                $record = JawabanKuesioner::create([
                    'mahasiswa_nrp' => $nrp,
                    'kuesioner_id'  => $pertanyaan_id,
                    'jawaban'       => $jawaban,
                    'kutub'         => $kutub,
                ]);

                // Set relasi manual agar hitungSkor bisa akses $j->kuesioner->dimensi
                $record->setRelation('kuesioner', $soal);
                $jawabanDisimpan->push($record);
            }

            // 2. Hitung skor dari jawaban yang baru disimpan
            $skorData = $this->hitungSkor($jawabanDisimpan);

            // 3. Simpan hasil ke tabel hasil_kuesioner
            HasilKuesioner::create(array_merge([
                'mahasiswa_nrp' => $nrp,
                'id_list'       => $list_id,
            ], $skorData));
        });

        return redirect()->route('mahasiswa.tes-hasil', $list_id)
            ->with('success', 'Jawaban berhasil disimpan! Berikut adalah hasil kuesionermu.');
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Tampilkan halaman hasil dari tabel hasil_kuesioner.
     * Data sudah tersimpan, tidak perlu hitung ulang.
     */
    public function hasil($list_id)
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'NRP mahasiswa belum terdaftar!');
        }

        $hasil = HasilKuesioner::where('mahasiswa_nrp', $mahasiswa->nrp)
            ->where('id_list', $list_id)
            ->first();

        if (!$hasil) {
            return redirect()->route('mahasiswa.tes-show', $list_id)
                ->with('info', 'Silakan isi kuesioner terlebih dahulu.');
        }

        // getRingkasanDimensi() dari model mengembalikan array terstruktur
        $dimensiMap = $hasil->getRingkasanDimensi();

        return view('mahasiswa.hasil_kuesioner', compact('hasil', 'dimensiMap'));
    }
}