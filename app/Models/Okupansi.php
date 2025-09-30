<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Okupansi extends Model
{
    use HasFactory;

    protected $table = 'okupansi';

    protected $fillable = [
        'ruang_id',
        'bidang_id',
        'sub_bidang_id',
        'tanggal_okupansi',
        'jml_pegawai_organik',
        'jml_pegawai_tad',
        'jml_pegawai_kontrak',
        'total_pegawai',
        'persentase_okupansi',
        'keterangan'
    ];

    // Relasi ke Ruang
    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    // Relasi ke Bidang
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    // Relasi ke Sub Bidang
    public function subBidang()
    {
        return $this->belongsTo(SubBidang::class, 'sub_bidang_id');
    }
}
