<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kota;
use App\Models\Provinsi;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil provinsi yang sudah ada
        $jakarta = Provinsi::where('nama_provinsi', 'DKI Jakarta')->first();
        $jawaBarat = Provinsi::where('nama_provinsi', 'Jawa Barat')->first();
        $jawaTengah = Provinsi::where('nama_provinsi', 'Jawa Tengah')->first();
        $jawaTimur = Provinsi::where('nama_provinsi', 'Jawa Timur')->first();

        $kota = [
            // DKI Jakarta
            ['nama_kota' => 'Jakarta Pusat', 'kode_kota' => '3171', 'provinsi_id' => $jakarta->id],
            ['nama_kota' => 'Jakarta Utara', 'kode_kota' => '3172', 'provinsi_id' => $jakarta->id],
            ['nama_kota' => 'Jakarta Barat', 'kode_kota' => '3173', 'provinsi_id' => $jakarta->id],
            ['nama_kota' => 'Jakarta Selatan', 'kode_kota' => '3174', 'provinsi_id' => $jakarta->id],
            ['nama_kota' => 'Jakarta Timur', 'kode_kota' => '3175', 'provinsi_id' => $jakarta->id],
            
            // Jawa Barat
            ['nama_kota' => 'Bandung', 'kode_kota' => '3273', 'provinsi_id' => $jawaBarat->id],
            ['nama_kota' => 'Bogor', 'kode_kota' => '3271', 'provinsi_id' => $jawaBarat->id],
            ['nama_kota' => 'Depok', 'kode_kota' => '3276', 'provinsi_id' => $jawaBarat->id],
            ['nama_kota' => 'Tangerang', 'kode_kota' => '3671', 'provinsi_id' => $jawaBarat->id],
            ['nama_kota' => 'Bekasi', 'kode_kota' => '3275', 'provinsi_id' => $jawaBarat->id],
            
            // Jawa Tengah
            ['nama_kota' => 'Semarang', 'kode_kota' => '3374', 'provinsi_id' => $jawaTengah->id],
            ['nama_kota' => 'Solo', 'kode_kota' => '3372', 'provinsi_id' => $jawaTengah->id],
            ['nama_kota' => 'Yogyakarta', 'kode_kota' => '3471', 'provinsi_id' => $jawaTengah->id],
            
            // Jawa Timur
            ['nama_kota' => 'Surabaya', 'kode_kota' => '3578', 'provinsi_id' => $jawaTimur->id],
            ['nama_kota' => 'Malang', 'kode_kota' => '3573', 'provinsi_id' => $jawaTimur->id],
            ['nama_kota' => 'Sidoarjo', 'kode_kota' => '3579', 'provinsi_id' => $jawaTimur->id],
        ];

        foreach ($kota as $data) {
            Kota::create($data);
        }
    }
}
