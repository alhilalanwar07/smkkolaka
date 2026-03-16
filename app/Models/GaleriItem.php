<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriItem extends Model
{
    protected $table = 'galeri_item';

    protected $fillable = [
        'album_id',
        'tipe_file',
        'file_path',
        'caption',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(GaleriAlbum::class, 'album_id');
    }
}
