<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuesioner extends Model
{
    use HasFactory;

    protected $table = 'kuesioner';
    protected $primaryKey = 'id_kuesioner';
    public $timestamps = true;

    protected $fillable = [
        'dimensi',
        'pertanyaan',
        'opsi_a',
        'kutub_a',
        'opsi_b',
        'kutub_b',
        'id_list',
    ];

    // Relasi ke list_kuesioner
    public function listKuesioner()
    {
        return $this->belongsTo(ListKuesioner::class, 'id_list', 'id_list');
    }

    // Relasi ke jawaban mahasiswa
    public function jawaban()
    {
        return $this->hasMany(JawabanKuesioner::class, 'kuesioner_id', 'id_kuesioner');
    }
}