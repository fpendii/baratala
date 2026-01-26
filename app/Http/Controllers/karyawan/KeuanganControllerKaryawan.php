<?php

namespace App\Http\Controllers\karyawan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use App\Models\LaporanKeuangan;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\LaporanKeuanganExport;
use Maatwebsite\Excel\Facades\Excel; // Pastikan ini di-import

class KeuanganControllerKaryawan extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanKeuangan::with('pengguna');

        // Filter bulan/tanggal
        if ($request->filled('filter_tanggal')) {
            $query->whereYear('tanggal', substr($request->filter_tanggal, 0, 4))
                ->whereMonth('tanggal', substr($request->filter_tanggal, 5, 2));
        }

        // Filter jenis
        if ($request->filled('filter_jenis')) {
            $query->where('jenis', $request->filter_jenis);
        }

        // Filter pengguna
        if ($request->filled('filter_pengguna')) {
            $query->where('id_pengguna', $request->filter_pengguna);
        }

        $laporanKeuangan = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();


        $uangKas = Keuangan::first();
        $uangKeluar = LaporanKeuangan::where('jenis', 'pengeluaran')->sum('nominal');
        $uangMasuk = LaporanKeuangan::where('jenis', 'uang_masuk')->sum('nominal');
        $daftarKaryawan = Pengguna::all();

        return view('karyawan.keuangan.index', compact('laporanKeuangan', 'uangKeluar', 'uangKas', 'daftarKaryawan', 'uangMasuk'));
    }



    public function createPengeluaran()
    {

        $daftarKaryawan = Pengguna::get();

        return view('karyawan.keuangan.create-pengeluaran', compact('daftarKaryawan'));
    }

    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keperluan' => 'required|string',
            'nominal' => 'required|string',
            'jenis_uang' => 'required|in:kas,bank',
            'lampiran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'persetujuan_direktur' => 'required',
        ]);

        // Bersihkan nominal -> hapus titik/koma pemisah ribuan
        // Menggunakan (int) atau (float) untuk memastikan konversi ke tipe numerik jika diperlukan,
        // tetapi karena database menggunakan DECIMAL(15,2), kita biarkan sebagai string angka bersih,
        // namun untuk perbandingan dan pengurangan kita perlu konversi.
        $nominal = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominalNumeric = (float)$nominal; // Konversi ke float untuk operasi matematika

        DB::beginTransaction();
        try {
            $pengeluaran = new LaporanKeuangan();
            // Menggunakan Keuangan::first() untuk mendapatkan baris data keuangan utama
            $keuangan = Keuangan::first();

            // Cek apakah data Keuangan tersedia
            if (!$keuangan) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data Keuangan utama tidak ditemukan.')->withInput();
            }

            $pengeluaran->id_keuangan = $keuangan->id;
            $pengeluaran->id_pengguna = Auth::id(); // nanti bisa diganti auth()->id()
            $pengeluaran->tanggal = $request->tanggal;
            $pengeluaran->keperluan = $request->keperluan;
            $pengeluaran->nominal = $nominalNumeric; // Simpan sebagai angka
            $pengeluaran->penerima = $request->penerima;
            $pengeluaran->jenis = 'pengeluaran';
            $pengeluaran->jenis_uang = $request->jenis_uang;
            $pengeluaran->persetujuan_direktur = $request->persetujuan_direktur;


            // jika memerlukan persetujuan direktur
            if ($request->persetujuan_direktur == 1) {
                // Jika perlu persetujuan direktur, set status menunggu
                $pengeluaran->status_persetujuan = 'menunggu';
            } else {
                // Jika tidak perlu persetujuan, langsung set disetujui
                $pengeluaran->status_persetujuan = 'tanpa persetujuan';

                // --- Logika Pengurangan Saldo berdasarkan jenis_uang ---
                $jenisUang = $request->jenis_uang;

                if ($jenisUang == 'kas') {
                    // Kurangi sisa uang kas
                    if ($keuangan->uang_kas < $nominalNumeric) {
                        DB::rollBack();
                        return redirect()->to('/karyawan/keuangan/pengeluaran/create')->with('error', 'Saldo **Kas** tidak mencukupi!')->withInput();
                    }
                    $keuangan->uang_kas -= $nominalNumeric;
                } elseif ($jenisUang == 'bank') {
                    // Kurangi sisa uang rekening
                    if ($keuangan->uang_rekening < $nominalNumeric) {
                        DB::rollBack();
                        return redirect()->to('/karyawan/keuangan/pengeluaran/create')->with('error', 'Saldo **Bank** tidak mencukupi!')->withInput();
                    }
                    $keuangan->uang_rekening -= $nominalNumeric;
                }

                // Juga kurangi kolom 'nominal' yang merupakan total (kas + rekening)
                $keuangan->nominal -= $nominalNumeric;

                // Simpan perubahan pada tabel Keuangan
                $keuangan->save();
                // --------------------------------------------------------
            }


            // Upload lampiran
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/lampiran'), $filename);
                $pengeluaran->lampiran = $filename;
            }


            $pengeluaran->save();
            DB::commit();

            return redirect()->to('/karyawan/keuangan')->with('success', 'Pengeluaran kas berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pengeluaran: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function createKasbon()
    {
        $daftarKaryawan = Pengguna::get();

        return view('karyawan.keuangan.create-kasbon', compact('daftarKaryawan'));
    }

    public function storeKasbon(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keperluan' => 'required|string',
            'nominal' => 'required|string',
            'jenis_uang' => 'required|in:kas,bank',
            'status_persetujuan' => 'required|in:menunggu,disetujui,tanpa persetujuan', // Sesuaikan opsi status
        ]);

        // Bersihkan nominal -> hapus titik/koma pemisah ribuan
        $nominal = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominalNumeric = (float)$nominal; // Konversi ke float untuk operasi matematika

        DB::beginTransaction();
        try {
            $kasbon = new LaporanKeuangan();
            $keuangan = Keuangan::first();

            // Cek apakah data Keuangan tersedia
            if (!$keuangan) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data Keuangan utama tidak ditemukan.')->withInput();
            }

            $kasbon->id_keuangan = $keuangan->id;
            $kasbon->id_pengguna = Auth::id();
            $kasbon->tanggal = $request->tanggal;
            $kasbon->keperluan = $request->keperluan;
            $kasbon->nominal = $nominalNumeric;
            $kasbon->penerima = $request->penerima;
            $kasbon->jenis = 'kasbon';
            $kasbon->jenis_uang = $request->jenis_uang;
            $kasbon->status_persetujuan = $request->status_persetujuan;
            $kasbon->persetujuan_direktur = $request->status_persetujuan == 'menunggu' ? 1 : 0; // Sesuaikan jika perlu kolom ini

            // Logika Pengurangan Saldo hanya jika status_persetujuan BUKAN 'menunggu'
            if ($request->status_persetujuan != 'menunggu') {
                $jenisUang = $request->jenis_uang;

                if ($jenisUang == 'kas') {
                    // Kurangi sisa uang kas
                    if ($keuangan->uang_kas < $nominalNumeric) {
                        DB::rollBack();
                        return redirect()->to('/karyawan/keuangan/kasbon/create')->with('error', 'Saldo **Kas** tidak mencukupi!')->withInput();
                    }
                    $keuangan->uang_kas -= $nominalNumeric;
                } elseif ($jenisUang == 'bank') {
                    // Kurangi sisa uang rekening
                    if ($keuangan->uang_rekening < $nominalNumeric) {
                        DB::rollBack();
                        return redirect()->to('/karyawan/keuangan/kasbon/create')->with('error', 'Saldo **Bank** tidak mencukupi!')->withInput();
                    }
                    $keuangan->uang_rekening -= $nominalNumeric;
                }

                // Kurangi juga kolom 'nominal' yang merupakan total (kas + rekening)
                $keuangan->nominal -= $nominalNumeric;

                // Simpan perubahan pada tabel Keuangan
                $keuangan->save();
            }

            // Simpan data kasbon (laporan keuangan)
            $kasbon->save();

            DB::commit();

            return redirect()->to('/karyawan/keuangan')->with('success', 'Kasbon berhasil disimpan dengan status ' . $request->status_persetujuan . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan kasbon: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function createUangMasuk()
    {
        $daftarKaryawan = Pengguna::get();

        return view('karyawan.keuangan.create-uang-masuk', compact('daftarKaryawan'));
    }

    public function storeUangMasuk(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keperluan' => 'required|string',
            'nominal' => 'required|string',
            'jenis_uang' => 'required|in:kas,bank',
        ]);

        // Bersihkan nominal -> hapus titik/koma pemisah ribuan
        $nominal = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominalNumeric = (float)$nominal; // Konversi ke float untuk operasi matematika

        DB::beginTransaction();
        try {
            $uangMasuk = new LaporanKeuangan();
            $keuangan = Keuangan::first();

            // Cek apakah data Keuangan tersedia
            if (!$keuangan) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data Keuangan utama tidak ditemukan.')->withInput();
            }

            $uangMasuk->id_keuangan = $keuangan->id;
            $uangMasuk->id_pengguna = Auth::id();
            $uangMasuk->tanggal = $request->tanggal;
            $uangMasuk->keperluan = $request->keperluan;
            $uangMasuk->nominal = $nominalNumeric;
            $uangMasuk->penerima = $request->penerima;
            $uangMasuk->jenis = 'uang_masuk';
            $uangMasuk->jenis_uang = $request->jenis_uang;
            $uangMasuk->status_persetujuan = 'tanpa persetujuan';
            $uangMasuk->persetujuan_direktur = 0; // uang masuk tidak perlu persetujuan direktur

            // --- Logika Penambahan Saldo berdasarkan jenis_uang ---
            $jenisUang = $request->jenis_uang;

            if ($jenisUang == 'kas') {
                // Tambah sisa uang kas
                $keuangan->uang_kas += $nominalNumeric;
            } elseif ($jenisUang == 'bank') {
                // Tambah sisa uang rekening
                $keuangan->uang_rekening += $nominalNumeric;
            }

            // Tambah juga kolom 'nominal' yang merupakan total (kas + rekening)
            $keuangan->nominal += $nominalNumeric;

            // Simpan perubahan pada tabel Keuangan
            $keuangan->save();
            // --------------------------------------------------------

            $uangMasuk->save();

            DB::commit();

            return redirect()->to('/karyawan/keuangan')->with('success', 'Pemasukan kas berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pemasukan kas: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function generatePDF($id)
    {
        // Pastikan laporan sudah disetujui sebelum mencoba membuat PDF
        $laporan = LaporanKeuangan::with('pengguna', 'penerimaRelasi')->findOrFail($id);

        if ($laporan->status_persetujuan !== 'disetujui') {
            return redirect()->route('karyawan.keuangan.index')->with('error', 'Dokumen PDF hanya dapat dibuat untuk laporan yang disetujui.');
        }

        // Tambahkan package PDF di sini jika belum di-import di atas
        $pdf = app('dompdf.wrapper');

        // Load view blade untuk PDF
        $pdf->loadView('pdf.bukti_persetujuan_pdf', compact('laporan'));

        // Nama file PDF
        $fileName = 'Bukti_Pengeluaran_' . $laporan->id . '_' . Carbon::now()->format('Ymd') . '.pdf';

        // Unduh PDF
        return $pdf->download($fileName);
    }

    public function exportExcel(Request $request)
    {
        // Ambil semua parameter filter dari request (sama seperti di index/show data)
        $filters = $request->only(['filter_tanggal', 'filter_jenis', 'filter_pengguna']);

        // Tentukan nama file
        $namaFile = 'Laporan_Keuangan_' . now()->format('Ymd_His') . '.xlsx';

        // Lakukan export menggunakan kelas yang sudah dibuat
        return Excel::download(new LaporanKeuanganExport($filters), $namaFile);
    }

    /**
     * Hapus (Destroy) Laporan Keuangan.
     * Tidak mengizinkan penghapusan untuk status 'disetujui' dan 'ditolak'.
     * Logika pengembalian saldo diterapkan untuk status 'tanpa persetujuan'.
     */
    public function destroy($id)
    {
        // 1. Cari Laporan Keuangan
        $laporan = LaporanKeuangan::find($id);

        if (!$laporan) {
            return redirect()->to('/karyawan/keuangan')->with('error', 'Data laporan tidak ditemukan.');
        }

        // 2. CEK STATUS KEAMANAN
        // Blokir penghapusan untuk status yang tidak boleh dihapus: 'disetujui' dan 'ditolak'.
        if ($laporan->status_persetujuan === 'disetujui' || $laporan->status_persetujuan === 'ditolak') {
             return redirect()->to('/karyawan/keuangan')->with('error', 'Transaksi dengan status **' . ucfirst($laporan->status_persetujuan) . '** tidak dapat dihapus.');
        }

        // Cek Batas Waktu 24 Jam untuk status yang tersisa ('menunggu' dan 'tanpa persetujuan')
        $tanggalDibuat = Carbon::parse($laporan->created_at);
        $batasWaktuTerlampaui = $tanggalDibuat->lte(Carbon::now()->subDay());

        if ($batasWaktuTerlampaui) {
            // Blokir semua penghapusan jika sudah lewat 24 jam, kecuali ada kebijakan khusus.
            // Kita pertahankan batas 24 jam untuk 'menunggu' dan 'tanpa persetujuan' demi keamanan.
            return redirect()->to('/karyawan/keuangan')->with('error', 'Transaksi hanya dapat dihapus dalam waktu 1x24 jam sejak dibuat.');
        }


        DB::beginTransaction();
        try {
            $keuangan = Keuangan::first();

            if (!$keuangan) {
                DB::rollBack();
                return redirect()->to('/karyawan/keuangan')->with('error', 'Data Keuangan utama (Kas/Bank) tidak ditemukan.');
            }

            // 3. LOGIKA PENGEMBALIAN SALDO
            // Saldo HANYA perlu disesuaikan jika transaksi sebelumnya SUDAH mengurangi/menambah saldo.
            // Dalam Controller Anda: hanya status 'tanpa persetujuan' dan 'disetujui' (melalui direktur) yang memengaruhi saldo.
            // Karena 'disetujui' diblokir, kita fokus pada 'tanpa persetujuan'.

            if ($laporan->status_persetujuan === 'tanpa persetujuan') {
                $nominal = $laporan->nominal;
                $jenis_uang = $laporan->jenis_uang;
                $jenis_transaksi = $laporan->jenis; // uang_masuk, pengeluaran, kasbon

                if ($jenis_transaksi == 'pengeluaran' || $jenis_transaksi == 'kasbon') {
                    // Pengeluaran/Kasbon (keluar) perlu DITAMBAHKAN kembali
                    if ($jenis_uang == 'kas') {
                        $keuangan->uang_kas += $nominal;
                    } elseif ($jenis_uang == 'bank') {
                        $keuangan->uang_rekening += $nominal;
                    }
                    $keuangan->nominal += $nominal;

                } elseif ($jenis_transaksi == 'uang_masuk') {
                    // Uang Masuk (masuk) perlu DIKURANGI kembali
                    if ($jenis_uang == 'kas') {
                        $keuangan->uang_kas -= $nominal;
                    } elseif ($jenis_uang == 'bank') {
                        $keuangan->uang_rekening -= $nominal;
                    }
                    $keuangan->nominal -= $nominal;
                }

                // Simpan perubahan pada tabel Keuangan
                $keuangan->save();
            }
            // Catatan: Untuk status 'menunggu', saldo tidak berubah, jadi tidak ada yang perlu dikembalikan.


            // 4. Hapus Lampiran (jika ada)
            if ($laporan->lampiran) {
                // Pastikan Anda menggunakan Storage::delete() jika file di-upload via Storage facade,
                // atau unlink() jika via public_path/move(). Saya asumsikan unlink sesuai storePengeluaran.
                $path = public_path('uploads/lampiran/' . $laporan->lampiran);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // 5. Hapus Laporan Keuangan dari database
            $laporan->delete();

            DB::commit();

            return redirect()->to('/karyawan/keuangan')->with('success', 'Transaksi keuangan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus transaksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus transaksi. Terjadi kesalahan sistem.');
        }
    }
}
