<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;



class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengguna::query(); // Mulai query

        // 1. Terapkan Filter Role
        if ($request->filled('role') && $request->role !== 'semua') {
            $query->where('role', $request->role);
        }

        // 2. Terapkan Pencarian (Nama atau Email)
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Ambil daftar unik role yang tersedia untuk dropdown filter (opsional)
        $availableRoles = Pengguna::select('role')->distinct()->pluck('role');

        // Eksekusi query dengan pagination
        $pengguna = $query->paginate(10)->appends($request->query());

        // Kirim $availableRoles ke view
        return view('pengguna.index', compact('pengguna', 'availableRoles'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request){
        // Validasi data yang masuk dari form
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'role' => 'required|string',
        ]);

        // Buat pengguna baru
        $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => $request->role,
        ]);

        return redirect()->route('pengguna.index');
    }

    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        // Ambil semua data kecuali password
        $data = $request->except('password');

        // Jika password diisi, baru update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data
        $pengguna->update($data);

        return redirect()->route('pengguna.index');
    }

    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        // Soft delete (tidak menghapus data dari DB)
        $pengguna->delete();

        return redirect()
            ->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
