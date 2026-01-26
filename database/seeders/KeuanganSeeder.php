<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keuangan;

class KeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Keuangan::create([
            'nominal' => 5000000.00,
            'deskripsi' => 'Anggaran untuk perbaikan mesin produksi.',
        ]);
    }
}
