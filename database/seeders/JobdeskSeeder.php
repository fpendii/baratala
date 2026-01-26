<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jobdesk;

class JobdeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jobdesk::create([
            'judul_jobdesk' => 'Perbaikan Mesin',
            'deskripsi' => 'Melakukan perbaikan dan pemeliharaan rutin pada mesin produksi.',
            'divisi' => 'produksi',
        ]);

        Jobdesk::create([
            'judul_jobdesk' => 'Penyusunan Anggaran Tahunan',
            'deskripsi' => 'Menyusun rencana anggaran untuk tahun fiskal berikutnya.',
            'divisi' => 'keuangan',
        ]);

        Jobdesk::create([
            'judul_jobdesk' => 'Pengembangan Aplikasi Internal',
            'deskripsi' => 'Mengembangkan aplikasi untuk efisiensi operasional.',
            'divisi' => 'enginer',
        ]);
    }
}
