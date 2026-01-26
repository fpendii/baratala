<?php

namespace App\Http\Controllers\karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardControllerKaryawan extends Controller
{
    public function index()
    {
        return view('karyawan.dashboard.index');
    }
}
