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
use App\Models\Inventaris;

class PublicController extends Controller
{
    /**
     * Homepage - Landing Page
     */
    public function home()
    {
        // Optimasi: Cache stats (5 menit)
        $stats = \Illuminate\Support\Facades\Cache::remember('public.home.stats', 300, function () {
            $counts = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM kantor) as total_kantor,
                    (SELECT COUNT(*) FROM gedung) as total_gedung,
                    (SELECT COUNT(*) FROM ruang) as total_ruang
            ");
            
            return [
                'total_kantor' => $counts[0]->total_kantor,
                'total_gedung' => $counts[0]->total_gedung,
                'total_ruang' => $counts[0]->total_ruang,
            ];
        });

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
     * Bantuan / Help
     */
    public function help()
    {
        return view('public.help');
    }

    /**
     * Profil Perusahaan - About Page
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Scan Barcode - Halaman untuk scan barcode inventaris
     */
    public function scanBarcode()
    {
        return view('public.scan-barcode');
    }

    /**
     * Scan Result - Hasil scan barcode, redirect ke peta dengan auto-open modal
     */
    public function scanResult(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        // Format barcode: KODE_INVENTARIS|NAMA_KANTOR
        $parts = explode('|', $request->code);
        
        if (count($parts) < 2) {
            return redirect()->route('public.peta')
                ->with('error', 'Format barcode tidak valid.');
        }

        $kodeInventaris = $parts[0];
        $namaKantor = $parts[1];

        // Cari inventaris berdasarkan kode
        $inventaris = Inventaris::with(['kantor:id,nama_kantor'])
            ->where('kode_inventaris', $kodeInventaris)
            ->first();

        if (!$inventaris) {
            return redirect()->route('public.peta')
                ->with('error', 'Inventaris tidak ditemukan.');
        }

        // Verifikasi nama kantor sesuai
        if ($inventaris->kantor && $inventaris->kantor->nama_kantor !== $namaKantor) {
            return redirect()->route('public.peta')
                ->with('error', 'Data inventaris tidak sesuai.');
        }

        // Redirect ke peta dengan parameter untuk auto-open modal dan highlight inventaris
        return redirect()->route('public.peta', [
            'kantor_id' => $inventaris->lokasi_kantor_id,
            'inventaris_id' => $inventaris->id,
            'tab' => 'inventaris'
        ])->with('scan_success', 'Barcode berhasil di-scan!');
    }


