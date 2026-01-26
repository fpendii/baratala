<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'surat_keluar';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'id_pengguna',
        'tgl_surat',
        'nomor_surat',
        'konten_surat',
        'tujuan',
        'perihal',
        'jenis_surat',
        'lampiran',
        'dok_surat'
    ];

    // Kolom yang harus diubah ke tipe data tertentu (misal: date)
    protected $casts = [
        'tgl_surat' => 'date',
    ];

    // Relasi ke Model User (asumsi nama model pengguna Anda adalah User)
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
