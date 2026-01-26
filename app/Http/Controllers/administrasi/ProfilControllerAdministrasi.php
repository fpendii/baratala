<?php

namespace App\Http\Controllers\administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilControllerAdministrasi extends Controller
{
    public function index()
    {
        return view('administrasi.profil.index');
    }
}
