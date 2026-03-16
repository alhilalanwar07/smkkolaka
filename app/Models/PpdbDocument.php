<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbDocument extends Model
{
    protected $table = 'ppdb_documents';

    protected $fillable = [
        'application_id',
        'jenis_dokumen',
        'file_path',
        'status_verifikasi',
        'catatan_verifikasi',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(PpdbApplication::class, 'application_id');
    }
}
