<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Realisasi;
use App\Models\Kontrak;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogService;

class RealisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Realisasi::with(['kontrak.kantor']);

        // Scoping berdasarkan role (kantor) melalui relasi kontrak
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $query->whereHas('kontrak', function($q) use ($actor) {
                $q->where('kantor_id', $actor->kantor_id);
            });
        }

        // Terapkan filter
        if ($request->filled('kontrak')) {
            $query->where('kontrak_id', $request->kontrak);
        }

        if ($request->filled('tanggal_realisasi_dari')) {
            $query->whereDate('tanggal_realisasi', '>=', $request->tanggal_realisasi_dari);
        }

        if ($request->filled('tanggal_realisasi_sampai')) {
            $query->whereDate('tanggal_realisasi', '<=', $request->tanggal_realisasi_sampai);
        }

        $realisasi = $query->orderBy('created_at', 'desc')->get();
        
        // Ambil opsi filter (batasi untuk non-super_admin)
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $kontrak = Kontrak::where('status_perjanjian', '!=', 'selesai')
                ->where('kantor_id', $actor->kantor_id)
                ->get();
        } else {
            $kontrak = Kontrak::where('status_perjanjian', '!=', 'selesai')->get();
        }
            
        return view('realisasi.index', compact('realisasi', 'kontrak'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kontrak = Kontrak::where('status_perjanjian', '!=', 'selesai')->get();
        
        return view('realisasi.create', compact('kontrak'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Realisasi Store Request:', $request->all());
            
            $request->validate([
                'kontrak_id' => 'required|exists:kontrak,id',
                'tanggal_realisasi' => 'required|date',
                'kompensasi' => 'required|in:Pemeliharaan,Pembangunan',
                'deskripsi' => 'required|string',
                'rp_kompensasi' => 'required|numeric|min:0',
                'lokasi_kantor' => 'nullable|in:UIW,UID,UIP,UIT',
                'alamat' => 'nullable|string',
                'upload_berita_acara' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
            ]);

            // Ambil data kontrak untuk auto-fill
            $kontrak = Kontrak::findOrFail($request->kontrak_id);

            $data = $request->all();
            
            // Auto-fill data dari kontrak
            $data['no_perjanjian_pihak_1'] = $kontrak->no_perjanjian_pihak_1;
            $data['no_perjanjian_pihak_2'] = $kontrak->no_perjanjian_pihak_2;
            $data['tanggal_mulai'] = $kontrak->tanggal_mulai;
            $data['tanggal_selesai'] = $kontrak->tanggal_selesai;
            
            // Handle upload file
            if ($request->hasFile('upload_berita_acara')) {
                $file = $request->file('upload_berita_acara');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/berita_acara'), $filename);
                $data['upload_berita_acara'] = $filename;
            }

            $realisasi = Realisasi::create($data);
            Log::info('Realisasi created:', $realisasi->toArray());

            // Catat log audit
            AuditLogService::logCreate($realisasi, $request, "Membuat realisasi baru: {$realisasi->kompensasi} - Rp " . number_format($realisasi->rp_kompensasi, 0, ',', '.'));

            return redirect()->route('realisasi.index')
                ->with('success', 'Realisasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Realisasi Store Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $realisasi = Realisasi::with(['kontrak.kantor'])
            ->findOrFail($id);
        
        // Scoping akses lihat berdasarkan kantor kontrak
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            if ($realisasi->kontrak->kantor_id !== $actor->kantor_id) {
                return redirect()->route('realisasi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat realisasi ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk melihat realisasi ini!'
                    ]);
            }
        }
        
        // Catat log audit untuk view
        AuditLogService::logView($realisasi, $request, "Melihat detail realisasi: {$realisasi->kompensasi} - Rp " . number_format($realisasi->rp_kompensasi, 0, ',', '.'));
            
        return view('realisasi.show', compact('realisasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $realisasi = Realisasi::findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            if ($realisasi->kontrak->kantor_id !== $actor->kantor_id) {
                return redirect()->route('realisasi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit realisasi ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk mengedit realisasi ini!'
                    ]);
            }
            $kontrak = Kontrak::where('status_perjanjian', '!=', 'selesai')
                ->where('kantor_id', $actor->kantor_id)
                ->get();
        } else {
            $kontrak = Kontrak::where('status_perjanjian', '!=', 'selesai')->get();
        }
        
        return view('realisasi.edit', compact('realisasi', 'kontrak'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $realisasi = Realisasi::findOrFail($id);
        
        // Simpan nilai lama untuk audit
        $oldValues = $realisasi->toArray();
        
        $request->validate([
            'kontrak_id' => 'required|exists:kontrak,id',
            'tanggal_realisasi' => 'required|date',
            'kompensasi' => 'required|in:Pemeliharaan,Pembangunan',
            'deskripsi' => 'required|string',
            'rp_kompensasi' => 'required|numeric|min:0',
            'lokasi_kantor' => 'nullable|in:UIW,UID,UIP,UIT',
            'alamat' => 'nullable|string',
            'upload_berita_acara' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $data = $request->all();
        
        // Handle file upload
        if ($request->hasFile('upload_berita_acara')) {
            $file = $request->file('upload_berita_acara');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/berita_acara'), $filename);
            $data['upload_berita_acara'] = $filename;
        }

        $realisasi->update($data);

        // Log audit
        AuditLogService::logUpdate($realisasi, $oldValues, $request, "Mengubah realisasi: {$realisasi->kompensasi} - Rp " . number_format($realisasi->rp_kompensasi, 0, ',', '.'));

        return redirect()->route('realisasi.index')
            ->with('success', 'Realisasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $realisasi = Realisasi::findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            if ($realisasi->kontrak->kantor_id !== $actor->kantor_id) {
                return redirect()->route('realisasi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus realisasi ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk menghapus realisasi ini!'
                    ]);
            }
        }
        
        // Log audit before deletion
        AuditLogService::logDelete($realisasi, $request, "Menghapus realisasi: {$realisasi->kompensasi} - Rp " . number_format($realisasi->rp_kompensasi, 0, ',', '.'));
        
        $realisasi->delete();

        return redirect()->route('realisasi.index')
            ->with('success', 'Realisasi berhasil dihapus!');
    }
}
