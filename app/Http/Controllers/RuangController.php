<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruang;
use App\Models\Lantai;
use App\Models\Bidang;
use App\Models\SubBidang;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogService;

class RuangController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruang::with(['lantai.gedung.kantor', 'bidang', 'subBidang']);

        // Apply filters
        if ($request->filled('lantai')) {
            $query->where('lantai_id', $request->lantai);
        }

        if ($request->filled('status_ruang')) {
            $query->where('status_ruang', $request->status_ruang);
        }

        if ($request->filled('bidang')) {
            $query->where('bidang_id', $request->bidang);
        }

        $ruang = $query->orderBy('created_at', 'desc')->get();
        
        // Get filter options
        $lantai = Lantai::with('gedung.kantor')->get();
        $bidang = Bidang::all();
            
        return view('ruang.index', compact('ruang', 'lantai', 'bidang'));
    }

    public function create()
    {
        $lantai = Lantai::with('gedung.kantor')->get();
        $bidang = Bidang::orderBy('nama_bidang')->get();
        $subBidang = SubBidang::with('bidang')->orderBy('nama_sub_bidang')->get();
        
        return view('ruang.create', compact('lantai', 'bidang', 'subBidang'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Ruang Store Request:', $request->all());
            
            $request->validate([
                'nama_ruang' => 'required|string|max:255',
                'lantai_id' => 'required|exists:lantai,id',
                'bidang_id' => 'required|exists:bidang,id',
                'sub_bidang_id' => 'nullable|exists:sub_bidang,id',
                'kapasitas' => 'required|integer|min:1',
                'status_ruang' => 'required|in:tersedia,terisi,perbaikan',
            ]);

            $ruang = Ruang::create($request->all());
            Log::info('Ruang created:', $ruang->toArray());

            // Log audit
            AuditLogService::logCreate($ruang, $request, "Membuat ruang baru: {$ruang->nama_ruang}");

            return redirect()->route('ruang.index')
                ->with('success', 'Ruang berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Ruang Store Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Request $request, string $id)
    {
        $ruang = Ruang::with(['lantai.gedung.kantor', 'bidang', 'subBidang'])
            ->findOrFail($id);
            
        // Log audit for view
        AuditLogService::logView($ruang, $request, "Melihat detail ruang: {$ruang->nama_ruang}");
            
        return view('ruang.show', compact('ruang'));
    }

    public function edit(string $id)
    {
        $ruang = Ruang::findOrFail($id);
        $lantai = Lantai::with('gedung.kantor')->get();
        $bidang = Bidang::orderBy('nama_bidang')->get();
        $subBidang = SubBidang::with('bidang')->orderBy('nama_sub_bidang')->get();
        
        return view('ruang.edit', compact('ruang', 'lantai', 'bidang', 'subBidang'));
    }

    public function update(Request $request, string $id)
    {
        try {
            Log::info('Ruang Update Request:', $request->all());
            
            $ruang = Ruang::findOrFail($id);
            
            // Store old values for audit
            $oldValues = $ruang->toArray();
            
            $request->validate([
                'nama_ruang' => 'required|string|max:255',
                'lantai_id' => 'required|exists:lantai,id',
                'bidang_id' => 'required|exists:bidang,id',
                'sub_bidang_id' => 'nullable|exists:sub_bidang,id',
                'kapasitas' => 'required|integer|min:1',
                'status_ruang' => 'required|in:tersedia,terisi,perbaikan',
            ]);

            $ruang->update($request->all());
            Log::info('Ruang updated:', $ruang->toArray());

            // Log audit
            AuditLogService::logUpdate($ruang, $oldValues, $request, "Mengubah ruang: {$ruang->nama_ruang}");

            return redirect()->route('ruang.index')
                ->with('success', 'Ruang berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Ruang Update Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $ruang = Ruang::findOrFail($id);
            
            // Log audit before deletion
            AuditLogService::logDelete($ruang, $request, "Menghapus ruang: {$ruang->nama_ruang}");
            
            $ruang->delete();

            return redirect()->route('ruang.index')
                ->with('success', 'Ruang berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Ruang Delete Error:', ['error' => $e->getMessage(), 'id' => $id]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus ruang: ' . $e->getMessage()]);
        }
    }
}
