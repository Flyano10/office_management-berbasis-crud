<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lantai;
use App\Models\Gedung;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogService;

class LantaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lantai::with(['gedung.kantor']);

        // Apply filters
        if ($request->filled('gedung')) {
            $query->where('gedung_id', $request->gedung);
        }

        if ($request->filled('nomor_lantai')) {
            $query->where('nomor_lantai', $request->nomor_lantai);
        }


        $lantai = $query->orderBy('created_at', 'desc')->get();
        
        // Get filter options
        $gedung = Gedung::where('status_gedung', 'Aktif')->get();
            
        return view('lantai.index', compact('lantai', 'gedung'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gedung = Gedung::where('status_gedung', 'aktif')->get();
        
        return view('lantai.create', compact('gedung'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Lantai Store Request:', $request->all());
            
            $request->validate([
                'nama_lantai' => 'required|string|max:255',
                'nomor_lantai' => 'required|integer|min:1',
                'gedung_id' => 'required|exists:gedung,id'
            ]);

            $lantai = Lantai::create($request->all());
            Log::info('Lantai created:', $lantai->toArray());

            // Log audit
            AuditLogService::logCreate($lantai, $request, "Membuat lantai baru: {$lantai->nama_lantai}");

            return redirect()->route('lantai.index')
                ->with('success', 'Lantai berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Lantai Store Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $lantai = Lantai::with(['gedung.kantor'])
            ->findOrFail($id);
            
        // Log audit for view
        AuditLogService::logView($lantai, $request, "Melihat detail lantai: {$lantai->nama_lantai}");
            
        return view('lantai.show', compact('lantai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lantai = Lantai::findOrFail($id);
        $gedung = Gedung::where('status_gedung', 'aktif')->get();
        
        return view('lantai.edit', compact('lantai', 'gedung'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lantai = Lantai::findOrFail($id);
        
        // Store old values for audit
        $oldValues = $lantai->toArray();
        
        $request->validate([
            'nama_lantai' => 'required|string|max:255',
            'nomor_lantai' => 'required|integer|min:1',
            'gedung_id' => 'required|exists:gedung,id'
        ]);

        $lantai->update($request->all());

        // Log audit
        AuditLogService::logUpdate($lantai, $oldValues, $request, "Mengubah lantai: {$lantai->nama_lantai}");

        return redirect()->route('lantai.index')
            ->with('success', 'Lantai berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $lantai = Lantai::findOrFail($id);
        
        // Log audit before deletion
        AuditLogService::logDelete($lantai, $request, "Menghapus lantai: {$lantai->nama_lantai}");
        
        $lantai->delete();

        return redirect()->route('lantai.index')
            ->with('success', 'Lantai berhasil dihapus!');
    }
}
