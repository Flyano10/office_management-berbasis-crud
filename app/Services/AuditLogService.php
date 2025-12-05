<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuditLogService
{
    /**
     * Log an action
     */
    public static function log(
        string $action,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
        ?Request $request = null,
        ?string $description = null,
        array $metadata = []
    ): AuditLog {
        $user = Auth::guard('admin')->user();
        $request = $request ?? request();

        // Ambil field yang berubah
        $changedFields = [];
        if (!empty($oldValues) && !empty($newValues)) {
            foreach ($newValues as $key => $value) {
                if (!isset($oldValues[$key]) || $oldValues[$key] !== $value) {
                    $changedFields[] = $key;
                }
            }
        }

        $auditLog = AuditLog::create([
            'user_type' => 'admin',
            'user_id' => $user?->id,
            'user_name' => $user?->nama_admin,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'model_name' => $model ? $model->nama_kantor ?? $model->nama_gedung ?? $model->nama_lantai ?? $model->nama_ruang ?? $model->nama_kontrak ?? $model->nama_bidang ?? $model->nama_sub_bidang ?? $model->nama_admin ?? 'Unknown' : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changed_fields' => $changedFields,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'description' => $description,
            'metadata' => $metadata,
        ]);

        // Clear dashboard cache jika action mempengaruhi stats (create, update, delete)
        // Hanya untuk model yang mempengaruhi dashboard stats
        if (in_array($action, ['create', 'update', 'delete']) && $model) {
            $modelClass = get_class($model);
            $affectsDashboard = in_array($modelClass, [
                \App\Models\Kantor::class,
                \App\Models\Gedung::class,
                \App\Models\Lantai::class,
                \App\Models\Ruang::class,
                \App\Models\Okupansi::class,
                \App\Models\Kontrak::class,
                \App\Models\Realisasi::class,
                \App\Models\Bidang::class,
                \App\Models\SubBidang::class,
            ]);

            if ($affectsDashboard) {
                // Clear dashboard cache secara async (tidak blocking)
                Cache::forget('dashboard.stats');
                Cache::forget('dashboard.status_stats');
                Cache::forget('dashboard.analytics');
                Cache::forget('dashboard.kantor_by_kota');
                Cache::forget('dashboard.gedung_by_kantor');
                Cache::forget('dashboard.okupansi_by_bidang');
                Cache::forget('dashboard.kontrak_by_status');
                Cache::forget('dashboard.kontrak_by_month');
                Cache::forget('dashboard.top_kantor');
                Cache::forget('dashboard.top_bidang');
                Cache::forget('dashboard.kantor_map');
                
                // Clear public cache juga
                Cache::forget('public.home.stats');
                Cache::forget('public.api.kantor_data');
                Cache::forget('public.directory.kota');
                Cache::forget('public.directory.jenis_kantor');
            }
            
            // Clear cache berdasarkan model yang diubah
            if ($modelClass === \App\Models\Kantor::class) {
                Cache::forget('public.api.kantor_data');
                Cache::forget('admin.kantor.jenis_kantor');
                Cache::forget('admin.kantor.kota');
                Cache::forget('admin.gedung.kantor.all');
            } elseif ($modelClass === \App\Models\Inventaris::class) {
                // Clear inventaris cache - akan di-clear per kantor saat diakses
                // Cache akan expired otomatis dalam 5 menit
            } elseif ($modelClass === \App\Models\Kontrak::class) {
                // Clear kontrak cache - akan di-clear per kantor saat diakses
                // Cache akan expired otomatis dalam 5 menit
            } elseif ($modelClass === \App\Models\Gedung::class) {
                Cache::forget('public.home.stats');
            }
        }

        // Clear recent activities cache untuk semua action
        Cache::forget('dashboard.recent_activities');
        Cache::forget('dashboard.activity_counts');

        return $auditLog;
    }

    /**
     * Log model creation
     */
    public static function logCreate(Model $model, ?Request $request = null, ?string $description = null): AuditLog
    {
        return self::log(
            'create',
            $model,
            [],
            $model->getAttributes(),
            $request,
            $description ?? "Membuat {$model->getTable()} baru"
        );
    }

    /**
     * Log model update
     */
    public static function logUpdate(Model $model, array $oldValues, ?Request $request = null, ?string $description = null): AuditLog
    {
        return self::log(
            'update',
            $model,
            $oldValues,
            $model->getAttributes(),
            $request,
            $description ?? "Mengubah {$model->getTable()}"
        );
    }

    /**
     * Log model deletion
     */
    public static function logDelete(Model $model, ?Request $request = null, ?string $description = null): AuditLog
    {
        return self::log(
            'delete',
            $model,
            $model->getAttributes(),
            [],
            $request,
            $description ?? "Menghapus {$model->getTable()}"
        );
    }

    /**
     * Log login
     */
    public static function logLogin($user, ?Request $request = null): AuditLog
    {
        return self::log(
            'login',
            null,
            [],
            ['user_id' => $user->id, 'user_name' => $user->nama_admin],
            $request,
            "Login ke sistem"
        );
    }

    /**
     * Log logout
     */
    public static function logLogout($user, ?Request $request = null): AuditLog
    {
        return self::log(
            'logout',
            null,
            [],
            ['user_id' => $user->id, 'user_name' => $user->nama_admin],
            $request,
            "Logout dari sistem"
        );
    }

    /**
     * Log view action
     */
    public static function logView(Model $model, ?Request $request = null, ?string $description = null): AuditLog
    {
        return self::log(
            'view',
            $model,
            [],
            [],
            $request,
            $description ?? "Melihat {$model->getTable()}"
        );
    }

    /**
     * Log bulk operations
     */
    public static function logBulkOperation(string $action, string $modelType, int $count, ?Request $request = null, ?string $description = null): AuditLog
    {
        return self::log(
            "bulk_{$action}",
            null,
            [],
            ['model_type' => $modelType, 'count' => $count],
            $request,
            $description ?? "Bulk {$action} {$count} {$modelType}"
        );
    }

    /**
     * Log export operation
     */
    public static function logExport(string $modelType, int $count, ?Request $request = null, ?string $description = null): AuditLog
    {
        return self::log(
            'export',
            null,
            [],
            ['model_type' => $modelType, 'count' => $count],
            $request,
            $description ?? "Export {$count} {$modelType}"
        );
    }

    /**
     * Log import operation
     */
    public static function logImport(string $modelType, int $count, ?Request $request = null, ?string $description = null): AuditLog
    {
        return self::log(
            'import',
            null,
            [],
            ['model_type' => $modelType, 'count' => $count],
            $request,
            $description ?? "Import {$count} {$modelType}"
        );
    }

    /**
     * Get audit logs with filters
     */
    public static function getLogs(array $filters = [])
    {
        $query = AuditLog::query();

        // Filter berdasarkan user
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Filter berdasarkan action
        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        // Filter berdasarkan model
        if (isset($filters['model_type'])) {
            $query->where('model_type', $filters['model_type']);
        }

        // Filter berdasarkan range tanggal
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        // Filter berdasarkan IP
        if (isset($filters['ip_address'])) {
            $query->where('ip_address', $filters['ip_address']);
        }

        // Pencarian
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('model_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc');
    }
}


