<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'kelompok_mahasiswa';

    protected $fillable = [
        'kelompok_id',
        'mahasiswa_nrp',
        'cluster_id',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id_kelompok');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nrp', 'nrp');
    }
}
