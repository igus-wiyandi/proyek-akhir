<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TampilGuruController extends Controller
{
    public function index(){
        return view('tampil_guru.layout');
    }


}
