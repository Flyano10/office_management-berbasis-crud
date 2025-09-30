<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Kantor;
use App\Services\AuditLogService;

class GedungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gedung::with(['kantor.kota.provinsi']);

        // Apply filters
        if ($request->filled('status_gedung')) {
            $query->where('status_gedung', $request->status_gedung);
        }

        if ($request->filled('kantor')) {
            $query->where('kantor_id', $request->kantor);
        }


        $gedung = $query->orderBy('created_at', 'desc')->get();
        
        // Get filter options
        $kantor = Kantor::where('status_kantor', 'Aktif')->get();
            
        return view('gedung.index', compact('gedung', 'kantor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kantor = Kantor::where('status_kantor', 'aktif')->get();
        
        return view('gedung.create', compact('kantor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kantor_id' => 'required|exists:kantor,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status_gedung' => 'required|in:aktif,non_aktif',
            'status_kepemilikan' => 'required|in:milik,sewa'
        ]);

        $gedung = Gedung::create($request->all());

        // Log audit
        AuditLogService::logCreate($gedung, $request, "Membuat gedung baru: {$gedung->nama_gedung}");

        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $gedung = Gedung::with(['kantor.kota.provinsi', 'lantai.ruang'])
            ->findOrFail($id);
            
        // Log audit for view
        AuditLogService::logView($gedung, $request, "Melihat detail gedung: {$gedung->nama_gedung}");
            
        return view('gedung.show', compact('gedung'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gedung = Gedung::findOrFail($id);
        $kantor = Kantor::where('status_kantor', 'aktif')->get();
        
        return view('gedung.edit', compact('gedung', 'kantor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gedung = Gedung::findOrFail($id);
        
        $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kantor_id' => 'required|exists:kantor,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status_gedung' => 'required|in:aktif,non_aktif',
            'status_kepemilikan' => 'required|in:milik,sewa'
        ]);

        $gedung->update($request->all());

        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gedung = Gedung::findOrFail($id);
        $gedung->delete();

        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil dihapus!');
    }
}
