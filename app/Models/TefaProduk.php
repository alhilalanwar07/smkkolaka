<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TefaProduk extends Model
{
    protected $table = 'tefa_produk';

    protected $fillable = [
        'program_keahlian_id',
        'kategori_id',
        'nama_produk_jasa',
        'slug',
        'deskripsi',
        'harga_estimasi',
        'gambar_utama',
        'status_ketersediaan',
    ];

    protected function casts(): array
    {
        return [
            'harga_estimasi' => 'decimal:2',
        ];
    }

    public function programKeahlian(): BelongsTo
    {
        return $this->belongsTo(ProgramKeahlian::class, 'program_keahlian_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(TefaKategori::class, 'kategori_id');
    }

    public function scopeTersedia($query)
    {
        return $query->where('status_ketersediaan', 'tersedia');
    }
}
