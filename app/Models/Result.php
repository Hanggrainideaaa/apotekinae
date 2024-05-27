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

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }
    public function users()
    {
        return $this->belongsTo(users::class, 'user_id');
    }
    public function apotek()
    {
        return $this->belongsTo(apotek::class, 'apotek_id');
    }
}
