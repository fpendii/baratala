<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class PenggunaSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        $penggunas = [
            [
                'nama' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'role' => 'admin',
            ],
            [
                'nama' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Melati No. 2, Bandung',
                'role' => 'karyawan',
            ],
            [
                'nama' => 'Rizky Pratama',
                'email' => 'rizky@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Anggrek No. 3, Surabaya',
                'role' => 'kepala teknik',
            ],
            [
                'nama' => 'Siti Rahma',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Mawar No. 4, Yogyakarta',
                'role' => 'keuangan',
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567894',
                'alamat' => 'Jl. Kenanga No. 5, Jakarta',
                'role' => 'direktur',
            ],
        ];

        foreach ($penggunas as $data) {
            Pengguna::create($data);
        }
    }
}
