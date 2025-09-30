<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
        'deskripsi'
    ];

    // Relasi ke Sub Bidang
    public function subBidang()
    {
        return $this->hasMany(SubBidang::class);
    }

    // Relasi ke Okupansi
    public function okupansi()
    {
        return $this->hasMany(Okupansi::class);
    }

}
