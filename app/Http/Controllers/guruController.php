<?php

namespace App\Http\Controllers;
use App\Models\guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class guruController extends Controller
{
    public function index()
    {
        $guru = guru::paginate(5);
        return view('guru.index', compact('guru'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:5|string',
            'email' => 'required|email|unique:admin|string',
            'password' => 'required|min:5|string',
            'no_hp' => 'required|min:12|string',
            'alamat' => 'required|min:5|string',
        ],[
            'nama.min'=>'Nama minimal 5 karakter',
            'email.unique'=>'Email sudah dipakai',
            'password.min'=>'Password minimal 5 karakter',
            'no_hp.min'=>'Nomor HP minimal 12 karakter',
            'alamat.min'=>'Alamat minimal 5 karakter',
            'nama.required'=>'Nama Tidak Boleh Kosong',
            'email.required'=>'Email Tidak Boleh Kosong',
            'password.required'=>'Password Tidak Boleh Kosong',
            'no_hp.required'=>'Nomor HP Tidak Boleh Kosong',
            'alamat.required'=>'Alamat Tidak Boleh Kosong',
        ]);

        guru::create([
            "nama" => $request->nama,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "no_hp" => $request->no_hp,
            "alamat" => $request->alamat,
        ]);
        return redirect()->route('guru.index')->with('success', 'Guru Berhasil Ditambah');
    }

    public function edit($id)
    {
        $guru = guru::findOrFail($id);
        return view('guru.edit', compact('guru'));
    }


    public function layout()
    {
        $guruId = Session::get('guru_id');

        if (!$guruId) {
            return redirect()->route('loginGuru')->with('error', 'Silakan login dulu.');
        }

        $guru = guru::findOrFail($guruId);

        return view('tampil_guru.layout', compact('guru'));
    }

    public function update(Request $request, guru $guru)
    {

        $request->validate([
            'nama' => 'min:5|string',
            'email' => 'min:5|string',
            'password' => 'nullable|min:5|string',
            'no_hp' => 'min:12|string',
            'alamat' => 'min:5|string',
        ],[
            'nama.min'=>'Nama minimal 5 karakter',
            'password.min'=>'Password minimal 5 karakter',
            'no_hp.min'=>'Nomor HP minimal 12 karakter',
            'alamat.min'=>'Alamat minimal 5 karakter',
        ]);

        $guru->nama = $request->nama;
        $guru->email = $request->email;
        $guru->no_hp = $request->no_hp;
        $guru->alamat = $request->alamat;

        if ($request->filled('password')) {
            $guru->password = Hash::make($request->password);
        }

        $guru->save();

        return redirect()->route('guru.info')->with('success', 'Guru Berhasil Diubah');
    }

    public function destroy(guru $guru)
    {
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Guru Berhasil Dihapus');
    }

    public function infoguru(){
        $guruId = Session::get('guru_id');

        if (!$guruId) {
            return redirect()->route('loginGuru')->with('error', 'Silakan login dulu.');
        }

        $guru = guru::findOrFail($guruId);

        return view('guru.info', compact('guru'));
    }

    public function show($id)
{
    $guru = guru::findOrFail($id);
    return view('guru.show', compact('guru'));
}


}
