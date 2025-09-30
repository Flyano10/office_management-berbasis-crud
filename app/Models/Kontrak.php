<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    use HasFactory;

    protected $table = 'kontrak';

    protected $fillable = [
        'nama_perjanjian',
        'no_perjanjian_pihak_1',
        'no_perjanjian_pihak_2',
        'asset_owner',
        'ruang_lingkup',
        'tanggal_mulai',
        'tanggal_selesai',
        'nilai_kontrak',
        'sbu',
        'peruntukan_kantor',
        'alamat',
        'kantor_id',
        'status_perjanjian',
        'berita_acara',
        'keterangan'
    ];

    // Relasi ke Kantor
    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    // Relasi ke Realisasi
    public function realisasi()
    {
        return $this->hasMany(Realisasi::class);
    }
}
