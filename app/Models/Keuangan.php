<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';

    protected $fillable = [
        'nominal',
        'deskripsi',
    ];

    public function laporanKeuangans()
    {
        return $this->hasMany(LaporanKeuangan::class, 'id_keuangan');
    }
}
