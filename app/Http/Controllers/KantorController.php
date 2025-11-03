<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Kantor;
use App\Models\JenisKantor;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Services\AuditLogService;

class KantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kantor::with(['kota.provinsi', 'jenisKantor', 'parentKantor']);

        // Scoping berdasarkan role admin
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            // Batasi daftar ke kantor miliknya saja
            $query->where('id', $actor->kantor_id);
        }

        // Terapkan filter-filter yang dipilih
        if ($request->filled('status_kantor')) {
            $query->where('status_kantor', $request->status_kantor);
        }

        if ($request->filled('jenis_kantor')) {
            $query->where('jenis_kantor_id', $request->jenis_kantor);
        }

        if ($request->filled('kota')) {
            $query->where('kota_id', $request->kota);
        }

        $kantor = $query->orderBy('created_at', 'desc')->get();
        
        // Ambil opsi filter - optimasi query database
        $jenisKantor = JenisKantor::select('id', 'nama_jenis')->get();
        $kota = Kota::select('id', 'nama_kota', 'provinsi_id')->with('provinsi:id,nama_provinsi')->get();
            
        return view('kantor.index', compact('kantor', 'jenisKantor', 'kota'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya super_admin yang dapat membuat kantor baru
        if (auth('admin')->user()->role !== 'super_admin') {
            return redirect()->route('kantor.index')
                ->with('error', 'Hanya Super Admin yang dapat membuat kantor!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Hanya Super Admin yang dapat membuat kantor!'
                ]);
        }

        $jenisKantor = JenisKantor::all();
        $kota = Kota::with('provinsi')->get();
        $parentKantor = Kantor::select('id', 'nama_kantor')->get();
        
        return view('kantor.create', compact('jenisKantor', 'kota', 'parentKantor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Hanya super_admin yang dapat membuat kantor baru
            if (auth('admin')->user()->role !== 'super_admin') {
                return redirect()->route('kantor.index')
                    ->with('error', 'Hanya Super Admin yang dapat membuat kantor!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat membuat kantor!'
                    ]);
            }
            $request->validate([
                'kode_kantor' => 'required|unique:kantor,kode_kantor',
                'nama_kantor' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kota_id' => 'required|exists:kota,id',
                'jenis_kantor_id' => 'required|exists:jenis_kantor,id',
                'status_kantor' => 'required|in:aktif,tidak_aktif',
                'status_kepemilikan' => 'required|in:milik,sewa',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'parent_kantor_id' => 'nullable|exists:kantor,id'
            ]);

            $kantor = Kantor::create($request->all());

            // Log audit
            AuditLogService::logCreate($kantor, $request, "Membuat kantor baru: {$kantor->nama_kantor}");

            return redirect()->route('kantor.index')
                ->with('success', 'Kantor berhasil ditambahkan!')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Kantor Baru',
                    'message' => 'Kantor ' . $kantor->nama_kantor . ' berhasil ditambahkan!'
                ]);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali data yang diinput.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating kantor: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $kantor = Kantor::with(['kota.provinsi', 'jenisKantor'])
            ->findOrFail($id);

        // Scoping akses lihat
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true) && $kantor->id !== $actor->kantor_id) {
            return redirect()->route('kantor.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat kantor ini!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak memiliki akses untuk melihat kantor ini!'
                ]);
        }
            
        // Log audit for view
        AuditLogService::logView($kantor, $request, "Melihat detail kantor: {$kantor->nama_kantor}");
            
        return view('kantor.show', compact('kantor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kantor = Kantor::findOrFail($id);
        // Scoping akses edit
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true) && $kantor->id !== $actor->kantor_id) {
            return redirect()->route('kantor.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit kantor ini!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak memiliki akses untuk mengedit kantor ini!'
                ]);
        }

        $jenisKantor = JenisKantor::all();
        $kota = Kota::with('provinsi')->get();
        $parentKantor = Kantor::select('id', 'nama_kantor')->get();
        
        return view('kantor.edit', compact('kantor', 'jenisKantor', 'kota', 'parentKantor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $kantor = Kantor::findOrFail($id);
            // Scoping akses update
            $actor = auth('admin')->user();
            if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true) && $kantor->id !== $actor->kantor_id) {
                return redirect()->route('kantor.index')
                    ->with('error', 'Anda tidak memiliki akses untuk memperbarui kantor ini!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak memiliki akses untuk memperbarui kantor ini!'
                    ]);
            }
            
            // Store old values for audit
            $oldValues = $kantor->toArray();
            
            $request->validate([
                'kode_kantor' => 'required|unique:kantor,kode_kantor,' . $id,
                'nama_kantor' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kota_id' => 'required|exists:kota,id',
                'jenis_kantor_id' => 'required|exists:jenis_kantor,id',
                'status_kantor' => 'required|in:aktif,tidak_aktif',
                'status_kepemilikan' => 'required|in:milik,sewa',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'parent_kantor_id' => 'nullable|exists:kantor,id'
            ]);

            $kantor->update($request->all());

            // Log audit
            AuditLogService::logUpdate($kantor, $oldValues, $request, "Mengubah kantor: {$kantor->nama_kantor}");

            return redirect()->route('kantor.index')
                ->with('success', 'Kantor berhasil diperbarui!')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Kantor Diperbarui',
                    'message' => 'Kantor ' . $kantor->nama_kantor . ' berhasil diperbarui!'
                ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // Hanya super_admin yang dapat menghapus kantor
        if (auth('admin')->user()->role !== 'super_admin') {
            return redirect()->route('kantor.index')
                ->with('error', 'Hanya Super Admin yang dapat menghapus kantor!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Hanya Super Admin yang dapat menghapus kantor!'
                ]);
        }

        $kantor = Kantor::findOrFail($id);
        
        // Log audit before deletion
        AuditLogService::logDelete($kantor, $request, "Menghapus kantor: {$kantor->nama_kantor}");
        
        $kantor->delete();

        return redirect()->route('kantor.index')
            ->with('success', 'Kantor berhasil dihapus!');
    }
}
