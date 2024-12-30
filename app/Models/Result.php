<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $table = 'result';
    protected $fillable = [
        'request_id',
        'user_id',
        'apotek_id',
        'price',
        'isAccepted',
        'isTaken',
    ];

// Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel requests (opsional jika diperlukan)
    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }

    // Relasi ke tabel apotek (opsional jika diperlukan)
    public function apotek()
    {
        return $this->belongsTo(Apotek::class, 'apotek_id');
    }
}
