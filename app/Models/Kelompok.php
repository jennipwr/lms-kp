<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table      = 'kelompok';
    protected $primaryKey = 'id_kelompok';

    protected $fillable = [
        'kelas_id',
        'nama_kelompok',
        'tipe',            // 'homogen' | 'heterogen'
        'cluster_profile', // label interpretasi cluster dari ML
    ];

    // ── Relasi ──────────────────────────────────────────

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function kelompokMahasiswa()
    {
        return $this->hasMany(KelompokMahasiswa::class, 'kelompok_id', 'id_kelompok')
                    ->with('mahasiswa.user');
    }
}
