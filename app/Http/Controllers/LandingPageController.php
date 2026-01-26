<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;


class LandingPageController extends Controller
{
    public function index()
    {
        
        return view('landingpage.index');
    }
}
