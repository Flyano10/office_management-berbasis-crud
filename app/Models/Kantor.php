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
        'status_kepemilikan'
    ];

    // Relasi ke Kota
    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    // Relasi ke Jenis Kantor
    public function jenisKantor()
    {
        return $this->belongsTo(JenisKantor::class);
    }

    // Relasi ke Parent Kantor (Self-referencing)
    public function parentKantor()
    {
        return $this->belongsTo(Kantor::class, 'parent_kantor_id');
    }

    // Relasi ke Child Kantor (Self-referencing)
    public function childKantor()
    {
        return $this->hasMany(Kantor::class, 'parent_kantor_id');
    }

    // Relasi ke Kontrak
    public function kontrak()
    {
        return $this->hasMany(Kontrak::class);
    }

    // Relasi ke Gedung
    public function gedung()
    {
        return $this->hasMany(Gedung::class);
    }
}
