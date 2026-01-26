<?php

namespace App\Http\Controllers\direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Pengguna;
use App\Models\Komentar;
use Illuminate\Support\Facades\Auth;

class RencanaControllerDirektur extends Controller
{
    public function index()
    {
        // Ambil semua tugas, urutkan terbaru dulu
        $tugas = Tugas::orderBy('created_at', 'desc')->get();

        return view('direktur.rencana.index', compact('tugas'));
    }

    public function create()
    {
        $users = Pengguna::where('id', '!=', Auth::id())->get();
        return view('direktur.rencana.create', compact('users'));
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
            'pengguna'         => 'nullable|string', // masih string di sini
        ]);

        // simpan user yang membuat tugas (sebagai pembuat, bukan pivot)
        $validated['id_pengguna'] = Auth::id();

        // simpan file lampiran jika ada
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('public/uploads');
            $validated['lampiran'] = str_replace('public/', '', $lampiranPath);
        }

        // simpan tugas
        $tugas = Tugas::create($validated);

        // ambil id pengguna dari input (pecah string jadi array)
        $penggunaIds = [];
        if (!empty($request->pengguna)) {
            $penggunaIds = array_filter(explode(',', $request->pengguna));
        }

        // validasi hanya id user yang ada di tabel users
        $penggunaIds = Pengguna::whereIn('id', $penggunaIds)->pluck('id')->toArray();

        // simpan ke pivot
        $tugas->pengguna()->sync($penggunaIds);

        return redirect()->route('direktur.rencana.index')->with('success', 'Rencana berhasil ditambahkan.');
    }


    public function show($id)
    {
        $tugas = Tugas::with(['komentar.pengguna', 'pengguna'])->findOrFail($id);
        // dd($tugas);
        $allUsers = \App\Models\Pengguna::all(); // semua user

        return view('direktur.rencana.detail', compact('tugas', 'allUsers'));
    }


    public function edit($id)
    {
        $rencana = Tugas::with(['komentar.pengguna', 'pengguna'])->findOrFail($id);
        // dd($rencana);
        return view('direktur.rencana.edit', compact('rencana'));
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

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
            'pengguna'         => 'nullable|string', // hidden input, comma separated
        ]);

        // kalau ada lampiran baru, simpan
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('public/uploads');
            $validated['lampiran'] = str_replace('public/', '', $lampiranPath);
        }

        // update tugas
        $tugas->update($validated);

        // update pivot pengguna
        $penggunaIds = [];
        if (!empty($request->pengguna)) {
            $penggunaIds = array_filter(explode(',', $request->pengguna));
        }

        // validasi hanya user yang ada
        $penggunaIds = Pengguna::whereIn('id', $penggunaIds)->pluck('id')->toArray();

        // sync pivot → otomatis tambah kalau ada id baru, hapus kalau tidak ada lagi
        $tugas->pengguna()->sync($penggunaIds);

        return redirect()->route('direktur.rencana.index')->with('success', 'Rencana berhasil diperbarui.');
    }

    public function updatePengguna(Request $request, $id)
    {
        dd($request->all());
        $tugas = Tugas::findOrFail($id);

        // validasi input pengguna (array id user)
        $validated = $request->validate([
            'pengguna'   => 'nullable|array',
            'pengguna.*' => 'exists:pengguna,id', // pastikan semua id user valid
        ]);

        // kalau ada pengguna terpilih → ambil
        $penggunaIds = $validated['pengguna'] ?? [];

        // sync pivot (otomatis tambah baru dan hapus yg tidak ada lagi)
        $tugas->pengguna()->sync($penggunaIds);

        return redirect()
            ->route('direktur.rencana.show', $tugas->id)
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function komentar(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'komentar_direktur' => 'required|string|max:1000',
        ]);

        // Pastikan tugas ada
        $tugas = Tugas::findOrFail($id);
        // dd($tugas, $request->all());
        // Simpan komentar
        Komentar::create([
            'tugas_id'    => $tugas->id,
            'pengguna_id' => Auth::id(),  // ambil user yang login
            'isi'         => $request->komentar_direktur,
            'status'      => 'menunggu', // default
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function updateKomentarStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:setuju,tolak,menunggu',
        ]);

        $komentar = \App\Models\Komentar::findOrFail($id);
        $komentar->status = $request->status;
        $komentar->save();

        return redirect()->back()->with('success', 'Status komentar diperbarui.');
    }



    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();

        return redirect()->route('direktur.rencana.index')->with('success', 'Rencana berhasil dihapus.');
    }
}
