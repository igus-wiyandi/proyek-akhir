<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Admin::all();
        return view('admin.index', compact('admin'));
    }

    public function create()
    {
        $admin = Admin::all();
        return view('admin.create', compact('admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:5|string',
            'email' => 'required|email|unique:admin|string',
            'password' => 'required|min:5|string',
        ], [
            'nama.min'=>'Nama minimal 5 karakter',
            'email.unique'=>'Email sudah dipakai',
            'password.min'=>'Password minimal 5 karakter',
            'nama.required'=>'Nama Tidak Boleh Kosong',
            'email.required'=>'Email Tidak Boleh Kosong',
            'password.required'=>'Password Tidak Boleh Kosong',
        ]);
        Admin::create([
            "nama" => $request->nama,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);
        return redirect()->route('admin.index')->with('success', 'Admin Berhasil Ditambah');
    }

    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama' => 'min:5|string',
            'email' => 'email|string',
            'password' => 'nullable|min:5|string',
        ],[
            'nama.min'=>'Nama minimal 5 karakter',
            'password.min'=>'Password minimal 5 karakter',
        ]);

        $admin->nama = $request->nama;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.index')->with('success', 'Admin Berhasil Diubah');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin Berhasil Dihapus');
    }
}
