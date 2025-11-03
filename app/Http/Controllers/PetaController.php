<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kantor;
use App\Models\Gedung;
use App\Models\Kontrak;

class PetaController extends Controller
{
    /**
     * Display a listing of locations on map
     */
    public function index()
    {
        // Ambil data kantor dengan koordinat
        $kantorData = Kantor::with(['kota', 'jenisKantor'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Ambil data gedung dengan koordinat (opsional - bisa di-comment untuk sembunyikan gedung)
        $gedungData = Gedung::with(['kantor.kota'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Siapkan lokasi untuk tabel
        $locations = collect();
        
        // Tambah kantor ke lokasi
        foreach ($kantorData as $kantor) {
            $locations->push([
                'nama' => $kantor->nama_kantor,
                'kode' => $kantor->kode_kantor,
                'jenis' => 'Kantor',
                'alamat' => $kantor->alamat,
                'kota' => $kantor->kota->nama_kota ?? 'N/A',
                'status' => $kantor->status_kantor,
                'lat' => $kantor->latitude,
                'lng' => $kantor->longitude,
            ]);
        }

        // Tambah gedung ke lokasi
        foreach ($gedungData as $gedung) {
            $locations->push([
                'nama' => $gedung->nama_gedung,
                'kode' => null,
                'jenis' => 'Gedung',
                'alamat' => $gedung->alamat,
                'kota' => $gedung->kantor->kota->nama_kota ?? 'N/A',
                'status' => $gedung->status_gedung,
                'lat' => $gedung->latitude,
                'lng' => $gedung->longitude,
            ]);
        }

        return view('peta.index', compact(
            'kantorData',
            'gedungData', 
            'locations'
        ));
    }

    /**
     * Get locations data as JSON for AJAX requests
     */
    public function getLocations(Request $request)
    {
        $type = $request->get('type', 'all'); // kantor, gedung, all
        
        $data = [];
        
        if ($type === 'kantor' || $type === 'all') {
            $kantor = Kantor::with(['kota', 'jenisKantor'])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
                
            foreach ($kantor as $k) {
                $data[] = [
                    'id' => $k->id,
                    'nama' => $k->nama_kantor,
                    'kode' => $k->kode_kantor,
                    'alamat' => $k->alamat,
                    'kota' => $k->kota->nama_kota ?? 'N/A',
                    'jenis' => $k->jenisKantor->nama_jenis ?? 'N/A',
                    'status' => $k->status_kantor,
                    'latitude' => $k->latitude,
                    'longitude' => $k->longitude,
                    'type' => 'kantor'
                ];
            }
        }
        
        if ($type === 'gedung' || $type === 'all') {
            $gedung = Gedung::with(['kantor.kota'])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
                
            foreach ($gedung as $g) {
                $data[] = [
                    'id' => $g->id,
                    'nama' => $g->nama_gedung,
                    'kode' => null,
                    'alamat' => $g->alamat,
                    'kota' => $g->kantor->kota->nama_kota ?? 'N/A',
                    'jenis' => 'Gedung',
                    'status' => $g->status_gedung,
                    'latitude' => $g->latitude,
                    'longitude' => $g->longitude,
                    'type' => 'gedung'
                ];
            }
        }
        
        return response()->json($data);
    }

    /**
     * Get expiring contracts per kantor for public map popup (JSON)
     * Params:
     * - kantor_id (required)
     * - window: 6|3|1|all (default: all)
     */
    public function getExpiringContracts(Request $request)
    {
        $kantorId = (int) $request->get('kantor_id');
        if (!$kantorId) {
            return response()->json(['success' => false, 'message' => 'kantor_id required'], 422);
        }

        $window = $request->get('window', 'all');

        $selectColumns = [
            'id',
            'nama_perjanjian',
            'tanggal_mulai',
            'tanggal_selesai',
            'status_perjanjian',
            'status',
            'kantor_id'
        ];

        $result = [
            'success' => true,
            'window' => $window,
            'data' => [],
            'counts' => [
                'm6' => 0,
                'm3' => 0,
                'm1' => 0
            ]
        ];

        // If a specific window is requested
        if (in_array((string) $window, ['6', '3', '1'], true)) {
            $months = (int) $window;
            $data = Kontrak::select($selectColumns)
                ->expiringWithinMonths($months, $kantorId)
                ->orderBy('tanggal_selesai', 'asc')
                ->get()
                ->map(function ($k) {
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

            $result['data'] = $data;
            return response()->json($result);
        }

        // window=all → return grouped counts + top items
        $groups = [6 => 'm6', 3 => 'm3', 1 => 'm1'];
        foreach ($groups as $months => $key) {
            $items = Kontrak::select($selectColumns)
                ->expiringWithinMonths($months, $kantorId)
                ->orderBy('tanggal_selesai', 'asc')
                ->limit(10)
                ->get();

            $result['counts'][$key] = $items->count();
            $result['data'][$key] = $items->map(function ($k) {
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
}
