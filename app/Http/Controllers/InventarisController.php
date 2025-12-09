<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\KategoriInventaris;
use App\Models\Kantor;
use App\Models\Gedung;
use App\Models\Lantai;
use App\Models\Ruang;
use App\Models\Bidang;
use App\Models\SubBidang;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Optimasi: Select specific columns untuk eager loading
        $query = Inventaris::with([
            'kategori:id,nama_kategori',
            'kantor:id,nama_kantor',
            'gedung:id,nama_gedung',
            'lantai:id,nama_lantai,nomor_lantai',
            'ruang:id,nama_ruang',
            'bidang:id,nama_bidang',
            'subBidang:id,nama_sub_bidang'
        ]);

        // Scoping akses berdasarkan role
        $actor = auth('admin')->user();
        if ($actor->role === 'admin_regional') {
            // Admin regional: semua data di kantornya
            $query->where('lokasi_kantor_id', $actor->kantor_id);
        } elseif ($actor->role === 'manager_bidang') {
            // Manager Bidang: data di kantor & bidangnya
            $query->where('lokasi_kantor_id', $actor->kantor_id)
                  ->where('bidang_id', $actor->bidang_id);
        } elseif ($actor->role === 'staf') {
            // Staf: data di kantornya dan di bidangnya
            $query->where('lokasi_kantor_id', $actor->kantor_id)
                  ->where('bidang_id', $actor->bidang_id);
        }

        // Pencarian inventaris
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_inventaris', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter berdasarkan bidang
        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

        $inventaris = $query->orderBy('created_at', 'desc')->paginate(20);

        // Optimasi: Cache filter options (10 menit)
        $kategori = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.kategori', 600, function () {
            return KategoriInventaris::select('id', 'nama_kategori')->orderBy('nama_kategori')->get();
        });
        
        $bidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.bidang', 600, function () {
            return Bidang::select('id', 'nama_bidang')->get();
        });

        return view('admin.inventaris.index', compact('inventaris', 'kategori', 'bidang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $actor = auth('admin')->user();
        // Optimize: Cache kategori
        $kategori = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.kategori', 600, function () {
            return KategoriInventaris::select('id', 'nama_kategori')->orderBy('nama_kategori')->get();
        });
        
        // Optional: filter master data sesuai scope aktor untuk UX yang benar dengan cache
        if ($actor->role === 'admin_regional') {
            $kantor = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.kantor.{$actor->kantor_id}", 600, function () use ($actor) {
                return Kantor::select('id', 'nama_kantor')->where('id', $actor->kantor_id)->get();
            });
            
            $gedung = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.gedung.{$actor->kantor_id}", 600, function () use ($actor) {
                return Gedung::select('id', 'nama_gedung', 'kantor_id')->where('kantor_id', $actor->kantor_id)->get();
            });
            
            $lantai = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.lantai.{$actor->kantor_id}", 600, function () use ($actor) {
                return Lantai::select('id', 'nama_lantai', 'nomor_lantai', 'gedung_id')
                    ->whereHas('gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $ruang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.ruang.{$actor->kantor_id}", 600, function () use ($actor) {
                return Ruang::select('id', 'nama_ruang', 'lantai_id')
                    ->whereHas('lantai.gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $bidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.bidang', 600, function () {
                return Bidang::select('id', 'nama_bidang')->get();
            });
            
            $subBidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.subBidang', 600, function () {
                return SubBidang::select('id', 'nama_sub_bidang', 'bidang_id')->get();
            });
        } elseif ($actor->role === 'manager_bidang') {
            $kantor = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.kantor.{$actor->kantor_id}", 600, function () use ($actor) {
                return Kantor::select('id', 'nama_kantor')->where('id', $actor->kantor_id)->get();
            });
            
            $gedung = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.gedung.{$actor->kantor_id}", 600, function () use ($actor) {
                return Gedung::select('id', 'nama_gedung', 'kantor_id')->where('kantor_id', $actor->kantor_id)->get();
            });
            
            $lantai = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.lantai.{$actor->kantor_id}", 600, function () use ($actor) {
                return Lantai::select('id', 'nama_lantai', 'nomor_lantai', 'gedung_id')
                    ->whereHas('gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $ruang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.ruang.{$actor->kantor_id}", 600, function () use ($actor) {
                return Ruang::select('id', 'nama_ruang', 'lantai_id')
                    ->whereHas('lantai.gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $bidang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.bidang.{$actor->bidang_id}", 600, function () use ($actor) {
                return Bidang::select('id', 'nama_bidang')->where('id', $actor->bidang_id)->get();
            });
            
            $subBidang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.subBidang.{$actor->bidang_id}", 600, function () use ($actor) {
                return SubBidang::select('id', 'nama_sub_bidang', 'bidang_id')->where('bidang_id', $actor->bidang_id)->get();
            });
        } elseif ($actor->role === 'staf') {
            $kantor = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.kantor.{$actor->kantor_id}", 600, function () use ($actor) {
                return Kantor::select('id', 'nama_kantor')->where('id', $actor->kantor_id)->get();
            });
            
            $gedung = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.gedung.{$actor->kantor_id}", 600, function () use ($actor) {
                return Gedung::select('id', 'nama_gedung', 'kantor_id')->where('kantor_id', $actor->kantor_id)->get();
            });
            
            $lantai = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.lantai.{$actor->kantor_id}", 600, function () use ($actor) {
                return Lantai::select('id', 'nama_lantai', 'nomor_lantai', 'gedung_id')
                    ->whereHas('gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $ruang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.ruang.{$actor->kantor_id}", 600, function () use ($actor) {
                return Ruang::select('id', 'nama_ruang', 'lantai_id')
                    ->whereHas('lantai.gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $bidang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.bidang.{$actor->bidang_id}", 600, function () use ($actor) {
                return Bidang::select('id', 'nama_bidang')->where('id', $actor->bidang_id)->get();
            });
            
            $subBidang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.subBidang.{$actor->bidang_id}", 600, function () use ($actor) {
                return SubBidang::select('id', 'nama_sub_bidang', 'bidang_id')->where('bidang_id', $actor->bidang_id)->get();
            });
        } else {
            $kantor = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.kantor.all', 600, function () {
                return Kantor::select('id', 'nama_kantor')->orderBy('nama_kantor')->get();
            });
            
            $gedung = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.gedung.all', 600, function () {
                return Gedung::select('id', 'nama_gedung', 'kantor_id')->orderBy('nama_gedung')->get();
            });
            
            $lantai = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.lantai.all', 600, function () {
                return Lantai::select('id', 'nama_lantai', 'nomor_lantai', 'gedung_id')->orderBy('nomor_lantai')->get();
            });
            
            $ruang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.ruang.all', 600, function () {
                return Ruang::select('id', 'nama_ruang', 'lantai_id')->orderBy('nama_ruang')->get();
            });
            
            $bidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.bidang.all', 600, function () {
                return Bidang::select('id', 'nama_bidang')->orderBy('nama_bidang')->get();
            });
            
            $subBidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.subBidang.all', 600, function () {
                return SubBidang::select('id', 'nama_sub_bidang', 'bidang_id')->orderBy('nama_sub_bidang')->get();
            });
        }

        return view('admin.inventaris.create', compact('kategori', 'kantor', 'gedung', 'lantai', 'ruang', 'bidang', 'subBidang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_inventaris' => 'required|string|unique:inventaris,kode_inventaris',
            'kategori_id' => 'required|exists:kategori_inventaris,id',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baru,Baik,Rusak Ringan,Rusak Berat',
            'merk' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'tahun' => 'nullable|integer|min:1900|max:2030',
            'tanggal_pembelian' => 'nullable|date',
            'lokasi_kantor_id' => 'required|exists:kantor,id',
            'lokasi_gedung_id' => 'required|exists:gedung,id',
            'lokasi_lantai_id' => 'required|exists:lantai,id',
            'lokasi_ruang_id' => 'required|exists:ruang,id',
            'bidang_id' => 'required|exists:bidang,id',
            'sub_bidang_id' => 'nullable|exists:sub_bidang,id',
            'tanggal_input' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string'
        ]);

        $data = $request->all();

        // Enforcement backend untuk non-super_admin
        $actor = auth('admin')->user();
        if ($actor->role === 'admin_regional') {
            // Paksa lokasi kantor sesuai kantor admin
            $data['lokasi_kantor_id'] = $actor->kantor_id;
        } elseif ($actor->role === 'manager_bidang') {
            // Paksa kantor & bidang sesuai manager bidang
            $data['lokasi_kantor_id'] = $actor->kantor_id;
            $data['bidang_id'] = $actor->bidang_id;
        } elseif ($actor->role === 'staf') {
            // Paksa lokasi kantor & bidang sesuai staf
            $data['lokasi_kantor_id'] = $actor->kantor_id;
            $data['bidang_id'] = $actor->bidang_id;
        }

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $filename = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('uploads/inventaris'), $filename);
            $data['gambar'] = 'uploads/inventaris/' . $filename;
        }

        $inventaris = Inventaris::create($data);

        return redirect()->route('inventaris.show', $inventaris->id)
            ->with('success', 'Inventaris berhasil ditambahkan!')
            ->with('show_barcode', true);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Optimize: Select specific columns & eager load only needed relationships
        $inventaris = Inventaris::with([
            'kategori:id,nama_kategori',
            'kantor:id,nama_kantor',
            'gedung:id,nama_gedung',
            'lantai:id,nama_lantai,nomor_lantai',
            'ruang:id,nama_ruang',
            'bidang:id,nama_bidang',
            'subBidang:id,nama_sub_bidang'
        ])->select('inventaris.*')->findOrFail($id);
        $actor = auth('admin')->user();
        if ($actor->role === 'admin_regional' && $inventaris->lokasi_kantor_id != $actor->kantor_id) {
            return redirect()->route('inventaris.index')->with('error', 'Anda tidak memiliki akses untuk melihat data ini.');
        }
        if ($actor->role === 'manager_bidang' && ($inventaris->lokasi_kantor_id != $actor->kantor_id || $inventaris->bidang_id != $actor->bidang_id)) {
            return redirect()->route('inventaris.index')->with('error', 'Anda tidak memiliki akses untuk melihat data ini.');
        }
        if ($actor->role === 'staf' && ($inventaris->lokasi_kantor_id != $actor->kantor_id || $inventaris->bidang_id != $actor->bidang_id)) {
            return redirect()->route('inventaris.index')->with('error', 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        return view('admin.inventaris.show', compact('inventaris'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $actor = auth('admin')->user();
        // Cek akses terhadap record
        if ($actor->role === 'admin_regional' && $inventaris->lokasi_kantor_id != $actor->kantor_id) {
            return redirect()->route('inventaris.index')->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }
        if ($actor->role === 'manager_bidang' && ($inventaris->lokasi_kantor_id != $actor->kantor_id || $inventaris->bidang_id != $actor->bidang_id)) {
            return redirect()->route('inventaris.index')->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }
        if ($actor->role === 'staf' && ($inventaris->lokasi_kantor_id != $actor->kantor_id || $inventaris->bidang_id != $actor->bidang_id)) {
            return redirect()->route('inventaris.index')->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        // Optimize: Cache kategori
        $kategori = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.kategori', 600, function () {
            return KategoriInventaris::select('id', 'nama_kategori')->orderBy('nama_kategori')->get();
        });
        
        // Filter master data sesuai scope aktor dengan cache
        if ($actor->role === 'admin_regional') {
            $kantor = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.kantor.{$actor->kantor_id}", 600, function () use ($actor) {
                return Kantor::select('id', 'nama_kantor')->where('id', $actor->kantor_id)->get();
            });
            
            $gedung = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.gedung.{$actor->kantor_id}", 600, function () use ($actor) {
                return Gedung::select('id', 'nama_gedung', 'kantor_id')->where('kantor_id', $actor->kantor_id)->get();
            });
            
            $lantai = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.lantai.{$actor->kantor_id}", 600, function () use ($actor) {
                return Lantai::select('id', 'nama_lantai', 'nomor_lantai', 'gedung_id')
                    ->whereHas('gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $ruang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.ruang.{$actor->kantor_id}", 600, function () use ($actor) {
                return Ruang::select('id', 'nama_ruang', 'lantai_id')
                    ->whereHas('lantai.gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $bidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.bidang', 600, function () {
                return Bidang::select('id', 'nama_bidang')->get();
            });
            
            $subBidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.subBidang', 600, function () {
                return SubBidang::select('id', 'nama_sub_bidang', 'bidang_id')->get();
            });
        } elseif ($actor->role === 'manager_bidang') {
            $kantor = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.kantor.{$actor->kantor_id}", 600, function () use ($actor) {
                return Kantor::select('id', 'nama_kantor')->where('id', $actor->kantor_id)->get();
            });
            
            $gedung = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.gedung.{$actor->kantor_id}", 600, function () use ($actor) {
                return Gedung::select('id', 'nama_gedung', 'kantor_id')->where('kantor_id', $actor->kantor_id)->get();
            });
            
            $lantai = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.lantai.{$actor->kantor_id}", 600, function () use ($actor) {
                return Lantai::select('id', 'nama_lantai', 'nomor_lantai', 'gedung_id')
                    ->whereHas('gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $ruang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.ruang.{$actor->kantor_id}", 600, function () use ($actor) {
                return Ruang::select('id', 'nama_ruang', 'lantai_id')
                    ->whereHas('lantai.gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $bidang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.bidang.{$actor->bidang_id}", 600, function () use ($actor) {
                return Bidang::select('id', 'nama_bidang')->where('id', $actor->bidang_id)->get();
            });
            
            $subBidang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.subBidang.{$actor->bidang_id}", 600, function () use ($actor) {
                return SubBidang::select('id', 'nama_sub_bidang', 'bidang_id')->where('bidang_id', $actor->bidang_id)->get();
            });
        } elseif ($actor->role === 'staf') {
            $kantor = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.kantor.{$actor->kantor_id}", 600, function () use ($actor) {
                return Kantor::select('id', 'nama_kantor')->where('id', $actor->kantor_id)->get();
            });
            
            $gedung = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.gedung.{$actor->kantor_id}", 600, function () use ($actor) {
                return Gedung::select('id', 'nama_gedung', 'kantor_id')->where('kantor_id', $actor->kantor_id)->get();
            });
            
            $lantai = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.lantai.{$actor->kantor_id}", 600, function () use ($actor) {
                return Lantai::select('id', 'nama_lantai', 'nomor_lantai', 'gedung_id')
                    ->whereHas('gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $ruang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.ruang.{$actor->kantor_id}", 600, function () use ($actor) {
                return Ruang::select('id', 'nama_ruang', 'lantai_id')
                    ->whereHas('lantai.gedung', function($q) use($actor){ 
                        $q->where('kantor_id', $actor->kantor_id); 
                    })->get();
            });
            
            $bidang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.bidang.{$actor->bidang_id}", 600, function () use ($actor) {
                return Bidang::select('id', 'nama_bidang')->where('id', $actor->bidang_id)->get();
            });
            
            $subBidang = \Illuminate\Support\Facades\Cache::remember("admin.inventaris.subBidang.{$actor->bidang_id}", 600, function () use ($actor) {
                return SubBidang::select('id', 'nama_sub_bidang', 'bidang_id')->where('bidang_id', $actor->bidang_id)->get();
            });
        } else {
            $kantor = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.kantor.all', 600, function () {
                return Kantor::select('id', 'nama_kantor')->orderBy('nama_kantor')->get();
            });
            
            $gedung = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.gedung.all', 600, function () {
                return Gedung::select('id', 'nama_gedung', 'kantor_id')->orderBy('nama_gedung')->get();
            });
            
            $lantai = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.lantai.all', 600, function () {
                return Lantai::select('id', 'nama_lantai', 'nomor_lantai', 'gedung_id')->orderBy('nomor_lantai')->get();
            });
            
            $ruang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.ruang.all', 600, function () {
                return Ruang::select('id', 'nama_ruang', 'lantai_id')->orderBy('nama_ruang')->get();
            });
            
            $bidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.bidang.all', 600, function () {
                return Bidang::select('id', 'nama_bidang')->orderBy('nama_bidang')->get();
            });
            
            $subBidang = \Illuminate\Support\Facades\Cache::remember('admin.inventaris.subBidang.all', 600, function () {
                return SubBidang::select('id', 'nama_sub_bidang', 'bidang_id')->orderBy('nama_sub_bidang')->get();
            });
        }

        return view('admin.inventaris.edit', compact('inventaris', 'kategori', 'kantor', 'gedung', 'lantai', 'ruang', 'bidang', 'subBidang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_inventaris' => 'required|string|unique:inventaris,kode_inventaris,' . $id,
            'kategori_id' => 'required|exists:kategori_inventaris,id',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baru,Baik,Rusak Ringan,Rusak Berat',
            'merk' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'tahun' => 'nullable|integer|min:1900|max:2030',
            'tanggal_pembelian' => 'nullable|date',
            'lokasi_kantor_id' => 'required|exists:kantor,id',
            'lokasi_gedung_id' => 'required|exists:gedung,id',
            'lokasi_lantai_id' => 'required|exists:lantai,id',
            'lokasi_ruang_id' => 'required|exists:ruang,id',
            'bidang_id' => 'required|exists:bidang,id',
            'sub_bidang_id' => 'nullable|exists:sub_bidang,id',
            'tanggal_input' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string'
        ]);

        $data = $request->all();

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($inventaris->gambar && file_exists(public_path($inventaris->gambar))) {
                unlink(public_path($inventaris->gambar));
            }

            $gambar = $request->file('gambar');
            $filename = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('uploads/inventaris'), $filename);
            $data['gambar'] = 'uploads/inventaris/' . $filename;
        }

        // Enforcement backend untuk non-super_admin saat update
        $actor = auth('admin')->user();
        if ($actor->role === 'admin_regional') {
            $data['lokasi_kantor_id'] = $actor->kantor_id;
        } elseif ($actor->role === 'manager_bidang') {
            $data['lokasi_kantor_id'] = $actor->kantor_id;
            $data['bidang_id'] = $actor->bidang_id;
        } elseif ($actor->role === 'staf') {
            $data['lokasi_kantor_id'] = $actor->kantor_id;
            $data['bidang_id'] = $actor->bidang_id;
        }

        $inventaris->update($data);

        return redirect()->route('inventaris.index')
            ->with('success', 'Inventaris berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);

        // Hapus gambar
        if ($inventaris->gambar && file_exists(public_path($inventaris->gambar))) {
            unlink(public_path($inventaris->gambar));
        }

        $inventaris->delete();

        return redirect()->route('inventaris.index')
            ->with('success', 'Inventaris berhasil dihapus!');
    }

}
