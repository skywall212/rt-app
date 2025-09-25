<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterWarga;

class MasterWargaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Ahmad Fauzi',
                'nik' => '3201010101010001',
                'tempat_lahir' => 'Bandung',
                'tgl_lahir' => '1990-05-12',
                'alamat' => 'Jl. Merdeka No. 1, Bandung'
            ],
            [
                'nama' => 'Siti Aminah',
                'nik' => '3201010101010002',
                'tempat_lahir' => 'Jakarta',
                'tgl_lahir' => '1992-07-21',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta'
            ],
            [
                'nama' => 'Budi Santoso',
                'nik' => '3201010101010003',
                'tempat_lahir' => 'Surabaya',
                'tgl_lahir' => '1988-11-05',
                'alamat' => 'Jl. Diponegoro No. 12, Surabaya'
            ],
            [
                'nama' => 'Ani Lestari',
                'nik' => '3201010101010004',
                'tempat_lahir' => 'Yogyakarta',
                'tgl_lahir' => '1995-03-18',
                'alamat' => 'Jl. Malioboro No. 10, Yogyakarta'
            ],
            [
                'nama' => 'Joko Widodo',
                'nik' => '3201010101010005',
                'tempat_lahir' => 'Solo',
                'tgl_lahir' => '1971-06-21',
                'alamat' => 'Jl. Slamet Riyadi No. 99, Solo'
            ],
        ];

        foreach ($data as $warga) {
            MasterWarga::create($warga);
        }
    }
}
