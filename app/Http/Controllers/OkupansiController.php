<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Okupansi;
use App\Models\Ruang;
use App\Models\Bidang;
use App\Models\SubBidang;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogService;

class OkupansiController extends Controller
{
    public function index(Request $request)
    {
        $query = Okupansi::with(['ruang.lantai.gedung.kantor', 'bidang', 'subBidang']);

        // Scoping berdasarkan role: kantor (admin_regional, manager_bidang, staf) dan bidang (manager_bidang, staf)
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $query->whereHas('ruang.lantai.gedung', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            });
        }
        if ($actor && in_array($actor->role, ['manager_bidang','staf'], true)) {
            $query->where('bidang_id', $actor->bidang_id);
        }

        // Terapkan filter
        if ($request->filled('ruang')) {
            $query->where('ruang_id', $request->ruang);
        }

        if ($request->filled('bidang')) {
            $query->where('bidang_id', $request->bidang);
        }

        if ($request->filled('tanggal_okupansi_dari')) {
            $query->whereDate('tanggal_okupansi', '>=', $request->tanggal_okupansi_dari);
        }

        if ($request->filled('tanggal_okupansi_sampai')) {
            $query->whereDate('tanggal_okupansi', '<=', $request->tanggal_okupansi_sampai);
        }

        $okupansi = $query->orderBy('created_at', 'desc')->get();
        
        // Ambil opsi filter (batasi pilihan untuk non-super_admin)
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $ruang = Ruang::whereHas('lantai.gedung', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            })->with(['lantai.gedung.kantor'])->get();
            $bidang = in_array($actor->role, ['manager_bidang','staf'], true) ? Bidang::where('id', $actor->bidang_id)->get() : Bidang::all();
        } else {
            $ruang = Ruang::with(['lantai.gedung.kantor'])->get();
            $bidang = Bidang::all();
        }
            
        return view('okupansi.index', compact('okupansi', 'ruang', 'bidang'));
    }

    public function create()
    {
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $ruang = Ruang::whereHas('lantai.gedung', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            })->with(['lantai.gedung.kantor'])->get();
            $bidang = in_array($actor->role, ['manager_bidang','staf'], true)
                ? Bidang::where('id', $actor->bidang_id)->orderBy('nama_bidang')->get()
                : Bidang::orderBy('nama_bidang')->get();
        } else {
            $ruang = Ruang::with(['lantai.gedung.kantor'])->get();
            $bidang = Bidang::orderBy('nama_bidang')->get();
        }
        $subBidang = SubBidang::with('bidang')->orderBy('nama_sub_bidang')->get();
        
        return view('okupansi.create', compact('ruang', 'bidang', 'subBidang'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Okupansi Store Request:', $request->all());
            
            $request->validate([
                'ruang_id' => 'required|exists:ruang,id',
                'bidang_id' => 'required|exists:bidang,id',
                'sub_bidang_id' => 'nullable|exists:sub_bidang,id',
                'jml_pegawai_organik' => 'required|integer|min:0',
                'jml_pegawai_tad' => 'required|integer|min:0',
                'jml_pegawai_kontrak' => 'required|integer|min:0',
                'tanggal_okupansi' => 'required|date',
                'keterangan' => 'nullable|string'
            ]);

            // Hitung total pegawai dan okupansi
            $totalPegawai = $request->jml_pegawai_organik + $request->jml_pegawai_tad + $request->jml_pegawai_kontrak;
            $ruang = Ruang::findOrFail($request->ruang_id);
            $persentaseOkupansi = $ruang->kapasitas > 0 ? ($totalPegawai / $ruang->kapasitas) * 100 : 0;

            $data = $request->all();
            $data['total_pegawai'] = $totalPegawai;
            $data['persentase_okupansi'] = round($persentaseOkupansi, 2);

            // Enforcement kantor dan bidang untuk non-super_admin
            $actor = auth('admin')->user();
            if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
                // Pastikan ruang berada pada kantor actor
                $ruangCheck = Ruang::with('lantai.gedung')->findOrFail($request->ruang_id);
                if ($ruangCheck->lantai->gedung->kantor_id !== $actor->kantor_id) {
                    return back()->withErrors(['ruang_id' => 'Anda tidak dapat memilih ruang di luar kantor Anda.'])->withInput();
                }
            }
            if ($actor && in_array($actor->role, ['manager_bidang','staf'], true)) {
                $data['bidang_id'] = $actor->bidang_id;
            }

            $okupansi = Okupansi::create($data);
            Log::info('Okupansi created:', $okupansi->toArray());

            // Catat log audit
            AuditLogService::logCreate($okupansi, $request, "Membuat okupansi baru untuk ruang: {$okupansi->ruang->nama_ruang}");

            return redirect()->route('okupansi.index')
                ->with('success', 'Okupansi berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Okupansi Store Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Request $request, string $id)
    {
        $okupansi = Okupansi::with(['ruang.lantai.gedung.kantor', 'bidang', 'subBidang'])
            ->findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            if ($okupansi->ruang->lantai->gedung->kantor_id !== $actor->kantor_id) {
                return redirect()->route('okupansi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat data okupansi ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk melihat data okupansi ini!'
                    ]);
            }
            if (in_array($actor->role, ['manager_bidang','staf'], true) && $okupansi->bidang_id !== $actor->bidang_id) {
                return redirect()->route('okupansi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat okupansi pada bidang lain!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk melihat okupansi pada bidang lain!'
                    ]);
            }
        }
            
        // Catat log audit untuk view
        AuditLogService::logView($okupansi, $request, "Melihat detail okupansi untuk ruang: {$okupansi->ruang->nama_ruang}");
            
        return view('okupansi.show', compact('okupansi'));
    }

    public function edit(string $id)
    {
        $okupansi = Okupansi::findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            if ($okupansi->ruang->lantai->gedung->kantor_id !== $actor->kantor_id) {
                return redirect()->route('okupansi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk mengedit data ini!'
                    ]);
            }
        }
        if ($actor && in_array($actor->role, ['admin_regional', 'staf'], true)) {
            $ruang = Ruang::whereHas('lantai.gedung', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            })->with(['lantai.gedung.kantor'])->get();
            $bidang = $actor->role === 'staf'
                ? Bidang::where('id', $actor->bidang_id)->orderBy('nama_bidang')->get()
                : Bidang::orderBy('nama_bidang')->get();
        } else {
            $ruang = Ruang::with(['lantai.gedung.kantor'])->get();
            $bidang = Bidang::orderBy('nama_bidang')->get();
        }
        $subBidang = SubBidang::with('bidang')->orderBy('nama_sub_bidang')->get();
        
        return view('okupansi.edit', compact('okupansi', 'ruang', 'bidang', 'subBidang'));
    }

    public function update(Request $request, string $id)
    {
        try {
            Log::info('Okupansi Update Request:', $request->all());
            
            $okupansi = Okupansi::findOrFail($id);
            
            // Simpan nilai lama untuk audit
            $oldValues = $okupansi->toArray();
            
            $request->validate([
                'ruang_id' => 'required|exists:ruang,id',
                'bidang_id' => 'required|exists:bidang,id',
                'sub_bidang_id' => 'nullable|exists:sub_bidang,id',
                'jml_pegawai_organik' => 'required|integer|min:0',
                'jml_pegawai_tad' => 'required|integer|min:0',
                'jml_pegawai_kontrak' => 'required|integer|min:0',
                'tanggal_okupansi' => 'required|date',
                'keterangan' => 'nullable|string'
            ]);

            // Hitung total pegawai dan okupansi
            $totalPegawai = $request->jml_pegawai_organik + $request->jml_pegawai_tad + $request->jml_pegawai_kontrak;
            $ruang = Ruang::findOrFail($request->ruang_id);
            $persentaseOkupansi = $ruang->kapasitas > 0 ? ($totalPegawai / $ruang->kapasitas) * 100 : 0;

            $data = $request->all();
            $data['total_pegawai'] = $totalPegawai;
            $data['persentase_okupansi'] = round($persentaseOkupansi, 2);

            // Enforcement kantor dan bidang untuk non-super_admin
            $actor = auth('admin')->user();
            if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
                $ruangCheck = Ruang::with('lantai.gedung')->findOrFail($request->ruang_id);
                if ($ruangCheck->lantai->gedung->kantor_id !== $actor->kantor_id) {
                    return back()->withErrors(['ruang_id' => 'Anda tidak dapat memilih ruang di luar kantor Anda.'])->withInput();
                }
            }
            if ($actor && in_array($actor->role, ['manager_bidang','staf'], true)) {
                $data['bidang_id'] = $actor->bidang_id;
            }

            $okupansi->update($data);
            Log::info('Okupansi updated:', $okupansi->toArray());

            // Catat log audit
            AuditLogService::logUpdate($okupansi, $oldValues, $request, "Mengubah okupansi untuk ruang: {$okupansi->ruang->nama_ruang}");

            return redirect()->route('okupansi.index')
                ->with('success', 'Okupansi berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Okupansi Update Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $okupansi = Okupansi::findOrFail($id);
            $actor = auth('admin')->user();
            if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
                if ($okupansi->ruang->lantai->gedung->kantor_id !== $actor->kantor_id) {
                    return redirect()->route('okupansi.index')
                        ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk menghapus data ini!'
                        ]);
                }
                if (in_array($actor->role, ['manager_bidang','staf'], true) && $okupansi->bidang_id !== $actor->bidang_id) {
                    return redirect()->route('okupansi.index')
                        ->with('error', 'Anda tidak memiliki akses untuk menghapus okupansi pada bidang lain!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk menghapus okupansi pada bidang lain!'
                        ]);
                }
            }
            
            // Catat log audit before deletion
            AuditLogService::logDelete($okupansi, $request, "Menghapus okupansi untuk ruang: {$okupansi->ruang->nama_ruang}");
            
            $okupansi->delete();

            return redirect()->route('okupansi.index')
                ->with('success', 'Okupansi berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Okupansi Delete Error:', ['error' => $e->getMessage(), 'id' => $id]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus okupansi: ' . $e->getMessage()]);
        }
    }

    // Method untuk rekap okupansi per gedung (seperti contoh Menara Jamsostek)
    public function rekapGedung($gedungId)
    {
        $okupansi = Okupansi::with(['ruang.lantai.gedung.kantor', 'bidang', 'subBidang'])
            ->whereHas('ruang.lantai.gedung', function($query) use ($gedungId) {
                $query->where('id', $gedungId);
            })
            ->orderBy('ruang.lantai.nomor_lantai')
            ->get();
            
        return view('okupansi.rekap-gedung', compact('okupansi'));
    }
}
