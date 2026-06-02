<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginGuruController extends Controller
{
    public function loginGuru()
    {
        return view('login_guru.layout');
    }
    public function prosesloginGuru(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email tidak boleh kosong.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        if (Auth::user()->role !== 'guru') {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun ini bukan guru.'])->withInput();
        }

        $request->session()->regenerate();
        return redirect()->route('guru.layout');
    }
    public function logoutGuru()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('loginGuru');
    }
}
