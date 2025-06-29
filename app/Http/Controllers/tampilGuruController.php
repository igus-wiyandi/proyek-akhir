<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class tampilGuruController extends Controller
{
    public function index(){
        return view('tampil_guru.layout');
    }


}
