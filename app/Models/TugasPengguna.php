<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasPengguna extends Model
{
    protected $table = 'tugas_pengguna';
    protected $fillable = ['id_pengguna', 'id_tugas'];

    // relasi ke User
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    // relasi ke Tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas');
    }
}

