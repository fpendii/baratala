<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobdesk extends Model
{
    use HasFactory;

    protected $table = 'jobdesk';

    protected $fillable = [
        'judul_jobdesk',
        'deskripsi',
        'divisi',
    ];


    public function laporanJobdesks()
    {
        return $this->hasMany(LaporanJobdesk::class, 'id_jobdesk');
    }
}
