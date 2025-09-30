<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'user_name',
        'action',
        'model_type',
        'model_id',
        'model_name',
        'old_values',
        'new_values',
        'changed_fields',
        'ip_address',
        'user_agent',
        'url',
        'description',
        'metadata'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    /**
     * Get the model that was affected
     */
    public function model()
    {
        if ($this->model_type) {
            return $this->morphTo('model', 'model_type', 'model_id');
        }
        return null;
    }

    /**
     * Scope for filtering by user
     */
    public function scopeForUser($query, $userId, $userType = 'admin')
    {
        return $query->where('user_id', $userId)
                    ->where('user_type', $userType);
    }

    /**
     * Scope for filtering by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by model
     */
    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted action description
     */
    public function getFormattedActionAttribute()
    {
        $actions = [
            'create' => 'Membuat',
            'update' => 'Mengubah',
            'delete' => 'Menghapus',
            'login' => 'Login',
            'logout' => 'Logout',
            'view' => 'Melihat',
            'export' => 'Export',
            'import' => 'Import',
            'bulk_delete' => 'Bulk Delete',
            'bulk_export' => 'Bulk Export',
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Get formatted model name
     */
    public function getFormattedModelAttribute()
    {
        $models = [
            'App\\Models\\Kantor' => 'Kantor',
            'App\\Models\\Gedung' => 'Gedung',
            'App\\Models\\Lantai' => 'Lantai',
            'App\\Models\\Ruang' => 'Ruang',
            'App\\Models\\Okupansi' => 'Okupansi',
            'App\\Models\\Kontrak' => 'Kontrak',
            'App\\Models\\Realisasi' => 'Realisasi',
            'App\\Models\\Bidang' => 'Bidang',
            'App\\Models\\SubBidang' => 'Sub Bidang',
            'App\\Models\\Admin' => 'Admin',
        ];

        return $models[$this->model_type] ?? $this->model_type;
    }

    /**
     * Get changes summary
     */
    public function getChangesSummaryAttribute()
    {
        if (!$this->changed_fields || !$this->old_values || !$this->new_values) {
            return null;
        }

        $summary = [];
        foreach ($this->changed_fields as $field) {
            $oldValue = $this->old_values[$field] ?? null;
            $newValue = $this->new_values[$field] ?? null;
            
            if ($oldValue !== $newValue) {
                $summary[] = "{$field}: '{$oldValue}' → '{$newValue}'";
            }
        }

        return implode(', ', $summary);
    }
}