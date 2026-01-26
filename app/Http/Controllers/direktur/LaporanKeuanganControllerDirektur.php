<?php

namespace App\Http\Controllers\Direktur;

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


class LaporanKeuanganControllerDirektur extends Controller
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

        $laporanKeuangan = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();


        $uangKas = Keuangan::first();
        $uangKeluar = LaporanKeuangan::where('jenis', 'pengeluaran')->sum('nominal');
        $uangMasuk = LaporanKeuangan::where('jenis', 'uang_masuk')->sum('nominal');
        $daftarKaryawan = Pengguna::all();

        return view('direktur.laporan_keuangan.index', compact('laporanKeuangan', 'uangKeluar', 'uangKas', 'daftarKaryawan', 'uangMasuk'));
    }

    public function persetujuan(Request $request, $id)
    {
        $laporan = LaporanKeuangan::with('pengguna', 'penerimaRelasi')->findOrFail($id);
        // Ubah status persetujuan_direktur menjadi true
        return view('direktur.laporan_keuangan.persetujuan', compact('laporan'));
    }

    public function updatePersetujuan(Request $request, $id)
    {
        $laporan = LaporanKeuangan::findOrFail($id);

        // Hanya proses jika persetujuan dikirim (untuk keamanan)
        if (!in_array($request->persetujuan, ['disetujui', 'ditolak'])) {
             return back()->with('error', 'Pilihan persetujuan tidak valid.');
        }

        // Simpan status sebelum update
        $isApproved = ($request->persetujuan === 'disetujui');

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Update status persetujuan_direktur
            if ($isApproved) {
                $laporan->status_persetujuan = 'disetujui';

                // Jika disetujui, update uang kas (pengurangan)
                $keuangan = Keuangan::first();
                if (!$keuangan || $keuangan->nominal < $laporan->nominal) {
                    // Jika saldo tidak mencukupi, rollback dan kembalikan error
                    DB::rollBack();
                    return back()->with('error', 'Saldo kas tidak mencukupi untuk pengeluaran ini.');
                }
                $keuangan->nominal -= $laporan->nominal;
                $keuangan->save();
            } else {
                $laporan->status_persetujuan = 'ditolak';
            }

            $laporan->catatan = $request->catatan;
            $laporan->save();

            // Commit transaksi
            DB::commit();

            // Redirect ke route generate PDF jika disetujui
            if ($isApproved) {
                return redirect()->route('direktur.keuangan-laporan.generate-pdf', ['id' => $laporan->id])
                                 ->with('success', 'Laporan berhasil disetujui. Dokumen bukti pengeluaran sedang dibuat.');
            }

            // Redirect normal jika ditolak
            return redirect()->route('direktur.keuangan-laporan.index')
                             ->with('success', 'Persetujuan laporan keuangan berhasil diperbarui (Ditolak).');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            Log::error('Error updating laporan keuangan approval: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui persetujuan laporan keuangan.');
        }
    }

    public function generatePDF($id)
    {
        // Pastikan laporan sudah disetujui sebelum mencoba membuat PDF
        $laporan = LaporanKeuangan::with('pengguna', 'penerimaRelasi')->findOrFail($id);

        if ($laporan->status_persetujuan !== 'disetujui') {
            return redirect()->route('direktur.keuangan-laporan.index')->with('error', 'Dokumen PDF hanya dapat dibuat untuk laporan yang disetujui.');
        }

        // Tambahkan package PDF di sini jika belum di-import di atas
        $pdf = app('dompdf.wrapper');

        // Load view blade untuk PDF
        $pdf->loadView('direktur.laporan_keuangan.bukti_persetujuan_pdf', compact('laporan'));

        // Nama file PDF
        $fileName = 'Bukti_Pengeluaran_' . $laporan->id . '_' . Carbon::now()->format('Ymd') . '.pdf';

        // Unduh PDF
        return $pdf->download($fileName);
    }
}
