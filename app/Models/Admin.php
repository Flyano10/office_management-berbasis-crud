<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'nama_admin',
        'email',
        'no_hp',
        'username',
        'password',
        'role',
        'region_id',
        'bidang_id',
        'kantor_id',
        'is_active',
        'last_login',
        'password_history',
        'password_changed_at',
        'failed_login_attempts',
        'locked_until'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'password_history' => 'array',
        'password_changed_at' => 'datetime',
        'locked_until' => 'datetime',
    ];

    // Relasi ke Provinsi (region)
    public function region()
    {
        return $this->belongsTo(Provinsi::class, 'region_id');
    }

    // Relasi ke Bidang
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    // Relasi ke Kantor
    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'kantor_id');
    }

    // Scope untuk filter berdasarkan role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope untuk filter berdasarkan region
    public function scopeByRegion($query, $regionId)
    {
        return $query->where('region_id', $regionId);
    }

    // Scope untuk filter berdasarkan bidang
    public function scopeByBidang($query, $bidangId)
    {
        return $query->where('bidang_id', $bidangId);
    }

    // Scope untuk filter berdasarkan kantor
    public function scopeByKantor($query, $kantorId)
    {
        return $query->where('kantor_id', $kantorId);
    }

    // Method untuk cek akses berdasarkan role
    public function hasFullAccess()
    {
        return $this->role === 'super_admin';
    }

    public function hasRegionalAccess()
    {
        return in_array($this->role, ['super_admin', 'admin_regional']);
    }

    public function hasLimitedAccess()
    {
        return $this->role === 'staf';
    }
}
