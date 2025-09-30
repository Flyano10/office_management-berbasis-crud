<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kota';

    protected $fillable = [
        'nama_kota',
        'kode_kota',
        'provinsi_id'
    ];

    // Relasi ke Provinsi
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    // Relasi ke Gedung
    public function gedung()
    {
        return $this->hasMany(Gedung::class);
    }

    // Relasi ke Kantor
    public function kantor()
    {
        return $this->hasMany(Kantor::class);
    }
}
