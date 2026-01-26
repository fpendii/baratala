<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanJobdesk extends Model
{
    use HasFactory;

    protected $table = 'laporan_jobdesk';

    protected $fillable = [
        'id_pengguna',
        'id_jobdesk',
        'deskripsi',
        'lampiran',
        'status',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function jobdesk()
    {
        return $this->belongsTo(Jobdesk::class, 'id_jobdesk');
    }
}
