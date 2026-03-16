<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbQuota extends Model
{
    protected $table = 'ppdb_quotas';

    protected $fillable = [
        'period_id',
        'track_id',
        'program_keahlian_id',
        'kuota',
        'kuota_terisi',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
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

    public function programKeahlian(): BelongsTo
    {
        return $this->belongsTo(ProgramKeahlian::class, 'program_keahlian_id');
    }
}
