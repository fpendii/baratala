<?php

namespace App\Http\Controllers\karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ProfilControllerKaryawan extends Controller
{
    public function index()
    {
        $user = Session::get('pengguna'); // ambil data dari session
        $role = Session::get('role');     // role juga udah diset pas login

        return view('karyawan.profil.index', compact('user'));
    }
}
