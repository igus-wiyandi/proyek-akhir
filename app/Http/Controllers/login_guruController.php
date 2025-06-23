<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\loginGuruRules;
use Illuminate\Http\Request;
use App\Models\guru;
use Illuminate\Support\Facades\Session;


class login_guruController extends Controller
{

    public function loginGuru()
    {
        return view('login_guru.layout');
    }

    public function prosesloginGuru(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', new loginGuruRules($request)],
    ]);


    $guru = guru::where('email', $request->email)->first();


    Session::put('guru_id', $guru->id);

    return redirect()->route('tampilGuru.index');
}

    public function logoutGuru()
    {
        Session::flush();
        return redirect()->route('loginGuru');
    }
}
