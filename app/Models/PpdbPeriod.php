<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbPeriod extends Model
{
    protected $table = 'ppdb_periods';

    protected $fillable = [
        'nama_periode',
        'tahun_ajaran',
        'tahun_mulai',
        'tahun_selesai',
        'gelombang_ke',
        'gelombang_label',
        'tanggal_mulai_pendaftaran',
        'tanggal_selesai_pendaftaran',
        'tanggal_pengumuman',
        'tanggal_mulai_daftar_ulang',
        'tanggal_selesai_daftar_ulang',
        'deskripsi',
        'status',
        'status_pengumuman',
        'hasil_diumumkan_at',
        'catatan_pengumuman',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tahun_mulai' => 'integer',
            'tahun_selesai' => 'integer',
            'gelombang_ke' => 'integer',
            'tanggal_mulai_pendaftaran' => 'date',
            'tanggal_selesai_pendaftaran' => 'date',
            'tanggal_pengumuman' => 'date',
            'tanggal_mulai_daftar_ulang' => 'date',
            'tanggal_selesai_daftar_ulang' => 'date',
            'hasil_diumumkan_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(PpdbTrack::class, 'period_id')->orderBy('urutan');
    }

    public function quotas(): HasMany
    {
        return $this->hasMany(PpdbQuota::class, 'period_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(PpdbApplication::class, 'period_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePubliclyVisible($query)
    {
        return $query->whereIn('status', ['published', 'closed', 'archived']);
    }

    public function scopeRegistrationOpen($query)
    {
        $today = now()->toDateString();

        return $query->published()
            ->whereDate('tanggal_mulai_pendaftaran', '<=', $today)
            ->whereDate('tanggal_selesai_pendaftaran', '>=', $today);
    }

    public function scopeOrderForSelection($query)
    {
        return $query
            ->orderByDesc('is_active')
            ->orderByDesc('tahun_mulai')
            ->orderByDesc('tahun_selesai')
            ->orderBy('gelombang_ke')
            ->orderByDesc('tanggal_mulai_pendaftaran');
    }

    public function isAnnouncementPublished(): bool
    {
        return $this->status_pengumuman === 'published';
    }

    public function isRegistrationOpen(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        $startsAt = $this->tanggal_mulai_pendaftaran?->copy()->startOfDay();
        $endsAt = $this->tanggal_selesai_pendaftaran?->copy()->endOfDay();

        return $startsAt && $endsAt ? now()->between($startsAt, $endsAt) : false;
    }

    public function getFullLabelAttribute(): string
    {
        $segments = array_filter([
            $this->tahun_ajaran,
            $this->gelombang_label,
            $this->nama_periode,
        ]);

        return implode(' - ', $segments);
    }
}
