<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKantor extends Model
{
    use HasFactory;

    protected $table = 'jenis_kantor';

    protected $fillable = [
        'nama_jenis',
        'deskripsi'
    ];

    // Relasi ke Kantor
    public function kantor()
    {
        return $this->hasMany(Kantor::class);
    }
}
