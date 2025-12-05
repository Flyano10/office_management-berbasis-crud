<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    use HasFactory;

    protected $table = 'kantor';

    protected $fillable = [
        'kode_kantor',
        'nama_kantor',
        'alamat',
        'latitude',
        'longitude',
        'kota_id',
        'jenis_kantor_id',
        'parent_kantor_id',
        'status_kantor',
        'status_kepemilikan',
        'jenis_kepemilikan',
        'luas_tanah',
        'luas_bangunan',
        'daya_listrik_va',
        'kapasitas_genset_kva',
        'jumlah_sumur',
        'jumlah_septictank',
    ];

    // Relasi dengan model Kota
    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    // Relasi dengan model Jenis Kantor
    public function jenisKantor()
    {
        return $this->belongsTo(JenisKantor::class);
    }

    // Relasi dengan kantor induk (parent)
    public function parentKantor()
    {
        return $this->belongsTo(Kantor::class, 'parent_kantor_id');
    }

    // Relasi dengan kantor cabang (child)
    public function childKantor()
    {
        return $this->hasMany(Kantor::class, 'parent_kantor_id');
    }

    // Relasi dengan model Kontrak
    public function kontrak()
    {
        return $this->hasMany(Kontrak::class);
    }

    // Relasi dengan model Gedung
    public function gedung()
    {
        return $this->hasMany(Gedung::class);
    }
}
