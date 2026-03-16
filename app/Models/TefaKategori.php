<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TefaKategori extends Model
{
    protected $table = 'tefa_kategori';

    protected $fillable = ['nama_kategori', 'slug'];

    public function produk(): HasMany
    {
        return $this->hasMany(TefaProduk::class, 'kategori_id');
    }
}
