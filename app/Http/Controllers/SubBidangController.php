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

        // Akses: staf dilarang; manager_bidang hanya pada bidangnya
        $actor = auth('admin')->user();
        if ($actor && $actor->role === 'staf') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke modul Sub Bidang!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Staf tidak memiliki akses ke modul Sub Bidang.'
                ]);
        }
        if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id) {
            $query->where('bidang_id', $actor->bidang_id);
        }

        // Terapkan filter
        if ($request->filled('nama_sub_bidang')) {
            $query->where('nama_sub_bidang', 'like', '%' . $request->nama_sub_bidang . '%');
        }

        if ($request->filled('bidang')) {
            $query->where('bidang_id', $request->bidang);
        }

        $subBidang = $query->orderBy('created_at', 'desc')->get();
        
        // Ambil opsi filter (batasi untuk manager_bidang)
        if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id) {
            $bidang = Bidang::where('id', $actor->bidang_id)->orderBy('nama_bidang')->get();
        } else {
            $bidang = Bidang::orderBy('nama_bidang')->get();
        }
            
        return view('sub-bidang.index', compact('subBidang', 'bidang'));
    }

    public function create()
    {
        $actor = auth('admin')->user();
        // Akses: staf dilarang; manager_bidang hanya pada bidangnya
        if ($actor && $actor->role === 'staf') {
            return redirect()->route('sub-bidang.index')
                ->with('error', 'Anda tidak memiliki akses untuk membuat sub bidang!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Staf tidak dapat membuat sub bidang.'
                ]);
        }
        if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id) {
            $bidang = Bidang::where('id', $actor->bidang_id)->orderBy('nama_bidang')->get();
        } else {
            $bidang = Bidang::orderBy('nama_bidang')->get();
        }
        
        return view('sub-bidang.create', compact('bidang'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('SubBidang Store Request:', $request->all());
            
            $actor = auth('admin')->user();

            $request->validate([
                'nama_sub_bidang' => 'required|string|max:255',
                'bidang_id' => 'required|exists:bidang,id',
                'deskripsi' => 'nullable|string'
            ]);

            $data = $request->all();
            // Akses: staf dilarang; Enforcement bidang untuk manager_bidang
            if ($actor && $actor->role === 'staf') {
                return redirect()->route('sub-bidang.index')
                    ->with('error', 'Anda tidak memiliki akses untuk membuat sub bidang!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Staf tidak dapat membuat sub bidang.'
                    ]);
            }
            if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id) {
                $data['bidang_id'] = $actor->bidang_id;
            }

            $subBidang = SubBidang::create($data);
            Log::info('SubBidang created:', $subBidang->toArray());

            // Catat log audit
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
        
        $actor = auth('admin')->user();
        // Akses: staf dilarang; manager_bidang hanya pada bidangnya
        if ($actor && $actor->role === 'staf') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke modul Sub Bidang!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Staf tidak memiliki akses ke modul Sub Bidang.'
                ]);
        }
        if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id && $subBidang->bidang_id !== $actor->bidang_id) {
            return redirect()->route('sub-bidang.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat sub bidang ini!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak memiliki akses untuk melihat sub bidang ini!'
                ]);
        }
            
        // Catat log audit untuk view
        AuditLogService::logView($subBidang, $request, "Melihat detail sub bidang: {$subBidang->nama_sub_bidang}");
            
        return view('sub-bidang.show', compact('subBidang'));
    }

    public function edit(string $id)
    {
        $subBidang = SubBidang::findOrFail($id);
        $actor = auth('admin')->user();
        // Akses: staf dilarang; manager_bidang hanya pada bidangnya
        if ($actor && $actor->role === 'staf') {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke modul Sub Bidang!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Staf tidak memiliki akses ke modul Sub Bidang.'
                ]);
        }
        if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id && $subBidang->bidang_id !== $actor->bidang_id) {
            return redirect()->route('sub-bidang.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit sub bidang ini!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak memiliki akses untuk mengedit sub bidang ini!'
                ]);
        }
        if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id) {
            $bidang = Bidang::where('id', $actor->bidang_id)->orderBy('nama_bidang')->get();
        } else {
            $bidang = Bidang::orderBy('nama_bidang')->get();
        }
        
        return view('sub-bidang.edit', compact('subBidang', 'bidang'));
    }

    public function update(Request $request, string $id)
    {
        try {
            Log::info('SubBidang Update Request:', $request->all());
            
            $subBidang = SubBidang::findOrFail($id);
            $actor = auth('admin')->user();
            // Akses: staf dilarang; manager_bidang hanya pada bidangnya
            if ($actor && $actor->role === 'staf') {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke modul Sub Bidang!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Staf tidak memiliki akses ke modul Sub Bidang.'
                    ]);
            }
            if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id && $subBidang->bidang_id !== $actor->bidang_id) {
                return redirect()->route('sub-bidang.index')
                    ->with('error', 'Anda tidak memiliki akses untuk memperbarui sub bidang ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk memperbarui sub bidang ini!'
                    ]);
            }
            
            // Simpan nilai lama untuk audit
            $oldValues = $subBidang->toArray();
            
            $request->validate([
                'nama_sub_bidang' => 'required|string|max:255',
                'bidang_id' => 'required|exists:bidang,id',
                'deskripsi' => 'nullable|string'
            ]);

            $data = $request->all();
            if ($actor && in_array($actor->role, ['admin_regional','staf'], true) && $actor->bidang_id) {
                $data['bidang_id'] = $actor->bidang_id;
            }

            $subBidang->update($data);
            Log::info('SubBidang updated:', $subBidang->toArray());

            // Catat log audit
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
            $actor = auth('admin')->user();
            // Akses: staf dilarang; manager_bidang hanya pada bidangnya
            if ($actor && $actor->role === 'staf') {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke modul Sub Bidang!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Staf tidak memiliki akses ke modul Sub Bidang.'
                    ]);
            }
            if ($actor && $actor->role === 'manager_bidang' && $actor->bidang_id && $subBidang->bidang_id !== $actor->bidang_id) {
                return redirect()->route('sub-bidang.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus sub bidang ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk menghapus sub bidang ini!'
                    ]);
            }
            
            // Catat log audit before deletion
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
