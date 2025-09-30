<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'nama_admin' => 'Super Admin',
            'email' => 'admin@plniconplus.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        Admin::create([
            'nama_admin' => 'Admin Kantor',
            'email' => 'kantor@plniconplus.com',
            'username' => 'kantor',
            'password' => Hash::make('kantor123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
