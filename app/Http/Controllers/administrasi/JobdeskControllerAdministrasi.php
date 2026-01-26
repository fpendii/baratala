<?php

namespace App\Http\Controllers\administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\DB;

class JobdeskControllerAdministrasi extends Controller
{
    public function index()
    {
        $jobdesks = Jobdesk::all();
        return view('administrasi.jobdesk.index', compact('jobdesks'));
    }

    public function create()
    {
        return view('administrasi.jobdesk.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            // Divisi harus tunggal dan merupakan salah satu opsi yang valid
            'divisi'          => 'required|string|in:direktur,kepala teknik,enginer,produksi,keuangan',
            // Judul Jobdesk diharapkan dalam bentuk array, dan setiap elemennya harus diisi
            'judul_jobdesk.*' => 'required|string|max:255',
        ], [
            // Custom messages jika diperlukan
            'judul_jobdesk.*.required' => 'Judul jobdesk tidak boleh kosong.',
            'divisi.in' => 'Divisi yang dipilih tidak valid.'
        ]);

        $divisiTujuan = $request->divisi;
        $judulJobdeskArray = $request->judul_jobdesk;
        $count = 0; // Untuk menghitung berapa jobdesk yang berhasil disimpan

        DB::beginTransaction();
        try {
            // 2. Perulangan untuk menyimpan multiple jobdesk
            foreach ($judulJobdeskArray as $judul) {
                // Pastikan nilai judul tidak kosong (walaupun sudah ada validasi, ini sebagai safety check)
                if (!empty($judul)) {
                    Jobdesk::create([
                        'judul_jobdesk' => $judul,
                        'divisi'        => $divisiTujuan,
                    ]);
                    $count++;
                }
            }

            DB::commit();

            // 3. Respon setelah penyimpanan
            $message = $count > 1
                ? "$count Jobdesk berhasil ditambahkan untuk divisi " . ucfirst($divisiTujuan)
                : "1 Jobdesk berhasil ditambahkan untuk divisi " . ucfirst($divisiTujuan);

            return redirect()->to('/administrasi/jobdesk')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            // Logging error untuk debugging di server
            // Log::error('Gagal menyimpan multiple jobdesk: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyimpan jobdesk. Terjadi kesalahan sistem.')->withInput();
        }
    }

    public function edit($id)
    {
        $jobdesk = Jobdesk::findOrFail($id);
        return view('administrasi.jobdesk.edit', compact('jobdesk'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_jobdesk' => 'required|string|max:255',
            'divisi'        => 'required|string|max:255',
        ]);

        $jobdesk = Jobdesk::findOrFail($id);
        $jobdesk->update($request->all());

        return redirect()->to('administrasi/jobdesk')
                            ->with('success', 'Jobdesk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jobdesk = Jobdesk::findOrFail($id);
        $jobdesk->delete();

        return redirect()->to('administrasi/jobdesk')
                            ->with('success', 'Jobdesk berhasil dihapus');
    }
}
