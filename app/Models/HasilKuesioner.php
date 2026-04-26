<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKuesioner extends Model
{
    protected $table      = 'hasil_kuesioner';
    protected $primaryKey = 'id_hasil';

    protected $fillable = [
        'mahasiswa_nrp',
        'id_list',

        // Skor net tiap dimensi
        'skor_active_reflective',
        'skor_sensing_intuitive',
        'skor_visual_verbal',
        'skor_sequential_global',

        // Kutub pemenang tiap dimensi
        'hasil_active_reflective',
        'hasil_sensing_intuitive',
        'hasil_visual_verbal',
        'hasil_sequential_global',

        // Kategori kecenderungan
        'kategori_active_reflective',
        'kategori_sensing_intuitive',
        'kategori_visual_verbal',
        'kategori_sequential_global',
    ];

    // ── Relasi ──────────────────────────────────────────

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nrp', 'nrp');
    }

    public function listKuesioner()
    {
        return $this->belongsTo(ListKuesioner::class, 'id_list', 'id_list');
    }

    // ── Helper: ambil semua dimensi sebagai array terstruktur ──

    /**
     * Kembalikan array ringkasan hasil semua dimensi.
     * Berguna untuk ditampilkan di view tanpa logic di blade.
     *
     * @return array
     */
    public function getRingkasanDimensi(): array
    {
        return [
            'active_reflective' => [
                'label'     => 'Active / Reflective',
                'skor'      => $this->skor_active_reflective,
                'hasil'     => $this->hasil_active_reflective,
                'kategori'  => $this->kategori_active_reflective,
                'left'      => 'active',
                'right'     => 'reflective',
            ],
            'sensing_intuitive' => [
                'label'     => 'Sensing / Intuitive',
                'skor'      => $this->skor_sensing_intuitive,
                'hasil'     => $this->hasil_sensing_intuitive,
                'kategori'  => $this->kategori_sensing_intuitive,
                'left'      => 'sensing',
                'right'     => 'intuitive',
            ],
            'visual_verbal' => [
                'label'     => 'Visual / Verbal',
                'skor'      => $this->skor_visual_verbal,
                'hasil'     => $this->hasil_visual_verbal,
                'kategori'  => $this->kategori_visual_verbal,
                'left'      => 'visual',
                'right'     => 'verbal',
            ],
            'sequential_global' => [
                'label'     => 'Sequential / Global',
                'skor'      => $this->skor_sequential_global,
                'hasil'     => $this->hasil_sequential_global,
                'kategori'  => $this->kategori_sequential_global,
                'left'      => 'sequential',
                'right'     => 'global',
            ],
        ];
    }
}