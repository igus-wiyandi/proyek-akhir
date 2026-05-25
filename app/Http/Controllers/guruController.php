<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with('user')->paginate(5);
        return view('guru.index', compact('guru'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|min:5|string',
            'nik'      => 'required|digits:16|unique:guru,nik',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:5|string',
            'no_hp'    => 'required|min:10|string',
            'alamat'   => 'required|min:5|string',
        ], [
            'nama.required'     => 'Nama tidak boleh kosong',
            'nama.min'          => 'Nama minimal 5 karakter',
            'nik.required'      => 'NIK tidak boleh kosong',
            'nik.digits'        => 'NIK harus 16 digit angka',
            'nik.unique'        => 'NIK sudah terdaftar',
            'jenis_kelamin.required' => 'Jenis kelamin tidak boleh kosong',
            'jenis_kelamin.in'       => 'Jenis kelamin tidak valid',
            'email.required'    => 'Email tidak boleh kosong',
            'email.unique'      => 'Email sudah dipakai',
            'password.required' => 'Password tidak boleh kosong',
            'password.min'      => 'Password minimal 5 karakter',
            'no_hp.required'    => 'Nomor HP tidak boleh kosong',
            'no_hp.min'         => 'Nomor HP minimal 10 karakter',
            'alamat.required'   => 'Alamat tidak boleh kosong',
            'alamat.min'        => 'Alamat minimal 5 karakter',
        ]);

        // Simpan ke tabel users
        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        // Simpan ke tabel guru
        Guru::create([
            'user_id' => $user->id,
            'nama'    => $request->nama,
            'nik'     => $request->nik,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp'   => $request->no_hp,
            'alamat'  => $request->alamat,
        ]);

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambah');
    }

    public function edit($id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama'     => 'required|min:5|string',
            'nik'      => 'required|digits:16|unique:guru,nik,' . $guru->id,
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'email'    => 'required|email|unique:users,email,' . $guru->user_id,
            'password' => 'nullable|min:5|string',
            'no_hp'    => 'required|min:10|string',
            'alamat'   => 'required|min:5|string',
        ], [
            'nama.min'     => 'Nama minimal 5 karakter',
            'nik.digits'   => 'NIK harus 16 digit angka',
            'nik.unique'   => 'NIK sudah terdaftar',
            'email.unique' => 'Email sudah dipakai',
            'password.min' => 'Password minimal 5 karakter',
            'no_hp.min'    => 'Nomor HP minimal 10 karakter',
            'alamat.min'   => 'Alamat minimal 5 karakter',
        ]);

        // Update tabel guru
        $guru->nama   = $request->nama;
        $guru->nik    = $request->nik;
        $guru->jenis_kelamin = $request->jenis_kelamin;
        $guru->no_hp  = $request->no_hp;
        $guru->alamat = $request->alamat;
        $guru->save();

        // Update tabel users
        $guru->user->name  = $request->nama;
        $guru->user->email = $request->email;

        if ($request->filled('password')) {
            $guru->user->password = Hash::make($request->password);
        }

        $guru->user->save();

        return redirect()->route('guru.index')->with('success', 'Guru berhasil diubah');
    }

    public function destroy(Guru $guru)
{
    if ($guru->user) {
        $guru->user->delete();
    } else {
        $guru->delete();
    }
    return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus');
}

    // =====================
    // UNTUK GURU SENDIRI
    // =====================
    public function layout()
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        return view('tampil_guru.layout', compact('guru'));
    }

    public function infoguru()
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        return view('guru.info', compact('guru'));
    }

    public function show($id)
    {
        $guru = Guru::findOrFail($id);
        return view('guru.show', compact('guru'));
    }
}
