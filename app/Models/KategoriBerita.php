<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBerita extends Model
{
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['nama_kategori'];

    public function idDetail()
    {
        return $this->hasMany(Detail::class, 'id_kategori', 'id_kategori');
    }
    
}
