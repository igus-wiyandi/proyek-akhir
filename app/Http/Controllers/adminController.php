<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Admin::with('user')->get();
        return view('admin.index', compact('admin'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|min:5|string',
            'email'    => 'required|email|unique:users,email|string',
            'password' => 'required|min:5|string',
        ], [
            'nama.min'          => 'Nama minimal 5 karakter',
            'nama.required'     => 'Nama tidak boleh kosong',
            'email.unique'      => 'Email sudah dipakai',
            'email.required'    => 'Email tidak boleh kosong',
            'password.min'      => 'Password minimal 5 karakter',
            'password.required' => 'Password tidak boleh kosong',
        ]);

        // Simpan ke tabel users
        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        // Simpan ke tabel admin
        Admin::create([
            'user_id' => $user->id,
            'nama'    => $request->nama,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambah');
    }

    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama'     => 'required|min:5|string',
            'email'    => 'required|email|unique:users,email,' . $admin->user_id . '|string',
            'password' => 'nullable|min:5|string',
        ], [
            'nama.min'       => 'Nama minimal 5 karakter',
            'nama.required'  => 'Nama tidak boleh kosong',
            'email.unique'   => 'Email sudah dipakai',
            'email.required' => 'Email tidak boleh kosong',
            'password.min'   => 'Password minimal 5 karakter',
        ]);

        // Update tabel admin
        $admin->nama = $request->nama;
        $admin->save();

        // Update tabel users
        $admin->user->name  = $request->nama;
        $admin->user->email = $request->email;

        if ($request->filled('password')) {
            $admin->user->password = Hash::make($request->password);
        }

        $admin->user->save();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diubah');
    }

   public function destroy(Admin $admin)
{
    // Cegah hapus akun sendiri
    if ($admin->user_id === auth()->id()) {
        return redirect()->route('admin.index')->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
    }

    // Hapus user → otomatis hapus admin karena cascade
    $admin->user->delete();

    return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus');
}
}
