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
}
