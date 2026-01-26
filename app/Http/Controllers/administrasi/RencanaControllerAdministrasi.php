<?php

namespace App\Http\Controllers\administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RencanaControllerAdministrasi extends Controller
{
    public function index()
    {
        // Ambil semua tugas, urutkan terbaru dulu
        $tugas = Tugas::orderBy('created_at', 'desc')->get();
        return view('administrasi.rencana-kerja.index', compact('tugas'));
    }

    public function create()
    {
        return view('administrasi.rencana-kerja.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_rencana'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'status'           => 'required|string',
            'jenis'            => 'required|string',
            'prioritas'        => 'nullable|string',
            'lampiran'         => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'catatan'          => 'nullable|string',
            'pengguna'         => 'nullable|string', // Hidden input, koma dipisahkan
        ]);

        // Simpan user yang membuat tugas (sebagai pembuat)
        $validated['id_pengguna'] = Auth::id();


        if ($request->hasFile('lampiran')) {
            // Simpan file di folder 'surat_masuk_lampiran' di disk 'public'
            $filePath = $request->file('lampiran')->store('rencana_kerja_lampiran', 'public');
            $validated['lampiran'] = $filePath;
        }

        // Simpan tugas
        $tugas = Tugas::create($validated);

        // Ambil id pengguna dari input (pecah string jadi array)
        $penggunaIds = [];
        if (!empty($request->pengguna)) {
            $penggunaIds = array_filter(explode(',', $request->pengguna));
        }


        // Tambahkan user yang sedang login (pembuat) ke daftar pengguna yang ditugaskan
        $penggunaIds[] = Auth::id();

        // Hilangkan duplikasi & validasi hanya id yang valid di tabel pengguna
        $penggunaIds = Pengguna::whereIn('id', array_unique($penggunaIds))->pluck('id')->toArray();

        // Simpan ke pivot
        $tugas->pengguna()->sync($penggunaIds);

        return redirect()->to('administrasi/rencana')->with('success', 'Rencana berhasil ditambahkan.');
    }

    public function show($id)
    {
        $tugas = Tugas::with('pengguna', 'komentar.pengguna')->findOrFail($id);
        return view('administrasi.rencana-kerja.detail', compact('tugas'));
    }

    public function edit($id)
    {
        $rencana = Tugas::with(['pengguna', 'komentar.pengguna'])->findOrFail($id);
        // Ambil semua pengguna kecuali dirinya sendiri untuk penugasan
        $users = Pengguna::where('id', '!=', Auth::id())->get();
        return view('administrasi.rencana-kerja.edit', compact('rencana', 'users'));
    }



    public function destroy($id)
    {
        // Ambil data rencana/tugas
        $rencana = Tugas::findOrFail($id);

        // Hapus file lampiran jika ada
        if (!empty($rencana->lampiran) && Storage::disk('public')->exists($rencana->lampiran)) {
            Storage::disk('public')->delete($rencana->lampiran);
        }

        // Hapus data dari database
        $rencana->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->to('administrasi/rencana')->with('success', 'Rencana dan lampirannya berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $rencana = Tugas::findOrFail($id);

        $validated = $request->validate([
            'judul_rencana'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'status'           => 'required|string',
            'jenis'            => 'required|string',
            'prioritas'        => 'nullable|string',
            'lampiran'         => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'catatan'          => 'nullable|string',
            'pengguna'         => 'nullable|string', // Hidden input, koma dipisahkan
        ]);

        if ($request->hasFile('lampiran')) {
            // Hapus file lama jika ada
            if (!empty($rencana->lampiran) && Storage::disk('public')->exists($rencana->lampiran)) {
                Storage::disk('public')->delete($rencana->lampiran);
            }
            // Simpan file baru
            $filePath = $request->file('lampiran')->store('rencana_kerja_lampiran', 'public');
            $validated['lampiran'] = $filePath;
        }

        // Update data rencana
        $rencana->update($validated);

        // Ambil id pengguna dari input (pecah string jadi array)
        $penggunaIds = [];
        if (!empty($request->pengguna)) {
            $penggunaIds = array_filter(explode(',', $request->pengguna));
        }

        // Tambahkan user yang sedang login (pembuat) ke daftar pengguna yang ditugaskan
        $penggunaIds[] = Auth::id();

        // Hilangkan duplikasi & validasi hanya id yang valid di tabel pengguna
        $penggunaIds = Pengguna::whereIn('id', array_unique($penggunaIds))->pluck('id')->toArray();

        // Simpan ke pivot
        $rencana->pengguna()->sync($penggunaIds);

        return redirect()->to('administrasi/rencana')->with('success', 'Rencana berhasil diupdate.');
    }
}
