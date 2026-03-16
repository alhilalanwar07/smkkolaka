<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpdbTrack extends Model
{
    protected $table = 'ppdb_tracks';

    protected $fillable = [
        'period_id',
        'nama_jalur',
        'slug',
        'deskripsi',
        'status_tampil',
        'requires_verification',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'status_tampil' => 'boolean',
            'requires_verification' => 'boolean',
        ];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PpdbPeriod::class, 'period_id');
    }

    public function quotas(): HasMany
    {
        return $this->hasMany(PpdbQuota::class, 'track_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(PpdbApplication::class, 'track_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('status_tampil', true)->orderBy('urutan');
    }
}
