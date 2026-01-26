<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\DB;

class DataJobdeskController extends Controller
{
    public function index(Request $request) // Terima objek Request
    {
        $query = Jobdesk::query(); // Mulai query

        // 1. Terapkan Filter Divisi
        if ($request->filled('divisi') && $request->divisi !== 'semua') {
            $query->where('divisi', $request->divisi);
        }

        // 2. Terapkan Pencarian Judul Jobdesk
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where('judul_jobdesk', 'like', '%' . $search . '%');
        }

        // Ambil daftar unik divisi untuk dropdown filter
        // Ini memastikan dropdown hanya menampilkan divisi yang sudah ada di database.
        $availableDivisions = Jobdesk::select('divisi')->distinct()->pluck('divisi');

        // Eksekusi query (Gunakan pagination disarankan untuk data administrasi)
        $jobdesks = $query->paginate(10)->appends($request->query());

        // CATATAN: Jika Anda TIDAK menggunakan pagination, ganti baris di atas dengan:
        // $jobdesks = $query->get();

        // Kirim $availableDivisions ke view
        return view('data-jobdesk.index', compact('jobdesks', 'availableDivisions'));
    }

    public function create()
    {
        return view('data-jobdesk.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            // Divisi harus tunggal dan merupakan salah satu opsi yang valid
            'divisi'          => 'required|string',
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

            return redirect()->to('/data-jobdesk')->with('success', $message);
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
        return view('data-jobdesk.edit', compact('jobdesk'));
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

        return redirect()->to('data-jobdesk')
            ->with('success', 'Jobdesk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jobdesk = Jobdesk::findOrFail($id);
        $jobdesk->delete();

        return redirect()->to('administrasi/jobdesk')
            ->with('success', 'Jobdesk berhasil dihapus');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'Pilih minimal satu data untuk dihapus.');
        }

        try {
            // Menghapus data berdasarkan array ID yang dipilih
            \App\Models\Jobdesk::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', count($ids) . ' data jobdesk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
