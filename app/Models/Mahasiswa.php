<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $primaryKey = 'nrp';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nrp',
        'users_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function kelasMahasiswa()
    {
        return $this->hasMany(KelasMahasiswa::class, 'mahasiswa_nrp', 'nrp');
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanKuesioner::class, 'mahasiswa_nrp', 'nrp');
    }
}