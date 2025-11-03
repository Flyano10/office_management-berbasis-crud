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
        // Ambil statistik dasar - optimasi dengan single query
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

        // Ambil statistik status - optimasi dengan single query
        $statusStats = [
            'kantor_milik' => Kantor::where('status_kepemilikan', 'milik')->count(),
            'kantor_sewa' => Kantor::where('status_kepemilikan', 'sewa')->count(),
            'gedung_milik' => Gedung::where('status_kepemilikan', 'milik')->count(),
            'gedung_sewa' => Gedung::where('status_kepemilikan', 'sewa')->count(),
            'kontrak_aktif' => Kontrak::where('status_perjanjian', 'aktif')->count(),
            'kontrak_selesai' => Kontrak::where('status_perjanjian', 'selesai')->count(),
        ];

        // Ambil aktivitas terbaru
        $recentActivities = AuditLog::leftJoin('admin', 'audit_logs.user_id', '=', 'admin.id')
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

        // Ambil aktivitas hari ini
        $todayActivities = AuditLog::whereDate('created_at', today())
            ->count();

        // Ambil aktivitas minggu ini
        $weekActivities = AuditLog::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        // Ambil aktivitas bulan ini
        $monthActivities = AuditLog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Ambil kantor berdasarkan kota
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

        // Ambil gedung berdasarkan kantor
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

        // Ambil okupansi berdasarkan bidang
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

        // Ambil kontrak berdasarkan status
        $kontrakByStatus = Kontrak::select('status_perjanjian', DB::raw('count(*) as total'))
            ->groupBy('status_perjanjian')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status_perjanjian,
                    'total' => $item->total
                ];
            });

        // Ambil kontrak berdasarkan bulan (6 bulan terakhir) - gunakan data asli
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

        // Ambil kantor teratas berdasarkan jumlah gedung
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

        // Ambil bidang teratas berdasarkan jumlah okupansi
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

        // Ambil data analytics untuk chart
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

        // Ambil data kantor untuk map - pastikan ada koordinat
        $kantor = Kantor::with('kota.provinsi')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
            
        // Kalau gak ada kantor dengan koordinat, ambil semua kantor untuk fallback
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
