<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruang;
use App\Models\Lantai;
use App\Models\Bidang;
use App\Models\SubBidang;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang sudah ada
        $lantai = Lantai::first();
        $bidang = Bidang::first();
        $subBidang = SubBidang::first();

        if (!$lantai || !$bidang) {
            $this->command->info('Data Lantai atau Bidang belum ada. Silakan jalankan seeder lain terlebih dahulu.');
            return;
        }

        // Data ruang sample
        $ruangData = [
            [
                'nama_ruang' => 'Ruang Meeting A',
                'kapasitas' => 20,
                'status_ruang' => 'tersedia',
                'lantai_id' => $lantai->id,
                'bidang_id' => $bidang->id,
                'sub_bidang_id' => $subBidang ? $subBidang->id : null,
            ],
            [
                'nama_ruang' => 'Ruang Kerja IT',
                'kapasitas' => 15,
                'status_ruang' => 'terisi',
                'lantai_id' => $lantai->id,
                'bidang_id' => $bidang->id,
                'sub_bidang_id' => $subBidang ? $subBidang->id : null,
            ],
            [
                'nama_ruang' => 'Ruang Server',
                'kapasitas' => 5,
                'status_ruang' => 'perbaikan',
                'lantai_id' => $lantai->id,
                'bidang_id' => $bidang->id,
                'sub_bidang_id' => null,
            ],
        ];

        foreach ($ruangData as $data) {
            Ruang::create($data);
        }

        $this->command->info('Sample data ruang berhasil dibuat!');
    }
}