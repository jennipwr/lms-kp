<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanKuesioner extends Model
{
    use HasFactory;

    protected $table = 'jawaban_kuesioner';
    protected $primaryKey = 'id_jawaban';
    public $timestamps = true;

    protected $fillable = [
        'jawaban',
        'mahasiswa_nrp',
        'kuesioner_id',
        'kutub',
    ];

    // Relasi ke kuesioner
    public function kuesioner()
    {
        return $this->belongsTo(Kuesioner::class, 'kuesioner_id', 'id_kuesioner');
    }

    // Relasi ke mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nrp', 'nrp');
    }
}