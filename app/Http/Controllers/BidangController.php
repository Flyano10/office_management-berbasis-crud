<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Models\SubBidang;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogService;

class BidangController extends Controller
{
    public function index(Request $request)
    {
        $query = Bidang::with(['subBidang']);

        // Apply filters
        if ($request->filled('nama_bidang')) {
            $query->where('nama_bidang', 'like', '%' . $request->nama_bidang . '%');
        }

        $bidang = $query->orderBy('created_at', 'desc')->get();
        return view('bidang.index', compact('bidang'));
    }

    public function create()
    {
        return view('bidang.create');
    }

    public function store(Request $request)
    {
        try {
            Log::info('Bidang Store Request:', $request->all());
            
            $request->validate([
                'nama_bidang' => 'required|string|max:255',
                'deskripsi' => 'nullable|string'
            ]);

            $bidang = Bidang::create($request->all());
            Log::info('Bidang created:', $bidang->toArray());

            // Log audit
            AuditLogService::logCreate($bidang, $request, "Membuat bidang baru: {$bidang->nama_bidang}");

            return redirect()->route('bidang.index')
                ->with('success', 'Bidang berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Bidang Store Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Request $request, string $id)
    {
        $bidang = Bidang::with(['subBidang'])
            ->findOrFail($id);
            
        // Log audit for view
        AuditLogService::logView($bidang, $request, "Melihat detail bidang: {$bidang->nama_bidang}");
            
        return view('bidang.show', compact('bidang'));
    }

    public function edit(string $id)
    {
        $bidang = Bidang::findOrFail($id);
        
        return view('bidang.edit', compact('bidang'));
    }

    public function update(Request $request, string $id)
    {
        try {
            Log::info('Bidang Update Request:', $request->all());
            
            $bidang = Bidang::findOrFail($id);
            
            // Store old values for audit
            $oldValues = $bidang->toArray();
            
            $request->validate([
                'nama_bidang' => 'required|string|max:255',
                'deskripsi' => 'nullable|string'
            ]);

            $bidang->update($request->all());
            Log::info('Bidang updated:', $bidang->toArray());

            // Log audit
            AuditLogService::logUpdate($bidang, $oldValues, $request, "Mengubah bidang: {$bidang->nama_bidang}");

            return redirect()->route('bidang.index')
                ->with('success', 'Bidang berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Bidang Update Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $bidang = Bidang::findOrFail($id);
            
            // Cek apakah ada sub bidang yang terkait
            $subBidangCount = SubBidang::where('bidang_id', $id)->count();
            if ($subBidangCount > 0) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus bidang yang memiliki sub bidang. Hapus sub bidang terlebih dahulu.']);
            }

            // Log audit before deletion
            AuditLogService::logDelete($bidang, $request, "Menghapus bidang: {$bidang->nama_bidang}");
            
            $bidang->delete();

            return redirect()->route('bidang.index')
                ->with('success', 'Bidang berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Bidang Delete Error:', ['error' => $e->getMessage(), 'id' => $id]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus bidang: ' . $e->getMessage()]);
        }
    }
}
