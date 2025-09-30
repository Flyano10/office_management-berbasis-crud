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

class SearchController extends Controller
{
    /**
     * Global search across all models
     */
    public function globalSearch(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $type = $request->get('type', 'all');
            
            if (empty($query)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Query tidak boleh kosong'
                ]);
            }

            $results = collect();

            // Search based on type
            if ($type === 'all' || $type === 'kantor') {
                $kantorResults = $this->searchKantor($query);
                $results = $results->merge($kantorResults);
            }

            if ($type === 'all' || $type === 'gedung') {
                $gedungResults = $this->searchGedung($query);
                $results = $results->merge($gedungResults);
            }

            if ($type === 'all' || $type === 'ruang') {
                $ruangResults = $this->searchRuang($query);
                $results = $results->merge($ruangResults);
            }

            if ($type === 'all' || $type === 'kontrak') {
                $kontrakResults = $this->searchKontrak($query);
                $results = $results->merge($kontrakResults);
            }

            if ($type === 'all' || $type === 'realisasi') {
                $realisasiResults = $this->searchRealisasi($query);
                $results = $results->merge($realisasiResults);
            }

            if ($type === 'all' || $type === 'bidang') {
                $bidangResults = $this->searchBidang($query);
                $results = $results->merge($bidangResults);
            }

            // Sort by relevance (exact matches first)
            $results = $results->sortByDesc(function($item) use ($query) {
                $score = 0;
                $searchFields = $item['search_fields'] ?? [];
                
                foreach ($searchFields as $field) {
                    if (stripos($field, $query) !== false) {
                        $score += 1;
                        if (stripos($field, $query) === 0) {
                            $score += 2; // Exact start match gets higher score
                        }
                    }
                }
                
                return $score;
            });

            return response()->json([
                'success' => true,
                'query' => $query,
                'total' => $results->count(),
                'results' => $results->values()->take(20) // Limit to 20 results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search Kantor
     */
    private function searchKantor($query)
    {
        return Kantor::with(['kota', 'jenisKantor'])
            ->where(function($q) use ($query) {
                $q->where('nama_kantor', 'like', "%{$query}%")
                  ->orWhere('kode_kantor', 'like', "%{$query}%")
                  ->orWhere('alamat', 'like', "%{$query}%");
            })
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'kantor',
                    'title' => $item->nama_kantor,
                    'subtitle' => $item->kode_kantor,
                    'description' => $item->alamat,
                    'url' => route('kantor.show', $item->id),
                    'search_fields' => [
                        $item->nama_kantor,
                        $item->kode_kantor,
                        $item->alamat,
                        $item->kota->nama_kota ?? '',
                        $item->jenisKantor->nama_jenis ?? ''
                    ]
                ];
            });
    }

    /**
     * Search Gedung
     */
    private function searchGedung($query)
    {
        return Gedung::with(['kantor.kota'])
            ->where(function($q) use ($query) {
                $q->where('nama_gedung', 'like', "%{$query}%")
                  ->orWhere('alamat', 'like', "%{$query}%");
            })
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'gedung',
                    'title' => $item->nama_gedung,
                    'subtitle' => $item->kantor->nama_kantor ?? 'N/A',
                    'description' => $item->alamat,
                    'url' => route('gedung.show', $item->id),
                    'search_fields' => [
                        $item->nama_gedung,
                        $item->alamat,
                        $item->kantor->nama_kantor ?? '',
                        $item->kantor->kota->nama_kota ?? ''
                    ]
                ];
            });
    }

    /**
     * Search Ruang
     */
    private function searchRuang($query)
    {
        return Ruang::with(['lantai.gedung.kantor', 'bidang', 'subBidang'])
            ->where(function($q) use ($query) {
                $q->where('nama_ruang', 'like', "%{$query}%");
            })
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'ruang',
                    'title' => $item->nama_ruang,
                    'subtitle' => $item->lantai->gedung->nama_gedung ?? 'N/A',
                    'description' => "Kapasitas: {$item->kapasitas} | Status: {$item->status_ruang}",
                    'url' => route('ruang.show', $item->id),
                    'search_fields' => [
                        $item->nama_ruang,
                        $item->lantai->gedung->nama_gedung ?? '',
                        $item->bidang->nama_bidang ?? '',
                        $item->subBidang->nama_sub_bidang ?? ''
                    ]
                ];
            });
    }

    /**
     * Search Kontrak
     */
    private function searchKontrak($query)
    {
        return Kontrak::with(['kantor'])
            ->where(function($q) use ($query) {
                $q->where('nama_perjanjian', 'like', "%{$query}%")
                  ->orWhere('no_perjanjian_pihak_1', 'like', "%{$query}%")
                  ->orWhere('no_perjanjian_pihak_2', 'like', "%{$query}%")
                  ->orWhere('asset_owner', 'like', "%{$query}%");
            })
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'kontrak',
                    'title' => $item->nama_perjanjian,
                    'subtitle' => $item->no_perjanjian_pihak_1,
                    'description' => "Asset Owner: {$item->asset_owner} | Kantor: {$item->kantor->nama_kantor}",
                    'url' => route('kontrak.show', $item->id),
                    'search_fields' => [
                        $item->nama_perjanjian,
                        $item->no_perjanjian_pihak_1,
                        $item->no_perjanjian_pihak_2,
                        $item->asset_owner,
                        $item->kantor->nama_kantor ?? ''
                    ]
                ];
            });
    }

    /**
     * Search Realisasi
     */
    private function searchRealisasi($query)
    {
        return Realisasi::with(['kontrak'])
            ->where(function($q) use ($query) {
                $q->where('kompensasi', 'like', "%{$query}%")
                  ->orWhere('deskripsi', 'like', "%{$query}%")
                  ->orWhere('lokasi_kantor', 'like', "%{$query}%");
            })
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'realisasi',
                    'title' => $item->kompensasi,
                    'subtitle' => $item->kontrak->nama_perjanjian ?? 'N/A',
                    'description' => "Rp " . number_format($item->rp_kompensasi, 0, ',', '.') . " | {$item->lokasi_kantor}",
                    'url' => route('realisasi.show', $item->id),
                    'search_fields' => [
                        $item->kompensasi,
                        $item->deskripsi,
                        $item->lokasi_kantor,
                        $item->kontrak->nama_perjanjian ?? ''
                    ]
                ];
            });
    }

    /**
     * Search Bidang
     */
    private function searchBidang($query)
    {
        return Bidang::where(function($q) use ($query) {
                $q->where('nama_bidang', 'like', "%{$query}%")
                  ->orWhere('deskripsi', 'like', "%{$query}%");
            })
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'bidang',
                    'title' => $item->nama_bidang,
                    'subtitle' => 'Bidang',
                    'description' => $item->deskripsi,
                    'url' => route('bidang.show', $item->id),
                    'search_fields' => [
                        $item->nama_bidang,
                        $item->deskripsi
                    ]
                ];
            });
    }

    /**
     * Get search suggestions
     */
    public function getSuggestions(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'suggestions' => []
                ]);
            }

            $suggestions = collect();

            // Get suggestions from different models
            $kantorSuggestions = Kantor::where('nama_kantor', 'like', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'text' => $item->nama_kantor,
                        'type' => 'kantor',
                        'url' => route('kantor.show', $item->id)
                    ];
                });

            $gedungSuggestions = Gedung::where('nama_gedung', 'like', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'text' => $item->nama_gedung,
                        'type' => 'gedung',
                        'url' => route('gedung.show', $item->id)
                    ];
                });

            $suggestions = $suggestions->merge($kantorSuggestions)->merge($gedungSuggestions);

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions->take(10)->values()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}


