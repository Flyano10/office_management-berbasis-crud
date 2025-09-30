<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $table = 'ruang';

    protected $fillable = [
        'nama_ruang',
        'kapasitas',
        'status_ruang',
        'lantai_id',
        'bidang_id',
        'sub_bidang_id'
    ];

    // Relasi ke Lantai
    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }

    // Relasi ke Okupansi
    public function okupansi()
    {
        return $this->hasMany(Okupansi::class);
    }

    // Relasi ke Bidang
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    // Relasi ke Sub Bidang
    public function subBidang()
    {
        return $this->belongsTo(SubBidang::class);
    }

}
