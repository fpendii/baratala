<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tugas;
use App\Models\Pengguna;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pengguna = Pengguna::where('email', 'karyawan@example.com')->first();
        if ($pengguna) {
            Tugas::create([
                'id_pengguna' => $pengguna->id,
                'judul_rencana' => 'Rapat Mingguan Divisi',
                'deskripsi' => 'Rapat untuk membahas progres pekerjaan mingguan.',
                'tanggal_mulai' => '2025-09-20',
                'tanggal_selesai' => '2025-09-20',
                'status' => 'belum dikerjakan',
                'jenis' => 'rencana',
                'prioritas' => 'tinggi',
                'lampiran' => null,
                'catatan' => 'Siapkan presentasi.',
            ]);
        }
    }
}
