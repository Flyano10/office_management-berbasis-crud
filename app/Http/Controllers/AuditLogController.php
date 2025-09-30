<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Log;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('model_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by IP
        if ($request->filled('ip_address')) {
            $query->where('ip_address', $request->ip_address);
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'user_name', 'action', 'model_name', 'ip_address'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $auditLogs = $query->paginate(20)->appends(request()->query());

        // Get filter options
        $actions = AuditLog::distinct()->pluck('action')->sort();
        $modelTypes = AuditLog::distinct()->pluck('model_type')->filter()->sort();
        $users = AuditLog::distinct()->pluck('user_name')->filter()->sort();

        return view('audit-log.index', compact('auditLogs', 'actions', 'modelTypes', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $auditLog = AuditLog::findOrFail($id);
        return view('audit-log.show', compact('auditLog'));
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        try {
            $query = AuditLog::query();

            // Apply same filters as index
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