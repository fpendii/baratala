<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    use HasFactory;

    protected $table = 'laporan_keuangan';

    protected $fillable = [
        'id_keuangan',
        'id_pengguna',
        'tanggal',
        'keperluan',
        'nominal',
        'jenis',
        'jenis_uang',
        'lampiran',
        'persetujuan_direktur',
        'catatan',
        'status_persetujuan',
        'penerima',
        'bukti_persetujuan_pdf',
    ];

    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class, 'id_keuangan');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function penerimaRelasi()
    {
        return $this->belongsTo(Pengguna::class, 'penerima');
    }

    protected $casts = [
        'nominal' => 'decimal:2',
        'persetujuan_direktur' => 'boolean',
    ];
}
