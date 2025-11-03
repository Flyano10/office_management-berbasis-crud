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
        'parent_kantor',
        'parent_kantor_nama',
        'status_perjanjian',
        'status',
        'berita_acara',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Opsi status perjanjian kontrak
    public static function getStatusPerjanjianOptions()
    {
        return [
            'Baru' => 'Baru',
            'Amandemen' => 'Amandemen'
        ];
    }

    // Opsi status kontrak
    public static function getStatusOptions()
    {
        return [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif',
            'Batal' => 'Batal'
        ];
    }

    // Opsi kantor induk
    public static function getParentKantorOptions()
    {
        return [
            'Pusat' => 'Pusat',
            'SBU' => 'SBU',
            'Perwakilan' => 'Perwakilan',
            'Gudang' => 'Gudang'
        ];
    }

    /**
     * Scope: Kontrak yang akan berakhir dalam X bulan ke depan (default aktif saja).
     */
    public function scopeExpiringWithinMonths($query, int $months, ?int $kantorId = null)
    {
        $now = now()->startOfDay();
        $until = now()->addMonths($months)->endOfDay();

        $query->whereDate('tanggal_selesai', '>=', $now)
            ->whereDate('tanggal_selesai', '<=', $until)
            ->where('status', 'Aktif');

        if ($kantorId !== null) {
            $query->where('kantor_id', $kantorId);
        }

        return $query;
    }

    /**
     * Helper: Hitung sisa hari menuju tanggal_selesai.
     */
    public function getDaysToEndAttribute()
    {
        if (!$this->tanggal_selesai) {
            return null;
        }
        return now()->startOfDay()->diffInDays(optional($this->tanggal_selesai)->endOfDay(), false);
    }

    // Relasi dengan model Kantor
    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    // Relasi dengan model Realisasi
    public function realisasi()
    {
        return $this->hasMany(Realisasi::class);
    }
}
