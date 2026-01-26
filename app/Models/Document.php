<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // Penting untuk UUID
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasUuids;

    protected $fillable = [
        'doc_number',
        'title',
        'description',
        'category_id',
        'user_id',
        'is_confidential',
        'status'
    ];

    // Mengambil versi terbaru dari dokumen ini
    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)->latestOfMany('version_number');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    // RELASI MANY-TO-MANY KE TAGS
    public function tags()
    {
        // Parameter: Model, Nama Tabel Pivot, Foreign Key lokal, Foreign Key target
        return $this->belongsToMany(Tag::class, 'document_tag', 'document_id', 'tag_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }
}
