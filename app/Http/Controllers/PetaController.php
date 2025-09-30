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
        // Get kantor data with coordinates
        $kantorData = Kantor::with(['kota', 'jenisKantor'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Get gedung data with coordinates (optional - bisa di-comment untuk sembunyikan gedung)
        $gedungData = Gedung::with(['kantor.kota'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Prepare locations for table
        $locations = collect();
        
        // Add kantor to locations
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

        // Add gedung to locations
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

        // Get statistics
        $totalKantor = Kantor::count();
        $totalGedung = Gedung::count();
        $totalKontrak = Kontrak::count();
        $totalPegawai = \App\Models\Okupansi::sum('jml_pegawai_kontrak'); // Total pegawai dari okupansi

        return view('peta.index', compact(
            'kantorData',
            'gedungData', 
            'locations',
            'totalKantor',
            'totalGedung',
            'totalKontrak',
            'totalPegawai'
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
}
