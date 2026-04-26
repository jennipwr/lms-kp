<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $primaryKey = 'nik'; // primary key bukan auto-increment
    public $incrementing = false;   // karena nik manual
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'users_id',
    ];

    // relasi one-to-one ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}