    /**
     * API untuk data kantor (untuk peta)
     */
    public function getKantorData()
    {
        try {
            // Optimasi: Cache data kantor (10 menit) dengan select specific columns
            $kantor = \Illuminate\Support\Facades\Cache::remember('public.kantor_data', 600, function () {
                try {
                    // Select hanya kolom yang ada di database (gunakan get() tanpa select untuk aman)
                    $kantors = Kantor::with([
                        'kota:id,nama_kota',
                        'jenisKantor:id,nama_jenis',
                        'gedung:id,kantor_id,layout_path'
                    ])
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->get();
                    
                    // Map data dengan helper function untuk formatParentKantor
                    return $kantors->map(function($kantor) {
                        // Ambil layout_url dari gedung pertama yang punya layout_path
                        $layoutUrl = null;
                        if ($kantor->gedung && $kantor->gedung->isNotEmpty()) {
                            $gedungWithLayout = $kantor->gedung->firstWhere('layout_path', '!=', null);
                            if ($gedungWithLayout && $gedungWithLayout->layout_path) {
                                // Pastikan path sudah benar (storage path)
                                $layoutPath = $gedungWithLayout->layout_path;
                                if (strpos($layoutPath, 'storage/') === 0) {
                                    $layoutUrl = asset($layoutPath);
                                } elseif (strpos($layoutPath, '/') === 0) {
                                    $layoutUrl = asset('storage' . $layoutPath);
                                } else {
                                    $layoutUrl = asset('storage/' . $layoutPath);
                                }
                            }
                        }
                        
                        // Format parent kantor inline (tidak perlu $this)
                        $parentKantor = '-';
                        if ($kantor->sbu_type && $kantor->sbu) {
                            $parentKantor = $kantor->sbu;
                        } elseif ($kantor->sbu_type) {
                            $parentKantor = $kantor->sbu_type;
                        } elseif ($kantor->sbu) {
                            $parentKantor = $kantor->sbu;
                        }
                        
                        return [
                            'id' => $kantor->id,
                            'nama_kantor' => $kantor->nama_kantor ?? '',
                            'kode_kantor' => $kantor->kode_kantor ?? '',
                            'jenis_id' => $kantor->jenis_kantor_id ?? null,
                            'jenis' => $kantor->jenisKantor ? $kantor->jenisKantor->nama_jenis : '-',
                            'latitude' => (float) ($kantor->latitude ?? 0),
                            'longitude' => (float) ($kantor->longitude ?? 0),
                            'kota' => $kantor->kota ? $kantor->kota->nama_kota : '',
                            'status_kepemilikan' => $kantor->status_kepemilikan ?? '',
                            'jenis_kepemilikan' => $kantor->jenis_kepemilikan ?? '',
                            'luas_tanah' => $kantor->luas_tanah ?? null,
                            'luas_bangunan' => $kantor->luas_bangunan ?? null,
                            'daya_listrik_va' => $kantor->daya_listrik_va ?? null,
                            'kapasitas_genset_kva' => $kantor->kapasitas_genset_kva ?? null,
                            'jumlah_sumur' => $kantor->jumlah_sumur ?? null,
                            'jumlah_septictank' => $kantor->jumlah_septictank ?? null,
                            'parent_kantor' => $parentKantor,
                            'asset_owner' => isset($kantor->asset_owner) ? $kantor->asset_owner : '',
                            'ruang_lingkup' => isset($kantor->ruang_lingkup) ? $kantor->ruang_lingkup : '-',
                            'peruntukan_kantor' => isset($kantor->peruntukan_kantor) ? $kantor->peruntukan_kantor : '-',
                            'alamat' => $kantor->alamat ?? '-',
                            'keterangan' => isset($kantor->keterangan) ? $kantor->keterangan : '-',
                            'layout_url' => $layoutUrl
                        ];
                    });
                } catch (\Exception $e) {
                    \Log::error('Error in getKantorData cache closure: ' . $e->getMessage());
                    \Log::error($e->getTraceAsString());
                    return [];
                }
            });

            return response()->json($kantor ?? []);
        } catch (\Exception $e) {
            \Log::error('Error in getKantorData: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => 'Failed to load kantor data'], 500);
        }
    }

    /**
     * API untuk data inventaris berdasarkan kantor
     */
    public function getInventarisData($kantorId)
    {
        // Optimasi: Select specific columns (cache disabled sementara untuk debugging)
        $inventaris = \App\Models\Inventaris::with([
            'kategori:id,nama_kategori',
            'gedung:id,nama_gedung',
            'lantai:id,nama_lantai,nomor_lantai',
            'ruang:id,nama_ruang',
            'bidang:id,nama_bidang'
        ])
        ->where('lokasi_kantor_id', $kantorId)
        ->select('id', 'nama_barang', 'kode_inventaris', 'kategori_id', 'jumlah', 'kondisi', 
                 'lokasi_gedung_id', 'lokasi_lantai_id', 'lokasi_ruang_id', 'bidang_id',
                 'tanggal_input', 'harga', 'merk', 'tahun', 'tanggal_pembelian', 'deskripsi', 'gambar')
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
                'lokasi_lantai_nomor' => $item->lantai->nomor_lantai ?? null,
                'lokasi_ruang' => $item->ruang->nama_ruang ?? '',
                'bidang' => $item->bidang->nama_bidang ?? '',
                'tanggal_input' => $item->tanggal_input->format('d/m/Y'),
                'harga' => $item->harga,
                'merk' => $item->merk,
                'tahun' => $item->tahun,
                'tanggal_pembelian' => $item->tanggal_pembelian,
                'deskripsi' => $item->deskripsi,
                'gambar' => $item->gambar ? asset($item->gambar) : null,
            ];
        });

        return response()->json($inventaris);
    }

    /**
     * API untuk data kontrak berdasarkan kantor
     */
    public function getKontrakData(Request $request, $kantorId)
    {
        $status = $request->get('status', null); // Allow null untuk ambil semua

        // Optimasi: Select specific columns dengan error handling
        try {
            $query = \App\Models\Kontrak::with(['kantor:id,nama_kantor'])
                ->where('kantor_id', $kantorId)
                ->select('id', 'nama_perjanjian', 'no_perjanjian_pihak_1', 'no_perjanjian_pihak_2',
                         'tanggal_mulai', 'tanggal_selesai', 'nilai_kontrak', 'status_perjanjian', 'status',
                         'kantor_id', 'sbu', 'asset_owner', 'ruang_lingkup', 
                         'peruntukan_kantor', 'alamat', 'keterangan', 'parent_kantor');

            // Filter by status jika ada, jika tidak ambil semua
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }

            $kontrak = $query->get()->map(function($k) {
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
                    'parent_kantor' => $this->formatParentKantor(null, $k->sbu),
                    'asset_owner' => $k->asset_owner,
                    'ruang_lingkup' => $k->ruang_lingkup ?? '-',
                    'peruntukan_kantor' => $k->peruntukan_kantor ?? '-',
                    'alamat' => $k->alamat ?? '-',
                    'keterangan' => $k->keterangan ?? '-'
                ];
            });

            return response()->json($kontrak);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error loading kontrak data', [
                'kantor_id' => $kantorId,
                'status' => $status,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Gagal memuat data kontrak',
                'message' => $e->getMessage()
            ], 500);
        }
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
        // Optimasi: Query langsung (cache disabled sementara untuk debugging)
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
            'total_organik' => (int) ($employeeStats->total_organik ?? 0),
            'total_tad' => (int) ($employeeStats->total_tad ?? 0), 
            'total_kontrak' => (int) ($employeeStats->total_kontrak ?? 0),
            'total_all' => (int) (($employeeStats->total_organik ?? 0) + ($employeeStats->total_tad ?? 0) + ($employeeStats->total_kontrak ?? 0))
        ]);
    }

    /**
     * Get Laporan Inventaris Data for specific kantor
     */
    public function getLaporanInventarisData($kantorId, Request $request)
    {
        $kategoriId = $request->get('kategori_id');
        $searchBarang = $request->get('search_barang');
        
        // Optimasi: Select specific columns (cache disabled sementara untuk debugging)
        // Ambil opsi kategori dari database
        $kategoriOptions = \App\Models\KategoriInventaris::select('id', 'nama_kategori')->get();
        
        // Ambil opsi daftar barang dari database
        $barangOptions = \App\Models\Inventaris::where('lokasi_kantor_id', $kantorId)
            ->select('nama_barang')
            ->distinct()
            ->orderBy('nama_barang')
            ->pluck('nama_barang');
        
        $query = \App\Models\Inventaris::with([
            'kategori:id,nama_kategori',
            'gedung:id,nama_gedung',
            'lantai:id,nama_lantai,nomor_lantai',
            'ruang:id,nama_ruang',
            'bidang:id,nama_bidang'
        ])
        ->where('lokasi_kantor_id', $kantorId)
        ->select('id', 'nama_barang', 'kode_inventaris', 'jumlah', 'kondisi', 'merk', 'harga', 
                 'tahun', 'tanggal_pembelian', 'deskripsi', 'kategori_id', 
                 'lokasi_gedung_id', 'lokasi_lantai_id', 'lokasi_ruang_id', 'bidang_id');
        
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }
        
        if ($searchBarang) {
            $query->where('nama_barang', 'LIKE', '%' . $searchBarang . '%');
        }
        
        $inventaris = $query->get()
            ->groupBy(function($item) {
                $gedungName = $item->gedung->nama_gedung ?? 'Gedung';
                $lantaiLabel = $this->formatLantaiLabel($item->lantai);
                return trim($gedungName . ' - ' . $lantaiLabel);
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
                    'lantai_label' => $this->formatLantaiLabel($item->lantai),
                    'lantai_nomor' => $item->lantai->nomor_lantai ?? null,
                    'ruang' => $item->ruang->nama_ruang ?? '',
                    'bidang' => $item->bidang->nama_bidang ?? ''
                ];
                });
            })
            ->sortBy(function($items, $lokasi) {
                // Sort by nomor_lantai (extract from first item in group)
                $firstItem = $items->first();
                if ($firstItem && $firstItem['lantai_nomor'] !== null) {
                    return (int)$firstItem['lantai_nomor'];
                }
                return 9999; // Put items without nomor_lantai at the end
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

    /**
     * Format label lantai (nama + nomor)
     */
    private function formatLantaiLabel($lantai): string
    {
        if (!$lantai) {
            return '-';
        }

        $nama = $lantai->nama_lantai ?? '';
        $nomor = $lantai->nomor_lantai ?? '';

        if ($nama && $nomor) {
            return "{$nama} (Lantai {$nomor})";
        }

        if ($nama) {
            return $nama;
        }

        if ($nomor) {
            return "Lantai {$nomor}";
        }

        return '-';
    }
}
