<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    use HasFactory;

    protected $table = 'realisasi';

    protected $fillable = [
        'kontrak_id',
        'no_perjanjian_pihak_1',
        'no_perjanjian_pihak_2',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_realisasi',
        'kompensasi',
        'deskripsi',
        'rp_kompensasi',
        'lokasi_kantor',
        'alamat',
        'upload_berita_acara'
    ];

    // Relasi ke Kontrak
    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class);
    }
}
