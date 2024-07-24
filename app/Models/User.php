<?php



namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $primaryKey = 'id_user';
    protected $fillable = ['nama_user', 'hak_akses', 'refresh_token'];
    protected $hidden = ['password'];
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function login()
    {
        return $this->hasOne(Login::class, 'id_user', 'id_user');
    }

    public function komentar()
    {
        return $this->hasMany(KomentarBerita::class, 'id_user', 'id_user');
    }

    public function idDetail()
    {
        return $this->hasMany(Detail::class, 'id_user', 'id_user');
    }
}
