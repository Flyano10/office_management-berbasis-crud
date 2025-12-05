<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Kantor;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Storage;

class GedungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Optimasi: Select specific columns untuk eager loading
        $query = Gedung::with(['kantor:id,nama_kantor', 'kantor.kota:id,nama_kota', 'kantor.kota.provinsi:id,nama_provinsi']);

        // Scoping berdasarkan role admin (kantor)
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $query->where('kantor_id', $actor->kantor_id);
        }

        // Terapkan filter
        if ($request->filled('status_gedung')) {
            $query->where('status_gedung', $request->status_gedung);
        }

        if ($request->filled('kantor')) {
            $query->where('kantor_id', $request->kantor);
        }


        // Optimasi: Gunakan pagination
        $gedung = $query->orderBy('created_at', 'desc')->paginate(50);
        
        // Optimasi: Cache filter options (10 menit)
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $kantor = \Illuminate\Support\Facades\Cache::remember("admin.gedung.kantor.{$actor->kantor_id}", 600, function () use ($actor) {
                return Kantor::select('id', 'nama_kantor')->where('status_kantor', 'Aktif')->where('id', $actor->kantor_id)->get();
            });
        } else {
            $kantor = \Illuminate\Support\Facades\Cache::remember('admin.gedung.kantor.all', 600, function () {
                return Kantor::select('id', 'nama_kantor')->where('status_kantor', 'Aktif')->get();
            });
        }
            
        return view('gedung.index', compact('gedung', 'kantor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $kantor = Kantor::where('status_kantor', 'aktif')->where('id', $actor->kantor_id)->get();
        } else {
            $kantor = Kantor::where('status_kantor', 'aktif')->get();
        }
        
        return view('gedung.create', compact('kantor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kantor_id' => 'required|exists:kantor,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status_gedung' => 'required|in:aktif,non_aktif',
            'status_kepemilikan' => 'required|in:milik,sewa',
            'layout_gedung' => 'nullable|file|mimes:pdf,jpg,jpeg,png,svg|max:20480'
        ]);

        $data = $request->except('layout_gedung');
        // Enforcement: non-super_admin harus pada kantor sendiri
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true)) {
            $data['kantor_id'] = $actor->kantor_id;
        }

        if ($request->hasFile('layout_gedung')) {
            $data['layout_path'] = $request->file('layout_gedung')->store('gedung_layouts', 'public');
        }

        $gedung = Gedung::create($data);

        // Catat log audit
        AuditLogService::logCreate($gedung, $request, "Membuat gedung baru: {$gedung->nama_gedung}");

        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $gedung = Gedung::with(['kantor.kota.provinsi', 'lantai.ruang'])
            ->findOrFail($id);
        // Scoping akses lihat
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true) && $gedung->kantor_id !== $actor->kantor_id) {
            return redirect()->route('gedung.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat gedung ini!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak memiliki akses untuk melihat gedung ini!'
                ]);
        }
            
        // Catat log audit for view
        AuditLogService::logView($gedung, $request, "Melihat detail gedung: {$gedung->nama_gedung}");
            
        return view('gedung.show', compact('gedung'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gedung = Gedung::findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'staf'], true) && $gedung->kantor_id !== $actor->kantor_id) {
            return redirect()->route('gedung.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit gedung ini!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak memiliki akses untuk mengedit gedung ini!'
                ]);
        }
        if ($actor && in_array($actor->role, ['admin_regional', 'staf'], true)) {
            $kantor = Kantor::where('status_kantor', 'aktif')->where('id', $actor->kantor_id)->get();
        } else {
            $kantor = Kantor::where('status_kantor', 'aktif')->get();
        }
        
        return view('gedung.edit', compact('gedung', 'kantor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gedung = Gedung::findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true) && $gedung->kantor_id !== $actor->kantor_id) {
            return redirect()->route('gedung.index')
                ->with('error', 'Anda tidak memiliki akses untuk memperbarui gedung ini!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak memiliki akses untuk memperbarui gedung ini!'
                ]);
        }
        
        $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kantor_id' => 'required|exists:kantor,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status_gedung' => 'required|in:aktif,non_aktif',
            'status_kepemilikan' => 'required|in:milik,sewa',
            'layout_gedung' => 'nullable|file|mimes:pdf,jpg,jpeg,png,svg|max:20480'
        ]);

        $data = $request->except('layout_gedung');
        if ($actor && in_array($actor->role, ['admin_regional', 'staf'], true)) {
            $data['kantor_id'] = $actor->kantor_id;
        }

        if ($request->hasFile('layout_gedung')) {
            if ($gedung->layout_path && Storage::disk('public')->exists($gedung->layout_path)) {
                Storage::disk('public')->delete($gedung->layout_path);
            }
            $data['layout_path'] = $request->file('layout_gedung')->store('gedung_layouts', 'public');
        }

        $gedung->update($data);

        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gedung = Gedung::findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor && in_array($actor->role, ['admin_regional', 'manager_bidang', 'staf'], true) && $gedung->kantor_id !== $actor->kantor_id) {
            return redirect()->route('gedung.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus gedung ini!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda tidak memiliki akses untuk menghapus gedung ini!'
                ]);
        }
        if ($gedung->layout_path && Storage::disk('public')->exists($gedung->layout_path)) {
            Storage::disk('public')->delete($gedung->layout_path);
        }

        $gedung->delete();

        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil dihapus!');
    }
}
