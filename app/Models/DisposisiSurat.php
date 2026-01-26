<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposisiSurat extends Model
{
    use HasFactory;

    protected $table = 'disposisi_surat';

    protected $fillable = [
        'surat_masuk_id',
        'direktur_id',
        'user_tujuan_id',
        'catatan',
        'tanggal_disposisi',
        'status_baca',
    ];

    /**
     * Relasi ke surat masuk
     * 1 disposisi milik 1 surat masuk
     */
    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    /**
     * Relasi ke direktur (user yang mendisposisi)
     */
    public function direktur()
    {
        return $this->belongsTo(Pengguna::class, 'direktur_id');
    }

    /**
     * Relasi ke user tujuan disposisi
     */
    public function userTujuan()
    {
        return $this->belongsTo(Pengguna::class, 'user_tujuan_id');
    }
}
