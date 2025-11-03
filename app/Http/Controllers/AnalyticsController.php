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
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        // Statistik dasar
        $stats = [
            'total_kantor' => Kantor::count(),
            'total_gedung' => Gedung::count(),
            'total_lantai' => Lantai::count(),
            'total_ruang' => Ruang::count(),
            'total_kontrak' => Kontrak::count(),
            'total_realisasi' => Realisasi::count(),
            'total_bidang' => Bidang::count(),
            'total_sub_bidang' => SubBidang::count(),
        ];

        // Statistik okupansi
        $okupansiStats = $this->getOkupansiStats();
        
        // Kantor berdasarkan status
        $kantorByStatus = Kantor::select('status_kantor', DB::raw('count(*) as total'))
            ->groupBy('status_kantor')
            ->get()
            ->pluck('total', 'status_kantor');

        // Gedung berdasarkan status
        $gedungByStatus = Gedung::select('status_gedung', DB::raw('count(*) as total'))
            ->groupBy('status_gedung')
            ->get()
            ->pluck('total', 'status_gedung');

        // Kontrak berdasarkan status
        $kontrakByStatus = Kontrak::select('status_perjanjian', DB::raw('count(*) as total'))
            ->groupBy('status_perjanjian')
            ->get()
            ->pluck('total', 'status_perjanjian');

        // Ruang berdasarkan status
        $ruangByStatus = Ruang::select('status_ruang', DB::raw('count(*) as total'))
            ->groupBy('status_ruang')
            ->get()
            ->pluck('total', 'status_ruang');

        // Okupansi berdasarkan bidang
        $okupansiByBidang = Okupansi::with('bidang')
            ->select('bidang_id', DB::raw('sum(total_pegawai) as total_pegawai'))
            ->groupBy('bidang_id')
            ->get()
            ->map(function($item) {
                return [
                    'bidang' => $item->bidang->nama_bidang ?? 'N/A',
                    'total_pegawai' => $item->total_pegawai
                ];
            });

        // Aktivitas terbaru
        $recentKontrak = Kontrak::with('kantor')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentRealisasi = Realisasi::with('kontrak')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('analytics.index', compact(
            'stats',
            'okupansiStats',
            'kantorByStatus',
            'gedungByStatus',
            'kontrakByStatus',
            'ruangByStatus',
            'okupansiByBidang',
            'recentKontrak',
            'recentRealisasi'
        ));
    }

    /**
     * Get okupansi statistics
     */
    private function getOkupansiStats()
    {
        $okupansi = Okupansi::select(
            DB::raw('sum(jml_pegawai_organik) as total_organik'),
            DB::raw('sum(jml_pegawai_tad) as total_tad'),
            DB::raw('sum(jml_pegawai_kontrak) as total_kontrak'),
            DB::raw('sum(total_pegawai) as total_pegawai'),
            DB::raw('avg(persentase_okupansi) as avg_okupansi')
        )->first();

        return [
            'total_organik' => $okupansi->total_organik ?? 0,
            'total_tad' => $okupansi->total_tad ?? 0,
            'total_kontrak' => $okupansi->total_kontrak ?? 0,
            'total_pegawai' => $okupansi->total_pegawai ?? 0,
            'avg_okupansi' => round($okupansi->avg_okupansi ?? 0, 2)
        ];
    }

    /**
     * Get analytics data as JSON for charts
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'overview');

        switch ($type) {
            case 'okupansi':
                return $this->getOkupansiChartData();
            case 'kantor':
                return $this->getKantorChartData();
            case 'kontrak':
                return $this->getKontrakChartData();
            default:
                return $this->getOverviewChartData();
        }
    }

    private function getOverviewChartData()
    {
        return response()->json([
            'kantor' => Kantor::count(),
            'gedung' => Gedung::count(),
            'ruang' => Ruang::count(),
            'kontrak' => Kontrak::count(),
            'realisasi' => Realisasi::count()
        ]);
    }

    private function getOkupansiChartData()
    {
        $data = Okupansi::with('bidang')
            ->select('bidang_id', DB::raw('sum(total_pegawai) as total'))
            ->groupBy('bidang_id')
            ->get()
            ->map(function($item) {
                return [
                    'label' => $item->bidang->nama_bidang ?? 'N/A',
                    'value' => $item->total
                ];
            });

        return response()->json($data);
    }

    private function getKantorChartData()
    {
        $data = Kantor::select('status_kantor', DB::raw('count(*) as total'))
            ->groupBy('status_kantor')
            ->get()
            ->map(function($item) {
                return [
                    'label' => ucfirst($item->status_kantor),
                    'value' => $item->total
                ];
            });

        return response()->json($data);
    }

    private function getKontrakChartData()
    {
        $data = Kontrak::select('status_perjanjian', DB::raw('count(*) as total'))
            ->groupBy('status_perjanjian')
            ->get()
            ->map(function($item) {
                return [
                    'label' => ucfirst($item->status_perjanjian),
                    'value' => $item->total
                ];
            });

        return response()->json($data);
    }
}
