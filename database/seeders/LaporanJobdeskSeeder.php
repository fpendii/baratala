<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanJobdesk;
use App\Models\Pengguna;
use App\Models\Jobdesk;

class LaporanJobdeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pengguna = Pengguna::where('email', 'karyawan@example.com')->first();
        $jobdesk = Jobdesk::where('judul_jobdesk', 'Perbaikan Mesin')->first();
        if ($pengguna && $jobdesk) {
            LaporanJobdesk::create([
                'id_pengguna' => $pengguna->id,
                'id_jobdesk' => $jobdesk->id,
                'deskripsi' => 'Selesai perbaikan mesin A, mesin beroperasi normal.',
                'lampiran' => 'laporan_perbaikan_mesin.pdf',
                'status' => 'dikerjakan',
            ]);
        }
    }
}
