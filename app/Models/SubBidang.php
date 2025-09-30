<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBidang extends Model
{
    use HasFactory;

    protected $table = 'sub_bidang';

    protected $fillable = [
        'nama_sub_bidang',
        'deskripsi',
        'bidang_id'
    ];

    // Relasi ke Bidang
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}
