<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriInventaris;

class KategoriInventarisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'IT', 'deskripsi' => 'Perangkat teknologi informasi'],
            ['nama_kategori' => 'Perabot', 'deskripsi' => 'Furniture dan perabot kantor'],
            ['nama_kategori' => 'Elektronik', 'deskripsi' => 'Perangkat elektronik'],
            ['nama_kategori' => 'Kendaraan', 'deskripsi' => 'Kendaraan operasional'],
            ['nama_kategori' => 'Alat Tulis', 'deskripsi' => 'Peralatan tulis menulis'],
            ['nama_kategori' => 'Lain-lain', 'deskripsi' => 'Barang lainnya']
        ];
        
        foreach ($kategori as $k) {
            KategoriInventaris::create($k);
        }
    }
}
