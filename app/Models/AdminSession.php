<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'session_id',
        'ip_address',
        'user_agent',
        'last_activity',
        'is_active'
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Relationship with Admin
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get active sessions for admin
     */
    public static function getActiveSessions($adminId)
    {
        return self::where('admin_id', $adminId)
            ->where('is_active', true)
            ->orderBy('last_activity', 'desc');
    }

    /**
     * Get session count for admin
     */
    public static function getSessionCount($adminId)
    {
        return self::where('admin_id', $adminId)
            ->where('is_active', true)
            ->count();
    }

    /**
     * Cleanup old sessions
     */
    public static function cleanupOldSessions($adminId, $keepCount = 0)
    {
        $sessions = self::getActiveSessions($adminId)->get();
        
        if ($sessions->count() > $keepCount) {
            $sessionsToDelete = $sessions->skip($keepCount);
            foreach ($sessionsToDelete as $session) {
                $session->update(['is_active' => false]);
            }
        }
    }

    /**
     * Update last activity
     */
    public function updateActivity()
    {
        $this->update(['last_activity' => now()]);
    }
}
