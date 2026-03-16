<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolah';

    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'alamat_lengkap',
        'koordinat_peta',
        'nomor_telepon',
        'email_resmi',
        'tautan_sosmed',
        'logo_path',
        'favicon_path',
        'teks_sambutan_kepsek',
        'foto_kepsek',
        'visi_teks',
        'misi_teks',
    ];

    protected function casts(): array
    {
        return [
            'tautan_sosmed' => 'array',
        ];
    }
}
