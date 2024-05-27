<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $table = 'requests';
    protected $fillable = [
        'image',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(users::class, 'user_id');
    }
}
