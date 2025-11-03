<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kantor;
use App\Models\Kota;
use App\Models\JenisKantor;
use App\Models\Gedung;
use App\Models\Ruang;
use Illuminate\Support\Facades\DB;
use App\Models\Kontrak;

class PublicController extends Controller
{
    /**
     * Homepage - Landing Page
     */
    public function home()
    {
        // Ambil statistik cepat untuk ditampilkan di homepage
        $stats = [
            'total_kantor' => Kantor::count(),
            'total_gedung' => Gedung::count(),
            'total_ruang' => Ruang::count(),
        ];

        return view('public.home', compact('stats'));
    }

    /**
     * Peta - Interactive Map
     */
    public function peta()
    {
        return view('public.peta');
    }

    /**
     * Directory Kantor
     */
    public function directory(Request $request)
    {
        $query = Kantor::with(['kota', 'jenisKantor']);

        // Fungsi pencarian kantor berdasarkan nama atau alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kantor', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter kantor berdasarkan kota
        if ($request->filled('kota_id')) {
            $query->where('kota_id', $request->kota_id);
        }

        // Filter kantor berdasarkan jenis kantor
        if ($request->filled('jenis_kantor_id')) {
            $query->where('jenis_kantor_id', $request->jenis_kantor_id);
        }

        // Urutkan kantor berdasarkan nama kantor
        $query->orderBy('nama_kantor', 'asc');

        $kantor = $query->paginate(20)->appends(request()->query());

        // Ambil opsi filter untuk dropdown
        $kota = Kota::orderBy('nama_kota')->get();
        $jenisKantor = JenisKantor::orderBy('nama_jenis')->get();

        return view('public.directory', compact('kantor', 'kota', 'jenisKantor'));
    }


