<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    use HasFactory;

    protected $table = 'lantai';

    protected $fillable = [
        'nama_lantai',
        'nomor_lantai',
        'gedung_id'
    ];

    // Relasi ke Gedung
    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    // Relasi ke Ruang
    public function ruang()
    {
        return $this->hasMany(Ruang::class);
    }
}
