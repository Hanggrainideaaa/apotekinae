<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apotek extends Model
{
    use HasFactory;
    protected $table = 'apotek';
    protected $fillable = [
        'name',
        'pharmacy_license_number',
        'pharmacy_license_file',
        'pharmacits_practice_license',
        'pharmacy_address',
        'latitut',
        'longitut',
        'isVerified',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(users::class, 'user_id');
    }
}
