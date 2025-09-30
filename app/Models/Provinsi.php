<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';

    protected $fillable = [
        'nama_provinsi',
        'kode_provinsi'
    ];

    // Relasi ke Kota
    public function kota()
    {
        return $this->hasMany(Kota::class);
    }
}
