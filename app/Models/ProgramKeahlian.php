<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramKeahlian extends Model
{
    protected $table = 'program_keahlian';

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'slug',
        'deskripsi_lengkap',
        'fasilitas_unggulan',
        'prospek_karir',
        'gambar_cover',
        'status_tampil',
    ];

    protected function casts(): array
    {
        return [
            'status_tampil' => 'boolean',
        ];
    }

    public function tefaProduk(): HasMany
    {
        return $this->hasMany(TefaProduk::class, 'program_keahlian_id');
    }

    public function ppdbQuotas(): HasMany
    {
        return $this->hasMany(PpdbQuota::class, 'program_keahlian_id');
    }

    public function ppdbPilihanUtama(): HasMany
    {
        return $this->hasMany(PpdbApplication::class, 'pilihan_program_1_id');
    }

    public function ppdbPilihanCadangan(): HasMany
    {
        return $this->hasMany(PpdbApplication::class, 'pilihan_program_2_id');
    }

    public function scopeTampil($query)
    {
        return $query->where('status_tampil', true);
    }
}
