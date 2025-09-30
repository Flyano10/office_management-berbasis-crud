<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Okupansi;
use App\Models\Ruang;
use App\Models\Bidang;
use App\Models\SubBidang;

class OkupansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang sudah ada
        $ruang = Ruang::first();
        $bidang = Bidang::first();
        $subBidang = SubBidang::first();

        if (!$ruang || !$bidang) {
            $this->command->info('Data Ruang atau Bidang belum ada. Silakan jalankan seeder lain terlebih dahulu.');
            return;
        }

        // Data okupansi sample
        $okupansiData = [
            [
                'ruang_id' => $ruang->id,
                'bidang_id' => $bidang->id,
                'sub_bidang_id' => $subBidang ? $subBidang->id : null,
                'tanggal_okupansi' => '2024-09-23',
                'jml_pegawai_organik' => 0,
                'jml_pegawai_tad' => 0,
                'jml_pegawai_kontrak' => 50,
                'total_pegawai' => 50,
                'persentase_okupansi' => 100.00,
                'keterangan' => 'Sample data okupansi dengan pegawai kontrak',
            ],
        ];

        foreach ($okupansiData as $data) {
            Okupansi::create($data);
        }

        $this->command->info('Sample data okupansi berhasil dibuat!');
    }
}