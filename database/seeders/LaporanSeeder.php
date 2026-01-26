<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\Pengguna;
use App\Models\LaporanKeuangan;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pengguna = Pengguna::where('email', 'direktur@example.com')->first();
        $laporanKeuangan = LaporanKeuangan::first();

        if ($pengguna && $laporanKeuangan) {
            Laporan::create([
                'id_pengguna' => $pengguna->id,
                'id_laporan_keuangan' => $laporanKeuangan->id,
                'judul' => 'Laporan Pengeluaran Operasional',
                'deskripsi' => 'Ringkasan pengeluaran operasional bulan ini.',
                'lampiran' => 'laporan_ops_september.pdf',
                'status' => 'pending',
                'keputusan' => null,
            ]);
        }
    }
}   
