<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan penamaan konvensi
    protected $table = 'drafts';

    // Tentukan kolom yang dapat diisi massal
    protected $fillable = [
        'id_berita',
        'judul_berita',
        'gambar',
        'id_kategori'
    ];

    // Tentukan kolom yang tidak boleh diisi
    protected $guarded = [];

    // Jika Anda menggunakan timestamps
    public $timestamps = true;
}
