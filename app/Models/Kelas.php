<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = ['nama_kelas', 'kode_kelas', 'kelas_label','dosen_nik', 'join_token'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_nik', 'nik');
    }

    public function kelasMahasiswa()
    {
        return $this->hasMany(KelasMahasiswa::class, 'kelas_id', 'id_kelas');
    }
}