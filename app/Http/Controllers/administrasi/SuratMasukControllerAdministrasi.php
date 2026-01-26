<?php

namespace App\Http\Controllers\administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class SuratMasukControllerAdministrasi extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::orderBy('tanggal_terima', 'desc')->get();
        return view('administrasi.surat-masuk.index', compact('suratMasuk'));
    }

    public function create()
    {
        return view('administrasi.surat-masuk.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi data (Tambahkan validasi untuk file lampiran)
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
            'nomor_surat' => 'nullable|string|max:100|unique:surat_masuk,nomor_surat',
            'tanggal_terima' => 'required|date',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // File opsional, tipe file, maks 5MB
            'keterangan' => 'nullable|string',
        ]);

        // 2. Proses upload lampiran (jika ada file yang diunggah)
        if ($request->hasFile('lampiran')) {
            // Simpan file di folder 'surat_masuk_lampiran' di disk 'public'
            $filePath = $request->file('lampiran')->store('surat_masuk_lampiran', 'public');
            $validatedData['lampiran'] = $filePath;
        }

        // 3. Simpan ke database
        SuratMasuk::create($validatedData);

        return redirect()->to('administrasi/surat-masuk')
            ->with('success', 'Surat Masuk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);
        return view('administrasi.surat-masuk.edit', compact('suratMasuk'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        // 1. Validasi data
        // Menggunakan $suratMasuk->id untuk mengabaikan (ignore) nomor surat yang dimiliki oleh record ini sendiri.
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
            // Kunci perbaikan: Menambahkan 'ignore' dengan ID $suratMasuk
            'nomor_surat' => 'nullable|string|max:100' ,
            'tanggal_terima' => 'required|date',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // File opsional
            'keterangan' => 'nullable|string',
        ]);

        // 2. Proses update lampiran
        if ($request->hasFile('lampiran')) {
            // Hapus lampiran lama jika ada
            if ($suratMasuk->lampiran) {
                Storage::disk('public')->delete($suratMasuk->lampiran);
            }

            // Simpan file baru
            $filePath = $request->file('lampiran')->store('surat_masuk_lampiran', 'public');
            $validatedData['lampiran'] = $filePath;
        }
        // Jika ada field atau checkbox untuk menghapus lampiran, Anda bisa menambahkannya di sini.
        // Contoh: if ($request->input('hapus_lampiran')) { ... }

        // 3. Update database
        $suratMasuk->update($validatedData);

        return redirect()->to('administrasi/surat-masuk')
            ->with('success', 'Surat Masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);
        // Hapus lampiran fisik (jika ada) sebelum menghapus record database
        if ($suratMasuk->lampiran) {
            Storage::disk('public')->delete($suratMasuk->lampiran);
        }

        $suratMasuk->delete();

        return redirect()->to('administrasi/surat-masuk')
            ->with('success', 'Surat Masuk berhasil dihapus.');
    }
}
