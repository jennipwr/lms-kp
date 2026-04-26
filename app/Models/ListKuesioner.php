<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListKuesioner extends Model
{
    use HasFactory;

    protected $table = 'list_kuesioner';
    protected $primaryKey = 'id_list';
    public $timestamps = true; // created_at & updated_at

    protected $fillable = [
        'nama_kuesioner',
        'status',
    ];

    // Relasi ke pertanyaan (kuesioner)
    public function pertanyaan()
    {
        return $this->hasMany(Kuesioner::class, 'id_list', 'id_list');
    }
}