    /**
     * API untuk data kantor (untuk peta)
     */
    public function getKantorData()
    {
        $kantor = Kantor::with(['kota', 'jenisKantor'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function($k) {
                return [
                    'id' => $k->id,
                    'nama_kantor' => $k->nama_kantor,
                    'alamat' => $k->alamat,
                    'telepon' => $k->telepon,
                    'email' => $k->email,
                    'latitude' => $k->latitude,
                    'longitude' => $k->longitude,
                    'kota' => $k->kota->nama_kota ?? '',
                    'jenis' => $k->jenisKantor->nama_jenis ?? ''
                ];
            });

        return response()->json($kantor);
    }

    /**
     * API untuk data inventaris berdasarkan kantor
     */
    public function getInventarisData($kantorId)
    {
        $inventaris = \App\Models\Inventaris::with(['kategori', 'gedung', 'lantai', 'ruang', 'bidang'])
            ->where('lokasi_kantor_id', $kantorId)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'kode_inventaris' => $item->kode_inventaris,
                    'kategori' => $item->kategori->nama_kategori ?? '',
                    'jumlah' => $item->jumlah,
                    'kondisi' => $item->kondisi,
                    'lokasi_gedung' => $item->gedung->nama_gedung ?? '',
                    'lokasi_lantai' => $item->lantai->nama_lantai ?? '',
                    'lokasi_ruang' => $item->ruang->nama_ruang ?? '',
                    'bidang' => $item->bidang->nama_bidang ?? '',
                    'tanggal_input' => $item->tanggal_input->format('d/m/Y'),
                    'gambar' => $item->gambar ? asset($item->gambar) : null,
                    'deskripsi' => $item->deskripsi
                ];
            });

        return response()->json($inventaris);
    }

    /**
     * API untuk data kontrak berdasarkan kantor
     */
    public function getKontrakData(Request $request, $kantorId)
    {
        $status = $request->get('status', 'Aktif');

        $query = \App\Models\Kontrak::with(['kantor'])
            ->where('kantor_id', $kantorId);

        if ($status) {
            $query->where('status', $status);
        }

        $kontrak = $query
            ->get()
            ->map(function($k) {
                // Hitung durasi kontrak dalam hari
                $durasi = 0;
                if ($k->tanggal_mulai && $k->tanggal_selesai) {
                    $mulai = \Carbon\Carbon::parse($k->tanggal_mulai);
                    $selesai = \Carbon\Carbon::parse($k->tanggal_selesai);
                    $durasi = $mulai->diffInDays($selesai);
                }

                // Sisa hari menuju tanggal selesai (bisa negatif jika lewat)
                $daysToEnd = null;
                if ($k->tanggal_selesai) {
                    $daysToEnd = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($k->tanggal_selesai)->endOfDay(), false);
                }

                return [
                    'id' => $k->id,
                    'nama_perjanjian' => $k->nama_perjanjian,
                    'no_perjanjian_pihak_1' => $k->no_perjanjian_pihak_1,
                    'no_perjanjian_pihak_2' => $k->no_perjanjian_pihak_2,
                    'tanggal_mulai' => $k->tanggal_mulai ? \Carbon\Carbon::parse($k->tanggal_mulai)->format('d/m/Y') : '',
                    'tanggal_selesai' => $k->tanggal_selesai ? \Carbon\Carbon::parse($k->tanggal_selesai)->format('d/m/Y') : '',
                    'durasi_hari' => $durasi,
                    'days_to_end' => $daysToEnd,
                    'nilai_kontrak' => $k->nilai_kontrak ? 'Rp ' . number_format($k->nilai_kontrak, 0, ',', '.') : '-',
                    'status_perjanjian' => $k->status_perjanjian,
                    'status' => $k->status ?? 'Aktif',
                    'kantor' => $k->kantor->nama_kantor ?? '',
                    'parent_kantor' => $this->formatParentKantor($k->sbu_type, $k->sbu),
                    'asset_owner' => $k->asset_owner,
                    'ruang_lingkup' => $k->ruang_lingkup ?? '-',
                    'peruntukan_kantor' => $k->peruntukan_kantor ?? '-',
                    'alamat' => $k->alamat ?? '-',
                    'keterangan' => $k->keterangan ?? '-'
                ];
            });

        return response()->json($kontrak);
    }

    /**
     * API: Kontrak yang mendekati jatuh tempo untuk halaman publik peta
     * /api/kontrak-expiring/{kantorId}?window=all|6|3|1
     */
    public function getExpiringContracts($kantorId, Request $request)
    {
        $window = $request->get('window', 'all');

        $select = ['id','nama_perjanjian','tanggal_mulai','tanggal_selesai','status_perjanjian','status','kantor_id'];

        $result = [
            'success' => true,
            'window' => $window,
            'data' => [],
            'counts' => [ 'm6' => 0, 'm3' => 0, 'm1' => 0 ]
        ];

        if (in_array((string) $window, ['6','3','1'], true)) {
            $months = (int) $window;
            $items = Kontrak::select($select)
                ->expiringWithinMonths($months, (int)$kantorId)
                ->orderBy('tanggal_selesai','asc')
                ->get()
                ->map(function($k){
                    return [
                        'id' => $k->id,
                        'nama_perjanjian' => $k->nama_perjanjian,
                        'tanggal_mulai' => optional($k->tanggal_mulai)->format('Y-m-d'),
                        'tanggal_selesai' => optional($k->tanggal_selesai)->format('Y-m-d'),
                        'days_to_end' => $k->days_to_end,
                        'status' => $k->status,
                        'status_perjanjian' => $k->status_perjanjian,
                    ];
                });
            $result['data'] = $items;
            return response()->json($result);
        }

        // window=all: grouped preview and counts
        foreach ([6=>'m6',3=>'m3',1=>'m1'] as $months => $key) {
            $items = Kontrak::select($select)
                ->expiringWithinMonths($months, (int)$kantorId)
                ->orderBy('tanggal_selesai','asc')
                ->limit(10)
                ->get();
            $result['counts'][$key] = $items->count();
            $result['data'][$key] = $items->map(function($k){
                return [
                    'id' => $k->id,
                    'nama_perjanjian' => $k->nama_perjanjian,
                    'tanggal_mulai' => optional($k->tanggal_mulai)->format('Y-m-d'),
                    'tanggal_selesai' => optional($k->tanggal_selesai)->format('Y-m-d'),
                    'days_to_end' => $k->days_to_end,
                    'status' => $k->status,
                    'status_perjanjian' => $k->status_perjanjian,
                ];
            });
        }

        return response()->json($result);
    }

    /**
     * Get Employee Data for specific kantor from okupansi
     */
    public function getEmployeeData($kantorId)
    {
        $employeeStats = \App\Models\Okupansi::join('ruang', 'okupansi.ruang_id', '=', 'ruang.id')
            ->join('lantai', 'ruang.lantai_id', '=', 'lantai.id')
            ->join('gedung', 'lantai.gedung_id', '=', 'gedung.id')
            ->where('gedung.kantor_id', $kantorId)
            ->selectRaw('
                COALESCE(SUM(jml_pegawai_organik), 0) as total_organik,
                COALESCE(SUM(jml_pegawai_tad), 0) as total_tad,
                COALESCE(SUM(jml_pegawai_kontrak), 0) as total_kontrak
            ')
            ->first();

        return response()->json([
            'total_organik' => (int) $employeeStats->total_organik,
            'total_tad' => (int) $employeeStats->total_tad, 
            'total_kontrak' => (int) $employeeStats->total_kontrak,
            'total_all' => (int) ($employeeStats->total_organik + $employeeStats->total_tad + $employeeStats->total_kontrak)
        ]);
    }

    /**
     * Get Laporan Inventaris Data for specific kantor
     */
    public function getLaporanInventarisData($kantorId, Request $request)
    {
        $kategoriId = $request->get('kategori_id');
        $searchBarang = $request->get('search_barang');
        
        // Ambil opsi kategori dari database
        $kategoriOptions = \App\Models\KategoriInventaris::all();
        
        // Ambil opsi daftar barang dari database
        $barangOptions = \App\Models\Inventaris::where('lokasi_kantor_id', $kantorId)
            ->select('nama_barang')
            ->distinct()
            ->orderBy('nama_barang')
            ->pluck('nama_barang');
        
        $query = \App\Models\Inventaris::with(['kategori', 'gedung', 'lantai', 'ruang', 'bidang'])
            ->where('lokasi_kantor_id', $kantorId);
        
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }
        
        if ($searchBarang) {
            $query->where('nama_barang', 'LIKE', '%' . $searchBarang . '%');
        }
        
        $inventaris = $query->get()
            ->groupBy(function($item) {
                return $item->gedung->nama_gedung . ' - ' . $item->lantai->nama_lantai;
            })
            ->map(function($items, $lokasi) {
                return $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'kode_inventaris' => $item->kode_inventaris,
                    'jumlah' => $item->jumlah,
                    'kondisi' => $item->kondisi,
                    'merk' => $item->merk,
                    'harga' => $item->harga,
                    'tahun' => $item->tahun,
                    'tanggal_pembelian' => $item->tanggal_pembelian,
                    'deskripsi' => $item->deskripsi,
                    'kategori' => $item->kategori->nama_kategori ?? '',
                    'gedung' => $item->gedung->nama_gedung ?? '',
                    'lantai' => $item->lantai->nama_lantai ?? '',
                    'ruang' => $item->ruang->nama_ruang ?? '',
                    'bidang' => $item->bidang->nama_bidang ?? ''
                ];
                });
            });
        
        return response()->json([
            'inventaris' => $inventaris,
            'kategori_options' => $kategoriOptions,
            'barang_options' => $barangOptions
        ]);
    }

    /**
     * Format Parent Kantor untuk display
     */
    private function formatParentKantor($sbuType, $sbu)
    {
        if (!$sbuType && !$sbu) {
            return '-';
        }
        
        if ($sbuType && $sbu) {
            return $sbu; // Return the full SBU name
        }
        
        if ($sbuType) {
            return $sbuType;
        }
        
        return $sbu ?? '-';
    }
}
