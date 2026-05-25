<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TampilGuruController extends Controller
{
    public function index()
    {
        return view('tampil_guru.layout');
    }

    public function edit($id)
    {
        $guru = Guru::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        return view('tampil_guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $request->validate([
            'nama'     => 'required|min:5|string',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'password' => 'nullable|min:5|string',
            'no_hp'    => 'required|min:10|string',
            'alamat'   => 'required|min:5|string',
        ], [
            'nama.required'  => 'Nama tidak boleh kosong',
            'nama.min'       => 'Nama minimal 5 karakter',
            'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
            'password.min'   => 'Password minimal 5 karakter',
            'no_hp.required' => 'Nomor HP tidak boleh kosong',
            'no_hp.min'      => 'Nomor HP minimal 10 karakter',
            'alamat.required'=> 'Alamat tidak boleh kosong',
            'alamat.min'     => 'Alamat minimal 5 karakter',
        ]);

       // Update tabel guru
    $guru->nama   = $request->nama;
    $guru->jenis_kelamin = $request->jenis_kelamin;
    $guru->no_hp  = $request->no_hp;
    $guru->alamat = $request->alamat;
    $guru->save();

    // Update tabel users
    $guru->user->name = $request->nama;

    if ($request->filled('password')) {
    $guru->user->password = Hash::make($request->password);
}

$guru->user->save();

        return redirect()->route('guru.info')->with('success', 'Profil berhasil diubah');
    }
}
