<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paragraft extends Model
{
    public $incrementing = true; // Tidak menggunakan incrementing untuk primary key

    protected $fillable = ['id_berita', 'isi_paragraft', 'status_paragraft', 'id_paragraft'];

    public function berita()
    {
        return $this->belongsTo(Berita::class, 'id_berita', 'id_berita');
    }
}
