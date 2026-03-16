<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'user_id',
        'nama_kegiatan',
        'slug',
        'deskripsi_kegiatan',
        'lokasi_pelaksanaan',
        'waktu_mulai',
        'waktu_selesai',
        'kategori_peserta',
    ];

    protected function casts(): array
    {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('waktu_mulai', '>=', now())
                     ->orderBy('waktu_mulai');
    }
}
