<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class users extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'apikey',
        'token_verifications'
    ];

    public function roles()
    {
        return $this->belongsTo(roles::class, 'role_id');
    }
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function apotek()
    {
        return $this->hasOne(apotek::class, 'user_id');
    }
}
