<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bidang;
use App\Models\SubBidang;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidangData = [
            [
                'nama_bidang' => 'Penagihan',
                'deskripsi' => 'Bidang Penagihan',
                'sub_bidang' => []
            ],
            [
                'nama_bidang' => 'Perbendaharaan dan Pajak',
                'deskripsi' => 'Bidang Perbendaharaan dan Pajak',
                'sub_bidang' => []
            ],
            [
                'nama_bidang' => 'Perencanaan Korporat dan Manajemen Portofolio',
                'deskripsi' => 'Bidang Perencanaan Korporat dan Manajemen Portofolio',
                'sub_bidang' => []
            ],
            [
                'nama_bidang' => 'Akuntansi',
                'deskripsi' => 'Bidang Akuntansi',
                'sub_bidang' => []
            ],
            [
                'nama_bidang' => 'Keuangan Korporat',
                'deskripsi' => 'Bidang Keuangan Korporat',
                'sub_bidang' => []
            ],
            [
                'nama_bidang' => 'Manajemen Penjualan Telekomunikasi, Logistik dan Layanan Keuangan',
                'deskripsi' => 'Bidang Manajemen Penjualan Telekomunikasi, Logistik dan Layanan Keuangan',
                'sub_bidang' => []
            ],
            [
                'nama_bidang' => 'Manajemen Penjualan Segmen Pemerintahan, Energi, Konstruksi & Hospitality',
                'deskripsi' => 'Bidang Manajemen Penjualan Segmen Pemerintahan, Energi, Konstruksi & Hospitality',
                'sub_bidang' => []
            ],
            [
                'nama_bidang' => 'Manajemen Penjualan Retail',
                'deskripsi' => 'Bidang Manajemen Penjualan Retail',
                'sub_bidang' => []
            ],
            [
                'nama_bidang' => 'Pengembangan Bisnis Konektivitas dan Infrastruktur',
                'deskripsi' => 'Bidang Pengembangan Bisnis Konektivitas dan Infrastruktur',
                'sub_bidang' => []
            ]
        ];

        foreach ($bidangData as $data) {
            $bidang = Bidang::create([
                'nama_bidang' => $data['nama_bidang'],
                'deskripsi' => $data['deskripsi']
            ]);

            // Create sub bidang if any
            foreach ($data['sub_bidang'] as $subBidangData) {
                SubBidang::create([
                    'nama_sub_bidang' => $subBidangData,
                    'deskripsi' => 'Sub ' . $subBidangData,
                    'bidang_id' => $bidang->id
                ]);
            }
        }
    }
}
