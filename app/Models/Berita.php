<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Berita extends Model
{
    protected $primaryKey = 'id_berita';
    protected $fillable = ['judul_berita', 'gambar', 'tanggal_berita', 'status_berita'];

    /**
     * Get the paragraphs associated with the news.
     *
     * @return HasMany
     */
    public function paragraf(): HasMany
    {
        return $this->hasMany(Paragraft::class, 'id_berita', 'id_berita');
    }

    /**
     * Get the detail associated with the news.
     *
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(Detail::class, 'id_berita', 'id_berita');
    }

    /**
     * Get the comments associated with the news.
     *
     * @return HasMany
     */
    public function komentar(): HasMany
    {
        return $this->hasMany(KomentarBerita::class, 'id_berita', 'id_berita');
    }
    
    public function getIdKategoriAttribute()
    {
        return $this->detail->id_kategori;
    }
}
