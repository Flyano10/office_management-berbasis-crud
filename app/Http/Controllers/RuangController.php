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

        // Scoping berdasarkan role: kantor (admin_regional, manager_bidang, staf) dan bidang (manager_bidang, staf)
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $query->whereHas('lantai.gedung', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            });
        }
        if ($actor && in_array($actor->role, ['manager_bidang','staf'], true)) {
            $query->where('bidang_id', $actor->bidang_id);
        }

        // Terapkan filter
        if ($request->filled('lantai')) {
            $query->where('lantai_id', $request->lantai);
        }

        if ($request->filled('status_ruang')) {
            $query->where('status_ruang', $request->status_ruang);
        }

        if ($request->filled('bidang')) {
            $query->where('bidang_id', $request->bidang);
        }

        // Optimasi: Gunakan pagination
        $ruang = $query->orderBy('created_at', 'desc')->paginate(50);
        
        // Ambil opsi filter (batasi pilihan untuk non-super_admin)
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $lantai = Lantai::whereHas('gedung', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            })->with('gedung.kantor')->get();
            $bidang = in_array($actor->role, ['manager_bidang','staf'], true)
                ? Bidang::where('id', $actor->bidang_id)->get()
                : Bidang::all();
        } else {
            $lantai = Lantai::with('gedung.kantor')->get();
            $bidang = Bidang::all();
        }
            
        return view('ruang.index', compact('ruang', 'lantai', 'bidang'));
    }

    public function create()
    {
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $lantai = Lantai::whereHas('gedung', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            })->with('gedung.kantor')->get();
            $bidang = in_array($actor->role, ['manager_bidang','staf'], true)
                ? Bidang::where('id', $actor->bidang_id)->orderBy('nama_bidang')->get()
                : Bidang::orderBy('nama_bidang')->get();
        } else {
            $lantai = Lantai::with('gedung.kantor')->get();
            $bidang = Bidang::orderBy('nama_bidang')->get();
        }
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

            $data = $request->all();
            $actor = auth('admin')->user();
            // Enforcement kantor untuk non-super_admin
            if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
                // Pastikan lantai berada pada kantor yang sesuai
                $lantai = Lantai::with('gedung')->findOrFail($request->lantai_id);
                if ($lantai->gedung->kantor_id !== $actor->kantor_id) {
                    return back()->withErrors(['lantai_id' => 'Anda tidak dapat memilih lantai di luar kantor Anda.'])->withInput();
                }
            }
            // Enforcement bidang untuk manager_bidang dan staf
            if ($actor && in_array($actor->role, ['manager_bidang','staf'], true)) {
                $data['bidang_id'] = $actor->bidang_id;
            }

            $ruang = Ruang::create($data);
            Log::info('Ruang created:', $ruang->toArray());

            // Catat log audit
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
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            if ($ruang->lantai->gedung->kantor_id !== $actor->kantor_id) {
                return redirect()->route('ruang.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat ruang ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk melihat ruang ini!'
                    ]);
            }
            if (in_array($actor->role, ['manager_bidang','staf'], true) && $ruang->bidang_id !== $actor->bidang_id) {
                return redirect()->route('ruang.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat ruang pada bidang lain!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk melihat ruang pada bidang lain!'
                    ]);
            }
        }
            
        // Catat log audit untuk view
        AuditLogService::logView($ruang, $request, "Melihat detail ruang: {$ruang->nama_ruang}");
            
        return view('ruang.show', compact('ruang'));
    }

    public function edit(string $id)
    {
        $ruang = Ruang::findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            if ($ruang->lantai->gedung->kantor_id !== $actor->kantor_id) {
                return redirect()->route('ruang.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit ruang ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk mengedit ruang ini!'
                    ]);
            }
            if (in_array($actor->role, ['manager_bidang','staf'], true) && $ruang->bidang_id !== $actor->bidang_id) {
                return redirect()->route('ruang.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit ruang pada bidang lain!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk mengedit ruang pada bidang lain!'
                    ]);
            }
        }
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $lantai = Lantai::whereHas('gedung', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            })->with('gedung.kantor')->get();
            $bidang = in_array($actor->role, ['manager_bidang','staf'], true)
                ? Bidang::where('id', $actor->bidang_id)->orderBy('nama_bidang')->get()
                : Bidang::orderBy('nama_bidang')->get();
        } else {
            $lantai = Lantai::with('gedung.kantor')->get();
            $bidang = Bidang::orderBy('nama_bidang')->get();
        }
        $subBidang = SubBidang::with('bidang')->orderBy('nama_sub_bidang')->get();
        
        return view('ruang.edit', compact('ruang', 'lantai', 'bidang', 'subBidang'));
    }

    public function update(Request $request, string $id)
    {
        try {
            Log::info('Ruang Update Request:', $request->all());
            
            $ruang = Ruang::findOrFail($id);
            
            // Simpan nilai lama untuk audit
            $oldValues = $ruang->toArray();
            
            $request->validate([
                'nama_ruang' => 'required|string|max:255',
                'lantai_id' => 'required|exists:lantai,id',
                'bidang_id' => 'required|exists:bidang,id',
                'sub_bidang_id' => 'nullable|exists:sub_bidang,id',
                'kapasitas' => 'required|integer|min:1',
                'status_ruang' => 'required|in:tersedia,terisi,perbaikan',
            ]);

            $data = $request->all();
            $actor = auth('admin')->user();
            if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
                // Pastikan lantai berada pada kantor yang sesuai
                $lantai = Lantai::with('gedung')->findOrFail($request->lantai_id);
                if ($lantai->gedung->kantor_id !== $actor->kantor_id) {
                    return back()->withErrors(['lantai_id' => 'Anda tidak dapat memilih lantai di luar kantor Anda.'])->withInput();
                }
            }
            // Enforcement bidang untuk manager_bidang dan staf
            if ($actor && in_array($actor->role, ['manager_bidang','staf'], true)) {
                $data['bidang_id'] = $actor->bidang_id;
            }

            $ruang->update($data);
            Log::info('Ruang updated:', $ruang->toArray());

            // Catat log audit
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
            $actor = auth('admin')->user();
            if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
                if ($ruang->lantai->gedung->kantor_id !== $actor->kantor_id) {
                    return redirect()->route('ruang.index')
                        ->with('error', 'Anda tidak memiliki akses untuk menghapus ruang ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk menghapus ruang ini!'
                        ]);
                }
                if (in_array($actor->role, ['manager_bidang','staf'], true) && $ruang->bidang_id !== $actor->bidang_id) {
                    return redirect()->route('ruang.index')
                        ->with('error', 'Anda tidak memiliki akses untuk menghapus ruang pada bidang lain!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk menghapus ruang pada bidang lain!'
                        ]);
                }
            }
            
            // Catat log audit before deletion
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
