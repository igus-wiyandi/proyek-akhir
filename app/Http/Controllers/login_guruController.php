<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\loginGuruRules;
use Illuminate\Http\Request;
use App\Models\guru;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class login_guruController extends Controller
{

    public function loginGuru()
    {
        return view('login_guru.layout');
    }

    public function registerGuru()
    {
        return view('login_guru.register');
    }

    public function prosesregisGuru(Request $request)
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
        return redirect()->route('loginGuru');
    }

    public function prosesloginGuru(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', new loginGuruRules($request)],
    ]);


    $guru = guru::where('email', $request->email)->first();


    Session::put('guru_id', $guru->id);

    return redirect()->route('guru.layout');
}

    public function logoutGuru()
    {
        Session::flush();
        return redirect()->route('loginGuru');
    }
}
