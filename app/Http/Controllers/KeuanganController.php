<?php

namespace App\Http\Controllers;

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
use Barryvdh\DomPDF\Facade\Pdf;

class KeuanganController extends Controller
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
       $uangKeluar = LaporanKeuangan::whereIn('jenis', ['pengeluaran', 'kasbon'])
    ->sum('nominal');
        $uangMasuk = LaporanKeuangan::where('jenis', 'uang_masuk')->sum('nominal');
        $daftarKaryawan = Pengguna::all();

        return view('keuangan.index', compact('laporanKeuangan', 'uangKeluar', 'uangKas', 'daftarKaryawan', 'uangMasuk'));
    }

    public function previewPdfView($id)
    {
        // 1. Ambil data laporan
        $laporan = LaporanKeuangan::findOrFail($id);

        // nama direktur
        $direktur = Pengguna::where('role', 'direktur')->first()->nama ?? 'Direktur';

        // 2. Siapkan variabel yang dibutuhkan view (sesuai template)
        $data = [
            'laporan' => $laporan,
            // $direktur adalah variabel yang mungkin digunakan di view
            'direktur' => $direktur,
            // $tanggal_persetujuan juga variabel yang dibutuhkan view
            'tanggal_persetujuan' => now()->format('Y-m-d H:i:s'),

        ];

        // 3. Tampilkan view-nya secara langsung
        return view('pdf.bukti_persetujuan_pdf', $data);
    }


    public function createPengeluaran()
    {

        $daftarKaryawan = Pengguna::get();

        return view('keuangan.create-pengeluaran', compact('daftarKaryawan'));
    }

    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keperluan' => 'required|string',
            'nominal' => 'required|string',
            'jenis_uang' => 'required|in:kas,bank',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'persetujuan_direktur' => 'required',
        ]);

        $nominal = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominalNumeric = (float) $nominal;

        DB::beginTransaction();
        try {
            $keuangan = Keuangan::first();
            if (!$keuangan) {
                DB::rollBack();
                return back()->with('error', 'Data Keuangan utama tidak ditemukan.');
            }

            $pengeluaran = new LaporanKeuangan();
            $pengeluaran->id_keuangan = $keuangan->id;
            $pengeluaran->id_pengguna = Auth::id();
            $pengeluaran->tanggal = $request->tanggal;
            $pengeluaran->keperluan = $request->keperluan;
            $pengeluaran->nominal = $nominalNumeric;
            $pengeluaran->penerima = $request->penerima;
            $pengeluaran->jenis = 'pengeluaran';
            $pengeluaran->jenis_uang = $request->jenis_uang;
            $pengeluaran->persetujuan_direktur = $request->persetujuan_direktur;

            if ($request->persetujuan_direktur == 1) {
                $pengeluaran->status_persetujuan = 'menunggu';
            } else {
                $pengeluaran->status_persetujuan = 'tanpa persetujuan';

                if ($request->jenis_uang == 'kas') {
                    if ($keuangan->uang_kas < $nominalNumeric) {
                        DB::rollBack();
                        return back()->with('error', 'Saldo Kas tidak mencukupi!');
                    }
                    $keuangan->uang_kas -= $nominalNumeric;
                } else {
                    if ($keuangan->uang_rekening < $nominalNumeric) {
                        DB::rollBack();
                        return back()->with('error', 'Saldo Bank tidak mencukupi!');
                    }
                    $keuangan->uang_rekening -= $nominalNumeric;
                }

                $keuangan->nominal -= $nominalNumeric;
                $keuangan->save();
            }
            $this->sendWhatsAppToDirektur($pengeluaran);

            // ===== SIMPAN LAMPIRAN KE STORAGE (SEPERTI SURAT KELUAR) =====
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $storageFolder = 'lampiran_keuangan';
                $dok_lampiran = "{$storageFolder}/{$filename}";

                Storage::disk('public')->put(
                    $dok_lampiran,
                    file_get_contents($file)
                );

                // Simpan NAMA FILE ke database
                $pengeluaran->lampiran = $filename;
            }


            $pengeluaran->save();
            DB::commit();

            return redirect()->to('keuangan')->with('success', 'Pengeluaran kas berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan.');
        }
    }



    private function sendWhatsAppToDirektur($pengeluaran)
    {
        // Salam otomatis
        $hour = now()->format('H');
        if ($hour >= 5 && $hour < 12) {
            $salam = 'Selamat Pagi';
        } elseif ($hour >= 12 && $hour < 17) {
            $salam = 'Selamat Siang';
        } elseif ($hour >= 17 && $hour < 20) {
            $salam = 'Selamat Sore';
        } else {
            $salam = 'Selamat Malam';
        }

        // Ambil direktur
        $direktur = \App\Models\Pengguna::where('role', 'direktur')->first();
        if (!$direktur || !$direktur->no_hp) {
            Log::error('Nomor WA direktur tidak ditemukan');
            return;
        }

        // === PESAN BERDASARKAN JENIS PERSETUJUAN ===
        if ($pengeluaran->persetujuan_direktur == 1) {
            // 🔔 PERLU PERSETUJUAN
            $message = "👋 *{$salam}, {$direktur->nama}!* \n\n"
                . "Terdapat *pengajuan pengeluaran* yang *memerlukan persetujuan Anda*.\n\n"
                . "📝 *Keperluan:* {$pengeluaran->keperluan}\n"
                . "💰 *Nominal:* Rp " . number_format($pengeluaran->nominal, 0, ',', '.') . "\n"
                . "📅 *Tanggal:* {$pengeluaran->tanggal}\n"
                . "👤 *Diajukan oleh:* " . Auth::user()->nama . "\n\n"
                . "Silakan buka aplikasi untuk melakukan *persetujuan*.\n\n"
                . "_Notifikasi otomatis dari Sistem Baratala_";
        } else {
            // ✅ TIDAK PERLU PERSETUJUAN
            $message = "ℹ️ *{$salam}, {$direktur->nama}!* \n\n"
                . "Terdapat *pengeluaran dana* yang diproses tanpa memerlukan persetujuan direktur.\n\n"
                . "📝 *Keperluan:* {$pengeluaran->keperluan}\n"
                . "💰 *Nominal:* Rp " . number_format($pengeluaran->nominal, 0, ',', '.') . "\n"
                . "📅 *Tanggal:* {$pengeluaran->tanggal}\n"
                . "👤 *Diproses oleh:* " . Auth::user()->nama . "\n\n"
                . "_Notifikasi otomatis dari Sistem Baratala_";
        }

        \App\Helpers\WhatsAppHelper::send($direktur->no_hp, $message);
    }


    public function createKasbon()
    {
        $daftarKaryawan = Pengguna::get();

        return view('keuangan.create-kasbon', compact('daftarKaryawan'));
    }

    public function storeKasbon(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keperluan' => 'required|string',
            'nominal' => 'required|string',
            'jenis_uang' => 'required|in:kas,bank',
            'status_persetujuan' => 'required',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $nominal = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominalNumeric = (float) $nominal;

        DB::beginTransaction();
        try {
            $keuangan = Keuangan::first();
            if (!$keuangan) {
                DB::rollBack();
                return back()->with('error', 'Data Keuangan utama tidak ditemukan.');
            }

            $kasbon = new LaporanKeuangan();
            $kasbon->id_keuangan = $keuangan->id;
            $kasbon->id_pengguna = Auth::id();
            $kasbon->tanggal = $request->tanggal;
            $kasbon->keperluan = $request->keperluan;
            $kasbon->nominal = $nominalNumeric;
            $kasbon->penerima = $request->penerima;
            $kasbon->jenis = 'kasbon';
            $kasbon->jenis_uang = $request->jenis_uang;
            $kasbon->persetujuan_direktur = $request->status_persetujuan;

            // ===== LAMPIRAN =====
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $storageFolder = 'lampiran_keuangan';
                Storage::disk('public')->put(
                    $storageFolder . '/' . $filename,
                    file_get_contents($file)
                );

                $kasbon->lampiran = $filename;
            }

            // ===== LOGIKA PERSETUJUAN =====
            if ($request->status_persetujuan == 1) {
                // PERLU PERSETUJUAN
                $kasbon->status_persetujuan = 'menunggu';

                $kasbon->save();
                $this->sendWhatsAppKasbonPerluPersetujuan($kasbon);
            } else {
                // TANPA PERSETUJUAN
                $kasbon->status_persetujuan = 'tanpa persetujuan';

                if ($request->jenis_uang == 'kas') {
                    if ($keuangan->uang_kas < $nominalNumeric) {
                        DB::rollBack();
                        return back()->with('error', 'Saldo Kas tidak mencukupi!');
                    }
                    $keuangan->uang_kas -= $nominalNumeric;
                } else {
                    if ($keuangan->uang_rekening < $nominalNumeric) {
                        DB::rollBack();
                        return back()->with('error', 'Saldo Bank tidak mencukupi!');
                    }
                    $keuangan->uang_rekening -= $nominalNumeric;
                }

                $keuangan->nominal -= $nominalNumeric;
                $keuangan->save();

                $kasbon->save();
                $this->sendWhatsAppKasbonTanpaPersetujuan($kasbon);
            }

            DB::commit();
            return redirect()->to('keuangan')->with('success', 'Kasbon berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan.');
        }
    }


    private function sendWhatsAppKasbonPerluPersetujuan($kasbon)
    {
        $salam = $this->getSalam();
        $direktur = \App\Models\Pengguna::where('role', 'direktur')->first();

        if (!$direktur || !$direktur->no_hp) return;

        $message = "👋 *{$salam}, {$direktur->nama}!* \n\n"
            . "Terdapat *pengajuan kasbon* yang memerlukan persetujuan Anda.\n\n"
            . "📝 *Keperluan:* {$kasbon->keperluan}\n"
            . "💰 *Nominal:* Rp " . number_format($kasbon->nominal, 0, ',', '.') . "\n"
            . "📅 *Tanggal:* {$kasbon->tanggal}\n"
            . "👤 *Diajukan oleh:* " . Auth::user()->nama . "\n\n"
            . "Silakan membuka aplikasi untuk melakukan persetujuan.\n\n"
            . "_Notifikasi otomatis dari Sistem Baratala_";

        \App\Helpers\WhatsAppHelper::send($direktur->no_hp, $message);
    }

    private function sendWhatsAppKasbonTanpaPersetujuan($kasbon)
    {
        $salam = $this->getSalam();
        $direktur = \App\Models\Pengguna::where('role', 'direktur')->first();

        if (!$direktur || !$direktur->no_hp) return;

        $message = "ℹ️ *{$salam}, {$direktur->nama}!* \n\n"
            . "Terdapat *kasbon* yang diproses tanpa memerlukan persetujuan direktur.\n\n"
            . "📝 *Keperluan:* {$kasbon->keperluan}\n"
            . "💰 *Nominal:* Rp " . number_format($kasbon->nominal, 0, ',', '.') . "\n"
            . "📅 *Tanggal:* {$kasbon->tanggal}\n"
            . "👤 *Diproses oleh:* " . Auth::user()->nama . "\n\n"
            . "_Notifikasi otomatis dari Sistem Baratala_";

        \App\Helpers\WhatsAppHelper::send($direktur->no_hp, $message);
    }

    /*******  e91f10c9-b427-4149-92b2-46c975aef53f  *******/
    private function getSalam()
    {
        $hour = now()->format('H');

        return match (true) {
            $hour >= 5 && $hour < 12 => 'Selamat Pagi',
            $hour >= 12 && $hour < 17 => 'Selamat Siang',
            $hour >= 17 && $hour < 20 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
    }



    public function createUangMasuk()
    {
        $daftarKaryawan = Pengguna::get();

        return view('keuangan.create-uang-masuk', compact('daftarKaryawan'));
    }

    public function storeUangMasuk(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keperluan' => 'required|string',
            'nominal' => 'required|string',
            'jenis_uang' => 'required|in:kas,bank',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $nominal = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominalNumeric = (float) $nominal;

        DB::beginTransaction();
        try {
            $uangMasuk = new LaporanKeuangan();
            $keuangan = Keuangan::first();

            if (!$keuangan) {
                DB::rollBack();
                return back()->with('error', 'Data Keuangan utama tidak ditemukan.');
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
            $uangMasuk->persetujuan_direktur = 0;

            if ($request->jenis_uang === 'kas') {
                $keuangan->uang_kas += $nominalNumeric;
            } else {
                $keuangan->uang_rekening += $nominalNumeric;
            }

            $keuangan->nominal += $nominalNumeric;
            $keuangan->save();



            // ===== LAMPIRAN =====
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $storageFolder = 'lampiran_keuangan';
                $dok_lampiran = "{$storageFolder}/{$filename}";

                Storage::disk('public')->put(
                    $dok_lampiran,
                    file_get_contents($file)
                );

                $uangMasuk->lampiran = $filename;
            }

            $uangMasuk->save();

            // === KIRIM WA INFO UANG MASUK ===
            $this->sendWhatsAppUangMasuk($uangMasuk);
            DB::commit();

            return redirect()->to('keuangan')->with('success', 'Pemasukan kas berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan.');
        }
    }

    private function sendWhatsAppUangMasuk($uangMasuk)
    {
        $salam = $this->getSalam();

        $direktur = \App\Models\Pengguna::where('role', 'direktur')->first();
        if (!$direktur || !$direktur->no_hp) return;

        $message = "ℹ️ *{$salam}, {$direktur->nama}!* \n\n"
            . "Terdapat *pemasukan dana* yang telah dicatat ke sistem.\n\n"
            . "📝 *Keperluan:* {$uangMasuk->keperluan}\n"
            . "💰 *Nominal:* Rp " . number_format($uangMasuk->nominal, 0, ',', '.') . "\n"
            . "🏦 *Sumber:* " . strtoupper($uangMasuk->jenis_uang) . "\n"
            . "📅 *Tanggal:* {$uangMasuk->tanggal}\n"
            . "👤 *Dicatat oleh:* " . Auth::user()->nama . "\n\n"
            . "_Notifikasi otomatis dari Sistem Baratala_";

        \App\Helpers\WhatsAppHelper::send($direktur->no_hp, $message);
    }


    // public function generatePDF($id)
    // {
    //     // Pastikan laporan sudah disetujui sebelum mencoba membuat PDF
    //     $laporan = LaporanKeuangan::with('pengguna', 'penerimaRelasi')->findOrFail($id);

    //     if ($laporan->status_persetujuan !== 'disetujui') {
    //         return redirect()->route('keuangan.index')->with('error', 'Dokumen PDF hanya dapat dibuat untuk laporan yang disetujui.');
    //     }

    //     // Tambahkan package PDF di sini jika belum di-import di atas
    //     $pdf = app('dompdf.wrapper');

    //     // Load view blade untuk PDF
    //     $pdf->loadView('pdf.bukti_persetujuan_pdf', compact('laporan'));

    //     // Nama file PDF
    //     $fileName = 'Bukti_Pengeluaran_' . $laporan->id . '_' . Carbon::now()->format('Ymd') . '.pdf';

    //     // Unduh PDF
    //     return $pdf->download($fileName);
    // }

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
            return redirect()->to('keuangan')->with('error', 'Data laporan tidak ditemukan.');
        }

        // 2. CEK STATUS KEAMANAN
        // Blokir penghapusan untuk status yang tidak boleh dihapus: 'disetujui' dan 'ditolak'.
        if ($laporan->status_persetujuan === 'disetujui' || $laporan->status_persetujuan === 'ditolak') {
            return redirect()->to('keuangan')->with('error', 'Transaksi dengan status **' . ucfirst($laporan->status_persetujuan) . '** tidak dapat dihapus.');
        }

        // Cek Batas Waktu 24 Jam untuk status yang tersisa ('menunggu' dan 'tanpa persetujuan')
        $tanggalDibuat = Carbon::parse($laporan->created_at);
        $batasWaktuTerlampaui = $tanggalDibuat->lte(Carbon::now()->subDay());

        if ($batasWaktuTerlampaui) {
            // Blokir semua penghapusan jika sudah lewat 24 jam, kecuali ada kebijakan khusus.
            // Kita pertahankan batas 24 jam untuk 'menunggu' dan 'tanpa persetujuan' demi keamanan.
            return redirect()->to('keuangan')->with('error', 'Transaksi hanya dapat dihapus dalam waktu 1x24 jam sejak dibuat.');
        }


        DB::beginTransaction();
        try {
            $keuangan = Keuangan::first();

            if (!$keuangan) {
                DB::rollBack();
                return redirect()->to('keuangan')->with('error', 'Data Keuangan utama (Kas/Bank) tidak ditemukan.');
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

            return redirect()->to('keuangan')->with('success', 'Transaksi keuangan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus transaksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus transaksi. Terjadi kesalahan sistem.');
        }
    }

    public function persetujuan(Request $request, $id)
    {
        $laporan = LaporanKeuangan::with('pengguna', 'penerimaRelasi')->findOrFail($id);
        // Ubah status persetujuan_direktur menjadi true
        return view('keuangan.persetujuan', compact('laporan'));
    }

    public function updatePersetujuan(Request $request, $id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);

        // Hanya proses jika persetujuan dikirim (untuk keamanan)
        if (!in_array($request->persetujuan, ['disetujui', 'ditolak'])) {
            return back()->with('error', 'Pilihan persetujuan tidak valid.');
        }

        $isApproved = ($request->persetujuan === 'disetujui');
        $pdfPath = null; // Inisialisasi variabel untuk path PDF

        DB::beginTransaction();

        try {
            if ($isApproved) {
                $laporan->status_persetujuan = 'disetujui';

                // Ambil data keuangan utama
                $keuangan = Keuangan::first();
                if (!$keuangan) {
                    DB::rollBack();
                    return back()->with('error', 'Data Keuangan utama tidak ditemukan.');
                }

                $nominal = (float) $laporan->nominal;
                $jenisUang = $laporan->jenis_uang;
                $jenisTransaksi = $laporan->jenis;

                // Logika Pembaruan Saldo (Kode Anda yang sudah benar)
                if (in_array($jenisTransaksi, ['pengeluaran', 'kasbon'])) {
                    if ($jenisUang === 'kas') {
                        if ($keuangan->uang_kas < $nominal) {
                            DB::rollBack();
                            return back()->with('error', 'Saldo Kas tidak mencukupi untuk pengeluaran ini.');
                        }
                        $keuangan->uang_kas -= $nominal;
                    } elseif ($jenisUang === 'bank') {
                        if ($keuangan->uang_rekening < $nominal) {
                            DB::rollBack();
                            return back()->with('error', 'Saldo Bank tidak mencukupi untuk pengeluaran ini.');
                        }
                        $keuangan->uang_rekening -= $nominal;
                    }
                    $keuangan->nominal -= $nominal;
                } elseif ($jenisTransaksi === 'uang_masuk') {
                    if ($jenisUang === 'kas') {
                        $keuangan->uang_kas += $nominal;
                    } elseif ($jenisUang === 'bank') {
                        $keuangan->uang_rekening += $nominal;
                    }
                    $keuangan->nominal += $nominal;
                }

                // Simpan perubahan keuangan
                $keuangan->save();

                // PANGGIL FUNGSI GENERATE PDF DAN SIMPAN PATH
                // Pastikan ini dipanggil SETELAH $laporan->status_persetujuan diatur
                $pdfPath = $this->generatePDF($laporan->id);
            } else {
                $laporan->status_persetujuan = 'ditolak';
                // Jika ditolak, pastikan path PDF dihapus/null
                $laporan->bukti_persetujuan_pdf = null;
            }


            $laporan->catatan = $request->catatan;
            // Simpan path PDF yang dikembalikan (jika ada)
            if ($pdfPath) {
                $laporan->bukti_persetujuan_pdf = $pdfPath;
            }

            $laporan->save(); // Simpan status persetujuan, catatan, dan path PDF

            DB::commit();

            $message = $isApproved ? 'Laporan berhasil disetujui dan bukti PDF telah disimpan.' : 'Persetujuan laporan keuangan berhasil diperbarui (Ditolak).';

            // Hanya redirect ke index, tidak perlu ke route generate-pdf lagi
            return redirect()->route('keuangan.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating laporan keuangan approval: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui persetujuan laporan keuangan.');
        }
    }

    // protected function generateAndStorePdf(LaporanKeuangan $laporan)
    // {
    //     if ($laporan->persetujuan_direktur != 1) {
    //         return null;
    //     }

    //     $data = [
    //         'laporan' => $laporan,
    //         'direktur' => Auth::user()->nama,
    //         'tanggal_persetujuan' => now()->format('d M Y H:i:s'),
    //     ];

    //     $pdf = Pdf::loadView('pdf.bukti_persetujuan_pdf', $data);

    //     $fileName = 'persetujuan_' . $laporan->id . '_' . time() . '.pdf';
    //     $filePath = 'bukti_persetujuan/' . $fileName;

    //     // Simpan file PDF ke storage/app/public/bukti_persetujuan/
    //     Storage::disk('public')->put($filePath, $pdf->output());

    //     return $filePath; // simpan path ini ke database
    // }



    private function generatePDF($id)
    {
        $laporan = LaporanKeuangan::with('pengguna', 'penerimaRelasi')->findOrFail($id);

        // ===== LOGO =====
        $logoBase64 = null;
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // ===== TTD DIREKTUR =====
        $ttdDirBase64 = null;
        $ttdPath = public_path('images/ttd_direktur.png'); // sesuaikan
        if (file_exists($ttdPath)) {
            $ttdDirBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath));
        }

        $pdf = Pdf::loadView('pdf.bukti_persetujuan_pdf', [
            'laporan' => $laporan,
            'tanggal_persetujuan' => now()->format('Y-m-d H:i:s'),
            'direktur' => Pengguna::where('role', 'direktur')->first()->nama ?? 'Direktur',
            'logoBase64' => $logoBase64,
            'ttdDirBase64' => $ttdDirBase64, // ✅ FIX
        ]);

        $fileName = 'bukti_persetujuan_' . $laporan->id . '_' . time() . '.pdf';
        $path = 'bukti_persetujuan_pdf/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());
        return $path;
    }



    /**
     * Fungsi helper untuk konversi gambar ke Base64
     */
    private function getBase64Image($path)
    {
        if (!file_exists($path)) {
            return ''; // Optional: bisa kasih placeholder
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
