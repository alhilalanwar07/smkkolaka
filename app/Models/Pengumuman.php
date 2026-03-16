<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'user_id',
        'judul_pengumuman',
        'slug',
        'isi_pengumuman',
        'file_lampiran_path',
        'tanggal_mulai_tampil',
        'tanggal_akhir_tampil',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai_tampil' => 'date',
            'tanggal_akhir_tampil' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('tanggal_mulai_tampil', '<=', now())
                     ->where('tanggal_akhir_tampil', '>=', now());
    }
}
