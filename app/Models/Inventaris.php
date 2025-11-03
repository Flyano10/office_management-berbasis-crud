<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang', 'kode_inventaris', 'kategori_id', 'jumlah', 'kondisi',
        'merk', 'harga', 'tahun', 'tanggal_pembelian',
        'lokasi_kantor_id', 'lokasi_gedung_id', 'lokasi_lantai_id', 'lokasi_ruang_id',
        'bidang_id', 'sub_bidang_id', 'tanggal_input', 'gambar', 'deskripsi'
    ];

    protected $casts = [
        'tanggal_input' => 'datetime',
        'tanggal_pembelian' => 'date',
        'jumlah' => 'integer',
        'harga' => 'decimal:2',
        'tahun' => 'integer'
    ];

    /**
     * Get the kategori that owns the inventaris.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriInventaris::class, 'kategori_id');
    }

    /**
     * Get the kantor that owns the inventaris.
     */
    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'lokasi_kantor_id');
    }

    /**
     * Get the gedung that owns the inventaris.
     */
    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'lokasi_gedung_id');
    }

    /**
     * Get the lantai that owns the inventaris.
     */
    public function lantai()
    {
        return $this->belongsTo(Lantai::class, 'lokasi_lantai_id');
    }

    /**
     * Get the ruang that owns the inventaris.
     */
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'lokasi_ruang_id');
    }

    /**
     * Get the bidang that owns the inventaris.
     */
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Get the sub bidang that owns the inventaris.
     */
    public function subBidang()
    {
        return $this->belongsTo(SubBidang::class);
    }
}
