<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriInventaris extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori', 'deskripsi'
    ];

    /**
     * Get the inventaris for the kategori.
     */
    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'kategori_id');
    }
}
