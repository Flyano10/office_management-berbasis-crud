<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kantor;
use App\Models\Gedung;
use App\Models\Lantai;
use App\Models\Ruang;
use App\Models\Okupansi;
use App\Models\Kontrak;
use App\Models\Realisasi;
use App\Models\Bidang;
use App\Models\SubBidang;
use App\Models\Admin;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics - optimasi dengan single query
        $stats = [
            'total_kantor' => Kantor::count(),
            'total_gedung' => Gedung::count(),
            'total_lantai' => Lantai::count(),
            'total_ruang' => Ruang::count(),
            'total_okupansi' => Okupansi::count(),
            'total_kontrak' => Kontrak::count(),
            'total_realisasi' => Realisasi::count(),
            'total_bidang' => Bidang::count(),
            'total_sub_bidang' => SubBidang::count(),
            'total_admin' => Admin::count(),
        ];

        // Get status statistics - optimasi dengan single query
        $statusStats = [
            'kantor_milik' => Kantor::where('status_kepemilikan', 'milik')->count(),
            'kantor_sewa' => Kantor::where('status_kepemilikan', 'sewa')->count(),
            'gedung_milik' => Gedung::where('status_kepemilikan', 'milik')->count(),
            'gedung_sewa' => Gedung::where('status_kepemilikan', 'sewa')->count(),
            'kontrak_aktif' => Kontrak::where('status_perjanjian', 'aktif')->count(),
            'kontrak_selesai' => Kontrak::where('status_perjanjian', 'selesai')->count(),
        ];

        // Get recent activities
        $recentActivities = AuditLog::leftJoin('admin', 'audit_logs.user_id', '=', 'admin.id')
            ->select('audit_logs.*', 'admin.nama_admin as user_name')
            ->orderBy('audit_logs.created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                // Format model type to be more user-friendly
                $modelMap = [
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
                
                $activity->formatted_model = $modelMap[$activity->model_type] ?? 'Data';
                return $activity;
            });

        // Get today's activities
        $todayActivities = AuditLog::whereDate('created_at', today())
            ->count();

        // Get this week's activities
        $weekActivities = AuditLog::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        // Get this month's activities
        $monthActivities = AuditLog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get kantor by kota
        $kantorByKota = Kantor::with('kota')
            ->select('kota_id', DB::raw('count(*) as total'))
            ->groupBy('kota_id')
            ->get()
            ->map(function ($item) {
                return [
                    'kota' => $item->kota->nama_kota ?? 'Unknown',
                    'total' => $item->total
                ];
            });

        // Get gedung by kantor
        $gedungByKantor = Gedung::with('kantor')
            ->select('kantor_id', DB::raw('count(*) as total'))
            ->groupBy('kantor_id')
            ->get()
            ->map(function ($item) {
                return [
                    'kantor' => $item->kantor->nama_kantor ?? 'Unknown',
                    'total' => $item->total
                ];
            });

        // Get okupansi by bidang
        $okupansiByBidang = Okupansi::with('bidang')
            ->select('bidang_id', DB::raw('count(*) as total'))
            ->groupBy('bidang_id')
            ->get()
            ->map(function ($item) {
                return [
                    'bidang' => $item->bidang->nama_bidang ?? 'Unknown',
                    'total' => $item->total
                ];
            });

        // Get kontrak by status
        $kontrakByStatus = Kontrak::select('status_perjanjian', DB::raw('count(*) as total'))
            ->groupBy('status_perjanjian')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status_perjanjian,
                    'total' => $item->total
                ];
            });

        // Get kontrak by month (last 6 months) - use actual data
        $kontrakByMonth = Kontrak::select(
                DB::raw('MONTH(tanggal_mulai) as month'),
                DB::raw('YEAR(tanggal_mulai) as year'),
                DB::raw('count(*) as total'),
                DB::raw('sum(nilai_kontrak) as total_nilai')
            )
            ->where('tanggal_mulai', '>=', now()->subMonths(6))
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'year' => $item->year,
                    'total' => $item->total,
                    'total_nilai' => $item->total_nilai,
                    'month_name' => now()->month($item->month)->format('M')
                ];
            });

        // Get top kantor by gedung count
        $topKantorByGedung = Kantor::withCount('gedung')
            ->orderBy('gedung_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'kantor' => $item->nama_kantor,
                    'gedung_count' => $item->gedung_count
                ];
            });

        // Get top bidang by okupansi count
        $topBidangByOkupansi = Bidang::withCount('okupansi')
            ->orderBy('okupansi_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'bidang' => $item->nama_bidang,
                    'okupansi_count' => $item->okupansi_count
                ];
            });

        // Get analytics data for charts
        $analyticsData = [
            'kantor_by_status' => [
                'aktif' => Kantor::where('status_kantor', 'aktif')->count(),
                'non_aktif' => Kantor::where('status_kantor', 'non_aktif')->count()
            ],
            'gedung_by_status' => [
                'aktif' => Gedung::where('status_gedung', 'aktif')->count(),
                'non_aktif' => Gedung::where('status_gedung', 'non_aktif')->count()
            ],
            'kontrak_by_status' => [
                'aktif' => Kontrak::where('status_perjanjian', 'aktif')->count(),
                'selesai' => Kontrak::where('status_perjanjian', 'selesai')->count()
            ],
            'total_nilai_kontrak' => Kontrak::sum('nilai_kontrak'),
            'total_nilai_realisasi' => Realisasi::sum('rp_kompensasi'),
            'okupansi_by_bidang' => $okupansiByBidang,
            'kantor_by_kota' => $kantorByKota
        ];

        // Get kantor data for map - ensure we have coordinates
        $kantor = Kantor::with('kota.provinsi')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
            
        // If no kantor with coordinates, get all kantor for fallback
        if ($kantor->isEmpty()) {
            $kantor = Kantor::with('kota.provinsi')->get();
        }

        return view('dashboard.index', [
            'totalKantor' => $stats['total_kantor'],
            'totalGedung' => $stats['total_gedung'],
            'totalRuang' => $stats['total_ruang'],
            'totalKontrak' => $stats['total_kontrak'],
            'totalOkupansi' => $stats['total_okupansi'],
            'statusStats' => $statusStats,
            'recentActivities' => $recentActivities,
            'kantor' => $kantor,
            'kantorByKota' => $kantorByKota,
            'kontrakByMonth' => $kontrakByMonth,
            'kontrakByStatus' => $kontrakByStatus,
            'okupansiByBidang' => $okupansiByBidang,
            'topKantorByGedung' => $topKantorByGedung,
            'topBidangByOkupansi' => $topBidangByOkupansi,
            'analyticsData' => $analyticsData
        ]);
    }
}
