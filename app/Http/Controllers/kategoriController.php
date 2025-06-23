<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class kategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::paginate(5);
        return view('kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('kategori.create',compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:5|string',
        ],[
            'nama.min'=>'Nama Minimal 5 Karakter',
            'nama.required'=>'Nama Tidak Boleh Kosong',
        ]);

        Kategori::create([
            "nama" => $request->nama,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Tugas Berhasil Ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'min:5|string',
        ],[
            'nama.min'=>'Nama minimal 5 Karakter'
        ]);
        $kategori->nama = $request->nama;

        $kategori->save();

        return redirect()->route('kategori.index')->with('success', 'Tugas Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Tugas Berhasil Dihapus');
    }
}
