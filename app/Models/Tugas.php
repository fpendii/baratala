<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'id_pengguna',
        'judul_rencana',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'jenis',
        'prioritas',
        'lampiran',
        'catatan',
    ];

    public function pengguna()
    {
        return $this->belongsToMany(
            Pengguna::class,      // model yang di-relasi
            'tugas_pengguna',     // nama tabel pivot
            'id_tugas',           // foreign key di pivot untuk Tugas
            'id_pengguna'         // foreign key di pivot untuk Pengguna
        );
    }

    public function tugasPengguna()
    {
        return $this->hasMany(TugasPengguna::class, 'id_tugas');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class);
    }
}
