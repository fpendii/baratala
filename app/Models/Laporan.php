<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'id_pengguna',
        'id_laporan_keuangan',
        'judul',
        'deskripsi',
        'lampiran',
        'status',
        'keputusan',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function laporanKeuangan()
    {
        return $this->belongsTo(LaporanKeuangan::class, 'id_laporan_keuangan');
    }
}
