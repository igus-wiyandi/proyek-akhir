<?php

namespace App\Http\Controllers;

use App\Rules\loginGuruRules;
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
            'password' => ['required', new loginGuruRules($request)],
        ], [
            'email.required'    => 'Email tidak boleh kosong.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

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
