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
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Optimasi: Gunakan cache untuk stats yang jarang berubah (5 menit)
        $stats = \Illuminate\Support\Facades\Cache::remember('dashboard.stats', 300, function () {
            // Ambil semua count dalam parallel menggunakan DB::select untuk performa lebih baik
            $counts = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM kantor) as total_kantor,
                    (SELECT COUNT(*) FROM gedung) as total_gedung,
                    (SELECT COUNT(*) FROM lantai) as total_lantai,
                    (SELECT COUNT(*) FROM ruang) as total_ruang,
                    (SELECT COUNT(*) FROM okupansi) as total_okupansi,
                    (SELECT COUNT(*) FROM kontrak) as total_kontrak,
                    (SELECT COUNT(*) FROM realisasi) as total_realisasi,
                    (SELECT COUNT(*) FROM bidang) as total_bidang,
                    (SELECT COUNT(*) FROM sub_bidang) as total_sub_bidang,
                    (SELECT COUNT(*) FROM admin) as total_admin
            ");
            
            return [
                'total_kantor' => $counts[0]->total_kantor,
                'total_gedung' => $counts[0]->total_gedung,
                'total_lantai' => $counts[0]->total_lantai,
                'total_ruang' => $counts[0]->total_ruang,
                'total_okupansi' => $counts[0]->total_okupansi,
                'total_kontrak' => $counts[0]->total_kontrak,
                'total_realisasi' => $counts[0]->total_realisasi,
                'total_bidang' => $counts[0]->total_bidang,
                'total_sub_bidang' => $counts[0]->total_sub_bidang,
                'total_admin' => $counts[0]->total_admin,
            ];
        });

        // Optimasi: Combine status stats dalam single query
        $statusStats = \Illuminate\Support\Facades\Cache::remember('dashboard.status_stats', 300, function () {
            $kantorStats = DB::select("
                SELECT 
                    status_kepemilikan,
                    COUNT(*) as total
                FROM kantor
                WHERE status_kepemilikan IN ('milik', 'sewa')
                GROUP BY status_kepemilikan
            ");
            
            $gedungStats = DB::select("
                SELECT 
                    status_kepemilikan,
                    COUNT(*) as total
                FROM gedung
                WHERE status_kepemilikan IN ('milik', 'sewa')
                GROUP BY status_kepemilikan
            ");
            
            $kontrakStats = DB::select("
                SELECT 
                    status_perjanjian,
                    COUNT(*) as total
                FROM kontrak
                WHERE status_perjanjian IN ('aktif', 'selesai')
                GROUP BY status_perjanjian
            ");
            
            $result = [
                'kantor_milik' => 0,
                'kantor_sewa' => 0,
                'gedung_milik' => 0,
                'gedung_sewa' => 0,
                'kontrak_aktif' => 0,
                'kontrak_selesai' => 0,
            ];
            
            foreach ($kantorStats as $stat) {
                $result['kantor_' . $stat->status_kepemilikan] = $stat->total;
            }
            
            foreach ($gedungStats as $stat) {
                $result['gedung_' . $stat->status_kepemilikan] = $stat->total;
            }
            
            foreach ($kontrakStats as $stat) {
                $result['kontrak_' . $stat->status_perjanjian] = $stat->total;
            }
            
            return $result;
        });

        // Optimasi: Ambil aktivitas terbaru dengan cache (1 menit)
        $recentActivities = \Illuminate\Support\Facades\Cache::remember('dashboard.recent_activities', 60, function () {
            return AuditLog::leftJoin('admin', 'audit_logs.user_id', '=', 'admin.id')
                ->select('audit_logs.*', 'admin.nama_admin as user_name')
                ->orderBy('audit_logs.created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($activity) {
                    // Format tipe model jadi lebih user-friendly
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
        });

        // Optimasi: Combine activity counts dalam single query
        $activityCounts = \Illuminate\Support\Facades\Cache::remember('dashboard.activity_counts', 60, function () {
            $today = today();
            $weekStart = now()->startOfWeek();
            $weekEnd = now()->endOfWeek();
            $month = now()->month;
            $year = now()->year;
            
            $counts = DB::select("
                SELECT 
                    COUNT(CASE WHEN DATE(created_at) = ? THEN 1 END) as today,
                    COUNT(CASE WHEN created_at BETWEEN ? AND ? THEN 1 END) as week,
                    COUNT(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 END) as month
                FROM audit_logs
            ", [$today, $weekStart, $weekEnd, $month, $year]);
            
            return [
                'today' => $counts[0]->today ?? 0,
                'week' => $counts[0]->week ?? 0,
                'month' => $counts[0]->month ?? 0,
            ];
        });
        
        $todayActivities = $activityCounts['today'];
        $weekActivities = $activityCounts['week'];
        $monthActivities = $activityCounts['month'];

        // Optimasi: Ambil grouping data dengan join langsung (hindari N+1)
        $kantorByKota = \Illuminate\Support\Facades\Cache::remember('dashboard.kantor_by_kota', 300, function () {
            return DB::table('kantor')
                ->join('kota', 'kantor.kota_id', '=', 'kota.id')
                ->select('kota.nama_kota as kota', DB::raw('count(*) as total'))
                ->groupBy('kota.id', 'kota.nama_kota')
                ->get()
                ->map(function ($item) {
                    return [
                        'kota' => $item->kota ?? 'Unknown',
                        'total' => $item->total
                    ];
                });
        });

        $gedungByKantor = \Illuminate\Support\Facades\Cache::remember('dashboard.gedung_by_kantor', 300, function () {
            return DB::table('gedung')
                ->join('kantor', 'gedung.kantor_id', '=', 'kantor.id')
                ->select('kantor.nama_kantor as kantor', DB::raw('count(*) as total'))
                ->groupBy('kantor.id', 'kantor.nama_kantor')
                ->get()
                ->map(function ($item) {
                    return [
                        'kantor' => $item->kantor ?? 'Unknown',
                        'total' => $item->total
                    ];
                });
        });

        $okupansiByBidang = \Illuminate\Support\Facades\Cache::remember('dashboard.okupansi_by_bidang', 300, function () {
            return DB::table('okupansi')
                ->join('bidang', 'okupansi.bidang_id', '=', 'bidang.id')
                ->select('bidang.nama_bidang as bidang', DB::raw('count(*) as total'))
                ->groupBy('bidang.id', 'bidang.nama_bidang')
                ->get()
                ->map(function ($item) {
                    return [
                        'bidang' => $item->bidang ?? 'Unknown',
                        'total' => $item->total
                    ];
                });
        });

        // Optimasi: Cache kontrak by month (5 menit)
        $kontrakByMonth = \Illuminate\Support\Facades\Cache::remember('dashboard.kontrak_by_month', 300, function () {
            return DB::table('kontrak')
                ->select(
                    DB::raw('MONTH(tanggal_mulai) as month'),
                    DB::raw('YEAR(tanggal_mulai) as year'),
                    DB::raw('count(*) as total'),
                    DB::raw('COALESCE(SUM(nilai_kontrak), 0) as total_nilai')
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
        });

        // Optimasi: Cache top kantor (5 menit)
        $topKantorByGedung = \Illuminate\Support\Facades\Cache::remember('dashboard.top_kantor', 300, function () {
            return DB::table('kantor')
                ->leftJoin('gedung', 'kantor.id', '=', 'gedung.kantor_id')
                ->select('kantor.nama_kantor as kantor', DB::raw('COUNT(gedung.id) as gedung_count'))
                ->groupBy('kantor.id', 'kantor.nama_kantor')
                ->orderBy('gedung_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'kantor' => $item->kantor,
                        'gedung_count' => $item->gedung_count
                    ];
                });
        });

        // Optimasi: Cache top bidang (5 menit)
        $topBidangByOkupansi = \Illuminate\Support\Facades\Cache::remember('dashboard.top_bidang', 300, function () {
            return DB::table('bidang')
                ->leftJoin('okupansi', 'bidang.id', '=', 'okupansi.bidang_id')
                ->select('bidang.nama_bidang as bidang', DB::raw('COUNT(okupansi.id) as okupansi_count'))
                ->groupBy('bidang.id', 'bidang.nama_bidang')
                ->orderBy('okupansi_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'bidang' => $item->bidang,
                        'okupansi_count' => $item->okupansi_count
                    ];
                });
        });

        // Optimasi: Ambil kontrak by status (dari analyticsData nanti)
        $kontrakByStatus = Cache::remember('dashboard.kontrak_by_status', 300, function () {
            return DB::table('kontrak')
                ->select('status_perjanjian as status', DB::raw('count(*) as total'))
                ->groupBy('status_perjanjian')
                ->get()
                ->map(function ($item) {
                    return [
                        'status' => $item->status,
                        'total' => $item->total
                    ];
                });
        });

        // Optimasi: Ambil analytics data dengan cache dan combine queries
        $analyticsData = \Illuminate\Support\Facades\Cache::remember('dashboard.analytics', 300, function () use ($okupansiByBidang, $kantorByKota) {
            // Combine status counts dalam single queries
            $kantorStatus = DB::table('kantor')
                ->select('status_kantor', DB::raw('count(*) as total'))
                ->whereIn('status_kantor', ['aktif', 'non_aktif'])
                ->groupBy('status_kantor')
                ->get()
                ->pluck('total', 'status_kantor');
            
            $gedungStatus = DB::table('gedung')
                ->select('status_gedung', DB::raw('count(*) as total'))
                ->whereIn('status_gedung', ['aktif', 'non_aktif'])
                ->groupBy('status_gedung')
                ->get()
                ->pluck('total', 'status_gedung');
            
            $kontrakStatus = DB::table('kontrak')
                ->select('status_perjanjian', DB::raw('count(*) as total'))
                ->whereIn('status_perjanjian', ['aktif', 'selesai'])
                ->groupBy('status_perjanjian')
                ->get()
                ->pluck('total', 'status_perjanjian');
            
            // Get sums
            $sums = DB::select("
                SELECT 
                    (SELECT COALESCE(SUM(nilai_kontrak), 0) FROM kontrak) as total_nilai_kontrak,
                    (SELECT COALESCE(SUM(rp_kompensasi), 0) FROM realisasi) as total_nilai_realisasi
            ");
            
            return [
                'kantor_by_status' => [
                    'aktif' => $kantorStatus['aktif'] ?? 0,
                    'non_aktif' => $kantorStatus['non_aktif'] ?? 0
                ],
                'gedung_by_status' => [
                    'aktif' => $gedungStatus['aktif'] ?? 0,
                    'non_aktif' => $gedungStatus['non_aktif'] ?? 0
                ],
                'kontrak_by_status' => [
                    'aktif' => $kontrakStatus['aktif'] ?? 0,
                    'selesai' => $kontrakStatus['selesai'] ?? 0
                ],
                'total_nilai_kontrak' => $sums[0]->total_nilai_kontrak ?? 0,
                'total_nilai_realisasi' => $sums[0]->total_nilai_realisasi ?? 0,
                'okupansi_by_bidang' => $okupansiByBidang,
                'kantor_by_kota' => $kantorByKota
            ];
        });

        // Optimasi: Cache kantor untuk map (5 menit) - hanya ambil kolom yang diperlukan
        $kantor = \Illuminate\Support\Facades\Cache::remember('dashboard.kantor_map', 300, function () {
            $kantor = DB::table('kantor')
                ->join('kota', 'kantor.kota_id', '=', 'kota.id')
                ->leftJoin('provinsi', 'kota.provinsi_id', '=', 'provinsi.id')
                ->select(
                    'kantor.id',
                    'kantor.nama_kantor',
                    'kantor.latitude',
                    'kantor.longitude',
                    'kota.nama_kota',
                    'provinsi.nama_provinsi'
                )
                ->whereNotNull('kantor.latitude')
                ->whereNotNull('kantor.longitude')
                ->get();
            
            // Kalau gak ada kantor dengan koordinat, ambil semua kantor untuk fallback
            if ($kantor->isEmpty()) {
                $kantor = DB::table('kantor')
                    ->join('kota', 'kantor.kota_id', '=', 'kota.id')
                    ->leftJoin('provinsi', 'kota.provinsi_id', '=', 'provinsi.id')
                    ->select(
                        'kantor.id',
                        'kantor.nama_kantor',
                        'kantor.latitude',
                        'kantor.longitude',
                        'kota.nama_kota',
                        'provinsi.nama_provinsi'
                    )
                    ->get();
            }
            
            return $kantor;
        });

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
