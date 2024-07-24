<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail extends Model
{
    protected $table = 'details';
    protected $primaryKey = 'id_berita';
    public $incrementing = false;
    protected $fillable = ['keterangan', 'views', 'id_user', 'id_kategori'];
    protected $hidden = ['id_berita', 'id_user', 'id_kategori'];
    
    /**
     * Get the news that owns the detail.
     *
     * @return BelongsTo
     */
    public function berita(): BelongsTo
    {
        return $this->belongsTo(Berita::class, 'id_berita', 'id_berita');
    }

    /**
     * Get the category that owns the detail.
     *
     * @return BelongsTo
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Get the user that owns the detail.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
