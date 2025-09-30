<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubBidang;
use App\Models\Bidang;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogService;

class SubBidangController extends Controller
{
    public function index(Request $request)
    {
        $query = SubBidang::with(['bidang']);

        // Apply filters
        if ($request->filled('nama_sub_bidang')) {
            $query->where('nama_sub_bidang', 'like', '%' . $request->nama_sub_bidang . '%');
        }

        if ($request->filled('bidang')) {
            $query->where('bidang_id', $request->bidang);
        }

        $subBidang = $query->orderBy('created_at', 'desc')->get();
        
        // Get filter options
        $bidang = Bidang::orderBy('nama_bidang')->get();
            
        return view('sub-bidang.index', compact('subBidang', 'bidang'));
    }

    public function create()
    {
        $bidang = Bidang::orderBy('nama_bidang')->get();
        
        return view('sub-bidang.create', compact('bidang'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('SubBidang Store Request:', $request->all());
            
            $request->validate([
                'nama_sub_bidang' => 'required|string|max:255',
                'bidang_id' => 'required|exists:bidang,id',
                'deskripsi' => 'nullable|string'
            ]);

            $subBidang = SubBidang::create($request->all());
            Log::info('SubBidang created:', $subBidang->toArray());

            // Log audit
            AuditLogService::logCreate($subBidang, $request, "Membuat sub bidang baru: {$subBidang->nama_sub_bidang}");

            return redirect()->route('sub-bidang.index')
                ->with('success', 'Sub Bidang berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('SubBidang Store Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Request $request, string $id)
    {
        $subBidang = SubBidang::with(['bidang'])
            ->findOrFail($id);
            
        // Log audit for view
        AuditLogService::logView($subBidang, $request, "Melihat detail sub bidang: {$subBidang->nama_sub_bidang}");
            
        return view('sub-bidang.show', compact('subBidang'));
    }

    public function edit(string $id)
    {
        $subBidang = SubBidang::findOrFail($id);
        $bidang = Bidang::orderBy('nama_bidang')->get();
        
        return view('sub-bidang.edit', compact('subBidang', 'bidang'));
    }

    public function update(Request $request, string $id)
    {
        try {
            Log::info('SubBidang Update Request:', $request->all());
            
            $subBidang = SubBidang::findOrFail($id);
            
            // Store old values for audit
            $oldValues = $subBidang->toArray();
            
            $request->validate([
                'nama_sub_bidang' => 'required|string|max:255',
                'bidang_id' => 'required|exists:bidang,id',
                'deskripsi' => 'nullable|string'
            ]);

            $subBidang->update($request->all());
            Log::info('SubBidang updated:', $subBidang->toArray());

            // Log audit
            AuditLogService::logUpdate($subBidang, $oldValues, $request, "Mengubah sub bidang: {$subBidang->nama_sub_bidang}");

            return redirect()->route('sub-bidang.index')
                ->with('success', 'Sub Bidang berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('SubBidang Update Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $subBidang = SubBidang::findOrFail($id);
            
            // Log audit before deletion
            AuditLogService::logDelete($subBidang, $request, "Menghapus sub bidang: {$subBidang->nama_sub_bidang}");
            
            $subBidang->delete();

            return redirect()->route('sub-bidang.index')
                ->with('success', 'Sub Bidang berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('SubBidang Delete Error:', ['error' => $e->getMessage(), 'id' => $id]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus sub bidang: ' . $e->getMessage()]);
        }
    }
}
