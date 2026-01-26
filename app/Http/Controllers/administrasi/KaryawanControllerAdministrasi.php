<?php

namespace App\Http\Controllers\administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;

class KaryawanControllerAdministrasi extends Controller
{
    public function index()
    {
        $karyawan = Pengguna::where('role', '!=', 'direktur')->paginate(10);

        return view('administrasi.karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        return view('administrasi.karyawan.create');
    }

    public function edit($id)
    {
        $karyawan = Pengguna::findOrFail($id);
        return view('administrasi.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Pengguna::findOrFail($id);
        $karyawan->update($request->all());
        return redirect()->route('administrasi.karyawan.index');
    }
}
