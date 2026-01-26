<?php

namespace App\Http\Controllers\direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KaryawanControllerDirektur extends Controller
{
    /**
     * Menampilkan daftar karyawan (semua pengguna kecuali 'direktur').
     */
    public function index()
    {
        // Ambil semua pengguna dari database di mana role-nya bukan 'direktur'
        // dan lakukan paginasi untuk tampilan yang lebih baik
        $karyawan = Pengguna::where('role', '!=', 'direktur')->paginate(10);

        return view('direktur.karyawan.index', compact('karyawan'));
    }

    /**
     * Menampilkan form untuk membuat karyawan baru.
     */
    public function create()
    {
        return view('direktur.karyawan.create');
    }

    /**
     * Menyimpan data karyawan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'role' => ['required', 'string', Rule::in(['admin', 'karyawan', 'direktur', 'kepala teknik', 'enginer', 'produksi', 'keuangan'])],
        ]);

        // Buat objek Pengguna baru dan isi datanya
        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => $request->role,
        ]);

        return redirect()->route('direktur.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu karyawan.
     */
    public function show(Pengguna $karyawan)
    {
        return view('direktur.karyawan.show', compact('karyawan'));
    }

    /**
     * Menampilkan form untuk mengedit karyawan.
     */
    public function edit(Pengguna $karyawan)
    {
        return view('direktur.karyawan.edit', compact('karyawan'));
    }

    /**
     * Memperbarui data karyawan di database.
     */
    public function update(Request $request, Pengguna $karyawan)
    {
        // Validasi data yang masuk dari form
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna,email,' . $karyawan->id,
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'role' => ['required', 'string', Rule::in(['admin', 'karyawan', 'direktur', 'kepala teknik', 'enginer', 'produksi', 'keuangan'])],
        ];

        // Validasi password hanya jika diisi
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Perbarui data karyawan
        $karyawan->nama = $request->nama;
        $karyawan->email = $request->email;
        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password); // Enkripsi password baru
        }
        $karyawan->no_hp = $request->no_hp;
        $karyawan->alamat = $request->alamat;
        $karyawan->role = $request->role;
        $karyawan->save();

        return redirect()->route('direktur.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    /**
     * Menghapus karyawan dari database.
     */
    public function destroy(Pengguna $karyawan)
    {
        $karyawan->delete();

        return redirect()->route('direktur.karyawan.index')->with('success', 'Karyawan berhasil dihapus!');
    }
}
