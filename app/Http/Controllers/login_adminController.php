<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\loginAdminRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class login_adminController extends Controller
{

    public function loginAdmin()
    {
        return view('login_admin.layout');
    }

    public function prosesloginAdmin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', new loginAdminRules($request)],
        ]);

        return redirect()->route('admin.index');
    }

    public function logoutAdmin()
    {
        Session::flush();
        return redirect()->route('loginAdmin');
    }
}
