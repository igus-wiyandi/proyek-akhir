<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginAdminController extends Controller
{
    public function loginAdmin()
    {
        return view('login_admin.layout');
    }
    public function prosesloginAdmin(Request $request)
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

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun ini bukan admin.'])->withInput();
        }

        $request->session()->regenerate();
        return redirect()->route('admin.index');
    }
    public function logoutAdmin()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('loginAdmin');
    }
}
