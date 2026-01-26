<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Session::get('pengguna');

        return view('profil.index', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',

            // VALIDASI PASSWORD
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = Pengguna::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $user->nama   = $request->nama;
        $user->email  = $request->email;
        $user->no_hp  = $request->no_hp;
        $user->alamat = $request->alamat;

        // 🔐 UPDATE PASSWORD JIKA DIISI
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // UPDATE SESSION
        Session::put('pengguna', $user->toArray());
        Session::put('nama', $user->nama);
        Session::put('email', $user->email);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
