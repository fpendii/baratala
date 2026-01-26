<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengguna extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pengguna';
    // jika tabel memiliki kolom deleted_at
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function laporanJobdesks()
    {
        return $this->hasMany(LaporanJobdesk::class, 'id_pengguna');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'id_pengguna');
    }

    public function laporanKeuangans()
    {
        return $this->hasMany(LaporanKeuangan::class, 'id_pengguna');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_pengguna');
    }

    // Surat yang didisposisikan ke user ini
    public function disposisiMasuk()
    {
        return $this->hasMany(DisposisiSurat::class, 'user_tujuan_id');
    }

    // Surat yang didisposisikan oleh direktur
    public function disposisiKeluar()
    {
        return $this->hasMany(DisposisiSurat::class, 'direktur_id');
    }
}
