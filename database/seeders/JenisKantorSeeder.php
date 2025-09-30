<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisKantor;

class JenisKantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisKantor = [
            [
                'nama_jenis' => 'Pusat',
                'deskripsi' => 'Kantor Pusat PLN Icon Plus'
            ],
            [
                'nama_jenis' => 'SBU',
                'deskripsi' => 'Strategic Business Unit'
            ],
            [
                'nama_jenis' => 'KP',
                'deskripsi' => 'Kantor Perwakilan'
            ],
            [
                'nama_jenis' => 'Gudang',
                'deskripsi' => 'Gudang Penyimpanan'
            ]
        ];

        foreach ($jenisKantor as $data) {
            JenisKantor::create($data);
        }
    }
}
