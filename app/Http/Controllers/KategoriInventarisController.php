<?php

namespace App\Http\Controllers;

use App\Models\KategoriInventaris;
use Illuminate\Http\Request;

class KategoriInventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = KategoriInventaris::orderBy('nama_kategori')->paginate(20);
        return view('admin.kategori-inventaris.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori-inventaris.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_inventaris,nama_kategori',
            'deskripsi' => 'nullable|string'
        ]);

        KategoriInventaris::create($request->all());

        return redirect()->route('kategori-inventaris.index')
            ->with('success', 'Kategori inventaris berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kategori = KategoriInventaris::findOrFail($id);
        return view('admin.kategori-inventaris.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = KategoriInventaris::findOrFail($id);
        return view('admin.kategori-inventaris.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kategori = KategoriInventaris::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_inventaris,nama_kategori,' . $id,
            'deskripsi' => 'nullable|string'
        ]);

        $kategori->update($request->all());

        return redirect()->route('kategori-inventaris.index')
            ->with('success', 'Kategori inventaris berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = KategoriInventaris::findOrFail($id);

        // Cek apakah kategori digunakan di inventaris
        if ($kategori->inventaris()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan dalam inventaris!');
        }

        $kategori->delete();

        return redirect()->route('kategori-inventaris.index')
            ->with('success', 'Kategori inventaris berhasil dihapus!');
    }
}
