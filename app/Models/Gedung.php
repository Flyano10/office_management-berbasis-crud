<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedung';

    protected $fillable = [
        'nama_gedung',
        'alamat',
        'latitude',
        'longitude',
        'kantor_id',
        'status_gedung',
        'status_kepemilikan'
    ];

    // Relasi ke Kantor
    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    // Relasi ke Lantai
    public function lantai()
    {
        return $this->hasMany(Lantai::class);
    }
}
