<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        // Role-based access: deny staf, scope admin_regional & manager_bidang
        $actor = auth('admin')->user();
        if ($actor && $actor->role === 'staf') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke Audit Log!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Audit Log tidak tersedia untuk Staf.'
                ]);
        }
        if ($actor && in_array($actor->role, ['admin_regional','manager_bidang'], true)) {
            $adminIds = Admin::query()
                ->when($actor->kantor_id, function($q) use ($actor) { $q->where('kantor_id', $actor->kantor_id); })
                ->when($actor->role === 'manager_bidang', function($q) use ($actor) { $q->where('bidang_id', $actor->bidang_id); })
                ->pluck('id');
            $query->whereIn('user_id', $adminIds);
        }

        // Fungsi pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) { 
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('model_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
            // Jika yang terkirim adalah nama (legacy url), konversi ke ID
            if (!is_numeric($userId)) {
                $found = Admin::where('nama_admin', $userId)->value('id');
                if ($found) { $userId = $found; }
            }
            $query->where('user_id', $userId);
        }

        // Filter berdasarkan action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan tipe model
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter berdasarkan range tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter berdasarkan IP
        if ($request->filled('ip_address')) {
            $query->where('ip_address', $request->ip_address);
        }

        // Fungsi sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'user_name', 'action', 'model_name', 'ip_address'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $auditLogs = $query->paginate(20)->appends(request()->query());

        // Ambil opsi filter (menggunakan scope saat ini) dan hapus ordering supaya DISTINCT valid
        $actions = (clone $query)->reorder()->distinct()->pluck('action')->sort();
        $modelTypes = (clone $query)->reorder()->distinct()->pluck('model_type')->filter()->sort();

        // Kumpulkan user_id dari query yang sudah di-scope, kemudian map ke Admin
        $userIds = (clone $query)->reorder()->whereNotNull('user_id')->distinct()->pluck('user_id');
        $users = Admin::whereIn('id', $userIds)->orderBy('nama_admin')->get(['id','nama_admin']);

        return view('audit-log.index', compact('auditLogs', 'actions', 'modelTypes', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $auditLog = AuditLog::findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && $actor->role === 'staf') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke Audit Log!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Audit Log tidak tersedia untuk Staf.'
                ]);
        }
        if ($actor && in_array($actor->role, ['admin_regional','manager_bidang'], true)) {
            $allowedAdminIds = Admin::query()
                ->when($actor->kantor_id, function($q) use ($actor) { $q->where('kantor_id', $actor->kantor_id); })
                ->when($actor->role === 'manager_bidang', function($q) use ($actor) { $q->where('bidang_id', $actor->bidang_id); })
                ->pluck('id')
                ->toArray();
            if (!in_array($auditLog->user_id, $allowedAdminIds, true)) {
                return redirect()->route('audit-log.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat log ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk melihat log ini!'
                    ]);
            }
        }
        return view('audit-log.show', compact('auditLog'));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        try {
            $query = AuditLog::query();

            // Role-based access
            $actor = auth('admin')->user();
            if ($actor && $actor->role === 'staf') {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke Audit Log!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Audit Log tidak tersedia untuk Staf.'
                    ]);
            }
            if ($actor && in_array($actor->role, ['admin_regional','manager_bidang'], true)) {
                $adminIds = Admin::query()
                    ->when($actor->kantor_id, function($q) use ($actor) { $q->where('kantor_id', $actor->kantor_id); })
                    ->when($actor->role === 'manager_bidang', function($q) use ($actor) { $q->where('bidang_id', $actor->bidang_id); })
                    ->pluck('id');
                $query->whereIn('user_id', $adminIds);
            }

            // Terapkan filter yang sama seperti index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('user_name', 'like', "%{$search}%")
                      ->orWhere('model_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('action', 'like', "%{$search}%");
                });
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            if ($request->filled('model_type')) {
                $query->where('model_type', $request->model_type);
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            if ($request->filled('ip_address')) {
                $query->where('ip_address', $request->ip_address);
            }

            $auditLogs = $query->orderBy('created_at', 'desc')->get();

            // Log export action
            AuditLogService::logExport('AuditLog', $auditLogs->count(), $request, 'Export audit logs');

            // Generate CSV
            $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($auditLogs) {
                $file = fopen('php://output', 'w');
                
                // CSV headers
                fputcsv($file, [
                    'ID',
                    'Tanggal',
                    'User',
                    'Aksi',
                    'Model',
                    'Model Name',
                    'IP Address',
                    'User Agent',
                    'URL',
                    'Deskripsi',
                    'Changes Summary'
                ]);

                // CSV data
                foreach ($auditLogs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user_name,
                        $log->formatted_action,
                        $log->formatted_model,
                        $log->model_name,
                        $log->ip_address,
                        $log->user_agent,
                        $log->url,
                        $log->description,
                        $log->changes_summary
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Audit Log Export Error:', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal export audit logs: ' . $e->getMessage())
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Export Error',
                    'message' => 'Gagal export audit logs: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Get audit log statistics
     */
    public function statistics(Request $request)
    {
        $query = AuditLog::query();

        // Role-based access
        $actor = auth('admin')->user();
        if ($actor && $actor->role === 'staf') {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        if ($actor && in_array($actor->role, ['admin_regional','manager_bidang'], true)) {
            $adminIds = Admin::query()
                ->when($actor->kantor_id, function($q) use ($actor) { $q->where('kantor_id', $actor->kantor_id); })
                ->when($actor->role === 'manager_bidang', function($q) use ($actor) { $q->where('bidang_id', $actor->bidang_id); })
                ->pluck('id');
            $query->whereIn('user_id', $adminIds);
        }

        // Apply date filter if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $stats = [
            'total_logs' => $query->count(),
            'actions' => $query->selectRaw('action, COUNT(*) as count')
                              ->groupBy('action')
                              ->orderBy('count', 'desc')
                              ->get(),
            'users' => $query->selectRaw('user_name, COUNT(*) as count')
                            ->whereNotNull('user_name')
                            ->groupBy('user_name')
                            ->orderBy('count', 'desc')
                            ->get(),
            'models' => $query->selectRaw('model_type, COUNT(*) as count')
                             ->whereNotNull('model_type')
                             ->groupBy('model_type')
                             ->orderBy('count', 'desc')
                             ->get(),
            'daily_activity' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                     ->groupBy('date')
                                     ->orderBy('date', 'desc')
                                     ->limit(30)
                                     ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Reset all audit logs
     */
    public function reset()
    {
        try {
            $actor = auth('admin')->user();
            if ($actor && $actor->role === 'staf') {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke Audit Log!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Audit Log hanya untuk super admin dan admin regional.'
                    ]);
            }
            if ($actor && $actor->role === 'admin_regional') {
                return redirect()->route('audit-log.index')
                    ->with('error', 'Reset audit log hanya untuk super admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya super admin yang dapat mereset audit log.'
                    ]);
            }
            $count = AuditLog::count();
            
            // Log the reset action before deleting
            AuditLogService::log(
                'reset',
                null,
                [],
                ['deleted_count' => $count],
                request(),
                'Reset all audit logs',
                ['deleted_count' => $count]
            );

            // Delete all audit logs
            AuditLog::truncate();

            return redirect()->route('audit-log.index')
                ->with('success', "Berhasil reset audit log. {$count} records telah dihapus.")
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Reset Berhasil',
                    'message' => "Berhasil reset audit log. {$count} records telah dihapus."
                ]);

        } catch (\Exception $e) {
            Log::error('Audit Log Reset Error:', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal reset audit log: ' . $e->getMessage())
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Reset Error',
                    'message' => 'Gagal reset audit log: ' . $e->getMessage()
                ]);
        }
    }
}