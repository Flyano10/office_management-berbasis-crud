<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'status_kepemilikan',
        'layout_path'
    ];

    protected $appends = [
        'layout_url'
    ];

    public function getLayoutUrlAttribute(): ?string
    {
        if (!$this->layout_path) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $this->layout_path)) {
            return $this->layout_path;
        }

        if (Storage::disk('public')->exists($this->layout_path)) {
            return Storage::disk('public')->url($this->layout_path);
        }

        return asset('storage/' . ltrim($this->layout_path, '/'));
    }

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
