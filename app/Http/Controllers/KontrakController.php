<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kontrak;
use App\Models\Kantor;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogService;

class KontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kontrak::with(['kantor']);

        // Apply filters
        if ($request->filled('status_perjanjian')) {
            $query->where('status_perjanjian', $request->status_perjanjian);
        }

        if ($request->filled('tanggal_mulai_dari')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai_dari);
        }

        if ($request->filled('tanggal_mulai_sampai')) {
            $query->whereDate('tanggal_mulai', '<=', $request->tanggal_mulai_sampai);
        }

        $kontrak = $query->orderBy('created_at', 'desc')->get();
            
        return view('kontrak.index', compact('kontrak'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kantor = Kantor::where('status_kantor', 'aktif')->get();
        
        return view('kontrak.create', compact('kantor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Debug: Log request data
            Log::info('Kontrak Store Request:', $request->all());
            
            $request->validate([
                'nama_perjanjian' => 'required|string|max:255',
                'no_perjanjian_pihak_1' => 'required|string|max:255',
                'no_perjanjian_pihak_2' => 'required|string|max:255',
                'asset_owner' => 'required|string|max:255',
                'ruang_lingkup' => 'nullable|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'nilai_kontrak' => 'required|numeric|min:0',
                'sbu' => 'nullable|string|max:255',
                'peruntukan_kantor' => 'nullable|string|max:255',
                'alamat' => 'required|string',
                'kantor_id' => 'required|exists:kantor,id',
                'status_perjanjian' => 'required|in:baru,amandemen,selesai',
                'berita_acara' => 'nullable|file|mimes:pdf|max:10240',
                'keterangan' => 'nullable|string'
            ]);

            // Custom validation untuk tanggal
            if ($request->tanggal_selesai < $request->tanggal_mulai) {
                return back()->withErrors(['tanggal_selesai' => 'Tanggal selesai harus setelah tanggal mulai.']);
            }

            // Handle file upload
            $data = $request->all();
            
            if ($request->hasFile('berita_acara')) {
                $file = $request->file('berita_acara');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/berita_acara'), $filename);
                $data['berita_acara'] = $filename;
            }

            $kontrak = Kontrak::create($data);
            Log::info('Kontrak created:', $kontrak->toArray());

            // Log audit
            AuditLogService::logCreate($kontrak, $request, "Membuat kontrak baru: {$kontrak->nama_perjanjian}");

            return redirect()->route('kontrak.index')
                ->with('success', 'Kontrak berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Kontrak Store Error:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $kontrak = Kontrak::with(['kantor.kota.provinsi'])
            ->findOrFail($id);
            
        // Log audit for view
        AuditLogService::logView($kontrak, $request, "Melihat detail kontrak: {$kontrak->nama_perjanjian}");
            
        return view('kontrak.show', compact('kontrak'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kontrak = Kontrak::findOrFail($id);
        $kantor = Kantor::where('status_kantor', 'aktif')->get();
        
        return view('kontrak.edit', compact('kontrak', 'kantor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $kontrak = Kontrak::findOrFail($id);
            
            // Store old values for audit
            $oldValues = $kontrak->toArray();
            
            $request->validate([
                'nama_perjanjian' => 'required|string|max:255',
                'kantor_id' => 'required|exists:kantor,id',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after:tanggal_mulai',
                'nilai_kontrak' => 'required|numeric|min:0',
                'status_perjanjian' => 'required|in:baru,berjalan,selesai',
                'no_perjanjian_pihak_1' => 'nullable|string|max:255',
                'no_perjanjian_pihak_2' => 'nullable|string|max:255',
                'asset_owner' => 'nullable|string|max:255',
                'ruang_lingkup' => 'nullable|string',
                'sbu' => 'nullable|string|max:255',
                'peruntukan_kantor' => 'nullable|string|max:255',
                'alamat' => 'nullable|string',
                'berita_acara' => 'nullable|file|mimes:pdf|max:10240',
                'keterangan' => 'nullable|string'
            ]);

            // Handle file upload
            $data = $request->all();
            
            if ($request->hasFile('berita_acara')) {
                $file = $request->file('berita_acara');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/berita_acara'), $filename);
                $data['berita_acara'] = $filename;
            }

            $kontrak->update($data);

            // Log audit
            AuditLogService::logUpdate($kontrak, $oldValues, $request, "Mengubah kontrak: {$kontrak->nama_perjanjian}");

            return redirect()->route('kontrak.index')
                ->with('success', 'Kontrak berhasil diperbarui!')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Kontrak Diperbarui',
                    'message' => 'Kontrak ' . $kontrak->nama_perjanjian . ' berhasil diperbarui!'
                ]);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali data yang diinput.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating kontrak: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $kontrak = Kontrak::findOrFail($id);
        
        // Log audit before deletion
        AuditLogService::logDelete($kontrak, $request, "Menghapus kontrak: {$kontrak->nama_perjanjian}");
        
        $kontrak->delete();

        return redirect()->route('kontrak.index')
            ->with('success', 'Kontrak berhasil dihapus!');
    }

}
