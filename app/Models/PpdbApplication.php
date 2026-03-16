<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbApplication extends Model
{
    protected $table = 'ppdb_applications';

    protected $fillable = [
        'period_id',
        'track_id',
        'nomor_pendaftaran',
        'nama_lengkap',
        'nisn',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat_lengkap',
        'nomor_hp',
        'email',
        'asal_sekolah',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'nomor_hp_orang_tua',
        'pilihan_program_1_id',
        'pilihan_program_2_id',
        'nilai_rata_rata',
        'skor_akademik',
        'skor_prestasi',
        'skor_afirmasi',
        'skor_tes_dasar',
        'skor_wawancara',
        'skor_berkas',
        'skor_seleksi',
        'ranking_jalur',
        'ranking_program',
        'catatan_pendaftar',
        'catatan_verifikator',
        'status_pendaftaran',
        'status_berkas',
        'hasil_seleksi',
        'program_diterima_id',
        'selection_notes',
        'status_daftar_ulang',
        'daftar_ulang_at',
        'catatan_daftar_ulang',
        'verified_daftar_ulang_by',
        'verified_daftar_ulang_at',
        'scored_at',
        'submitted_at',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'daftar_ulang_at' => 'datetime',
            'verified_daftar_ulang_at' => 'datetime',
            'scored_at' => 'datetime',
            'nilai_rata_rata' => 'decimal:2',
            'skor_akademik' => 'decimal:2',
            'skor_prestasi' => 'decimal:2',
            'skor_afirmasi' => 'decimal:2',
            'skor_tes_dasar' => 'decimal:2',
            'skor_wawancara' => 'decimal:2',
            'skor_berkas' => 'decimal:2',
            'skor_seleksi' => 'decimal:2',
        ];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PpdbPeriod::class, 'period_id');
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(PpdbTrack::class, 'track_id');
    }

    public function pilihanProgram1(): BelongsTo
    {
        return $this->belongsTo(ProgramKeahlian::class, 'pilihan_program_1_id');
    }

    public function pilihanProgram2(): BelongsTo
    {
        return $this->belongsTo(ProgramKeahlian::class, 'pilihan_program_2_id');
    }

    public function programDiterima(): BelongsTo
    {
        return $this->belongsTo(ProgramKeahlian::class, 'program_diterima_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function reRegistrationVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_daftar_ulang_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PpdbDocument::class, 'application_id');
    }
}
