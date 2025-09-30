<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provinsi;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsi = [
            ['nama_provinsi' => 'DKI Jakarta', 'kode_provinsi' => '31'],
            ['nama_provinsi' => 'Jawa Barat', 'kode_provinsi' => '32'],
            ['nama_provinsi' => 'Jawa Tengah', 'kode_provinsi' => '33'],
            ['nama_provinsi' => 'Jawa Timur', 'kode_provinsi' => '35'],
            ['nama_provinsi' => 'Banten', 'kode_provinsi' => '36'],
            ['nama_provinsi' => 'Bali', 'kode_provinsi' => '51'],
            ['nama_provinsi' => 'Sumatera Utara', 'kode_provinsi' => '12'],
            ['nama_provinsi' => 'Sumatera Barat', 'kode_provinsi' => '13'],
            ['nama_provinsi' => 'Riau', 'kode_provinsi' => '14'],
            ['nama_provinsi' => 'Jambi', 'kode_provinsi' => '15'],
            ['nama_provinsi' => 'Sumatera Selatan', 'kode_provinsi' => '16'],
            ['nama_provinsi' => 'Bengkulu', 'kode_provinsi' => '17'],
            ['nama_provinsi' => 'Lampung', 'kode_provinsi' => '18'],
        ];

        foreach ($provinsi as $data) {
            Provinsi::create($data);
        }
    }
}
