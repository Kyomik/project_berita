<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'logins';
    // protected $primaryKey = 'username';
    public $incrementing = false;
    protected $fillable = ['username', 'password', 'id_user'];
    protected $hidden = ['password'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
