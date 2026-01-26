<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    use HasUuids;

    protected $fillable = [
        'document_id',
        'version_number',
        'file_path',
        'file_name',
        'file_extension',
        'file_size'
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
