<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kategori;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $guru = Guru::all();
        $kategori = Kategori::all();
        $jabatan = Jabatan::with('guru', 'kategori')->paginate(5);
        return view('jabatan.index', compact('jabatan', 'guru', 'kategori'));
    }

    public function create()
    {
        $guru = Guru::all();
        $kategori = Kategori::all();
        $jabatan = Jabatan::with('guru', 'kategori')->get();
        return view('jabatan.create', compact('jabatan', 'guru', 'kategori'));
    }

    public function store(Request $request){
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'kategori_id' => 'required|exists:kategori,id',
            'honor' => 'required|integer|min:0',
        ]);


        Jabatan::create([
            "guru_id" => $request->guru_id,
            "kategori_id" => $request->kategori_id,
            "honor" => $request->honor,
        ]);

        return redirect()->route('jabatan.index')->with('success', 'Jabatan Berhasil Ditambah');
    }

    public function edit($id)
    {
        $jabatan = Jabatan::with('guru', 'kategori')->findOrFail($id);
        $guru = Guru::all();
        $kategori = Kategori::all();

        return view('jabatan.edit', compact('jabatan', 'guru', 'kategori'));
    }


    public function update(Request $request, Jabatan $jabatan){
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'kategori_id' => 'required|exists:kategori,id',
            'honor' => 'required|integer|min:0',
        ]);

            $jabatan->guru_id = $request->guru_id;
            $jabatan->kategori_id = $request->kategori_id;
            $jabatan->honor = $request->honor;

        $jabatan->save();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan Berhasil Ubah');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with('success', 'Jabatan Berhasil Dihapus');
    }
}
