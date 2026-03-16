<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBerita extends Model
{
    protected $table = 'kategori_berita';

    protected $fillable = ['nama_kategori', 'slug'];

    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class, 'kategori_id');
    }
}
