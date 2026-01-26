<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Helpers\WhatsAppHelper;
use App\Models\Pengguna;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratKeluarController extends Controller
{
    /**
     * Tampilkan daftar semua Surat Keluar.
     */
    public function index(Request $request)
    {
        // 1. Mulai query builder
        $query = SuratKeluar::with('pengguna')->orderBy('nomor_surat', 'desc');

        // Filter
        if ($request->filled('nomor')) {
            $query->where('nomor_surat', 'like', '%' . $request->nomor . '%');
        }
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('perihal', 'like', '%' . $cari . '%')
                    ->orWhere('tujuan', 'like', '%' . $cari . '%');
            });
        }
        if ($request->filled('jenis')) {
            $query->where('jenis_surat', strtolower($request->jenis));
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tgl_surat', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tgl_surat', '<=', $request->tanggal_selesai);
        }

        // Ambil data collection
        $allData = $query->get();
        $totalData = $allData->count(); // Hitung total data sesuai filter

        // Tentukan jumlah per halaman (misal 10)
        $perPage = 20;

        // Slice data sesuai halaman sekarang
        $page = $request->get('page', 1);
        $items = $allData->slice(($page - 1) * $perPage, $perPage)->values();

        // Buat paginator manual agar jumlah halaman sesuai data
        $surat_keluar = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $totalData,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('surat_keluar.index', compact('surat_keluar'));
    }

    public function create(Request $request)
    {
        $jenis_surat_options = ['umum', 'keuangan', 'operasional'];
        $jenis = $request->jenis ?? 'umum';

        // ===============================
        // GENERATE NOMOR SURAT PER JENIS
        // FORMAT: BTTP-1.058/Ops/XII/2025
        // ===============================

        // Ambil surat terakhir BERDASARKAN JENIS
        $lastSurat = SuratKeluar::where('jenis_surat', $jenis)
            ->orderBy('nomor_surat', 'desc')
            ->first();

        $lastNumber = 0;

        if ($lastSurat && preg_match('/BTTP-([\d\.]+)\//', $lastSurat->nomor_surat, $matches)) {
            // Contoh: 1.058 → 1058
            $lastNumber = (int) str_replace('.', '', $matches[1]);
        }

        // Tambah 1
        $newNumber = $lastNumber + 1;

        // Format ribuan: 1059 → 1.059
        $formattedNumber = number_format($newNumber, 0, '', '.');

        // Kode jenis surat
        $jenisKode = [
            'umum'        => 'Um',
            'operasional' => 'Ops',
            'keuangan'    => 'Keu',
        ];

        // Bulan Romawi
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        $bulan = $bulanRomawi[Carbon::now()->month];
        $tahun = Carbon::now()->year;

        // ✅ NOMOR FINAL SESUAI JENIS MASING-MASING
        $nomor_surat = "BTTP-{$formattedNumber}/{$jenisKode[$jenis]}/{$bulan}/{$tahun}";

        return view('surat_keluar.create', compact(
            'jenis_surat_options',
            'jenis',
            'nomor_surat'
        ));
    }





    /**
     * Simpan Surat Keluar baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_surat'   => 'required|date',
            'nomor_surat' => 'required|string|max:100|unique:surat_keluar,nomor_surat',
            'tujuan'      => 'required|string|max:255',
            'jenis_surat' => ['required', Rule::in(['umum', 'keuangan', 'operasional'])],
            'metode'      => 'required|in:editor,upload',

            'konten_surat' => 'required_if:metode,editor|nullable|string',
            // File akan disimpan di storage/app/public/surat_keluar_upload
            'file_surat'   => 'required_if:metode,upload|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $noWaDirektur = Pengguna::where('role', 'direktur')->first()->no_hp;

        try {
            $dok_surat = null;
            $lampiran = null;
            $kontenSurat = $request->konten_surat;

            // Pastikan nomor surat aman untuk nama file
            $safeNomor = str_replace(['/', '\\', ' '], '-', $request->nomor_surat);

            // Tentukan folder penyimpanan di dalam storage/app/public
            $storageFolder = 'surat_keluar_dokumen';

            // ============================
            // 📤 UPLOAD FILE MANUAL
            // ============================
            if ($request->hasFile('file_surat')) {

                $file = $request->file('file_surat');
                $filename = time() . '-' . $safeNomor . '.' . $file->getClientOriginalExtension();

                // ✅ Menggunakan Storage::disk('public')->storeAs()
                // File akan tersimpan di: storage/app/public/surat_keluar_dokumen/filename
                $dok_surat = $file->storeAs(
                    $storageFolder,
                    $filename,
                    'public'
                );
                $kontenSurat = ''; // Jika upload file, konten surat diabaikan

                Log::info('FILE UPLOAD BERHASIL', ['path' => $dok_surat]);
            }
            // ============================
            // 📄 GENERATE PDF DARI EDITOR
            // ============================
            else {
                $logoPath = public_path('image/logo.png');

                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    $kontenSurat = str_replace(
                        '../image/logo.png', // Ganti path lama (relatif)
                        'data:image/png;base64,' . $logoData,
                        $kontenSurat
                    );
                }

                $pdf = Pdf::loadHtml($kontenSurat)->setPaper('A4', 'portrait');

                $filename = 'SK-' . date('Ymd') . '-' . $safeNomor . '.pdf';

                // ✅ Menggunakan Storage::disk('public')->put()
                // File akan tersimpan di: storage/app/public/surat_keluar_dokumen/filename
                $dok_surat = "{$storageFolder}/{$filename}";


                Storage::disk('public')->put($dok_surat, $pdf->output());

                Log::info('PDF GENERATED', ['path' => $dok_surat]);
            }


            SuratKeluar::create([
                'id_pengguna'  => Auth::id(),
                'nomor_surat'  => $request->nomor_surat,
                'konten_surat' => $kontenSurat,
                'tgl_surat'    => $request->tgl_surat,
                'tujuan'       => $request->tujuan,
                'perihal'      => $request->perihal,
                'jenis_surat'  => $request->jenis_surat,
                // dok_surat menyimpan path relatif (ex: surat_keluar_dokumen/filename.pdf)
                'dok_surat'    => $dok_surat,
                'lampiran'     => $lampiran,
            ]);

            // ... (Notifikasi WA)
            WhatsAppHelper::sendSuratKeluarNotification(
                $noWaDirektur,
                $request,
                Auth::user()->nama ?? 'Pengguna'
            );

            return redirect()->route('surat-keluar.index')
                ->with('success', 'Surat berhasil disimpan');
        } catch (\Exception $e) {
            // ... (Penanganan Error)
            Log::error('STORE SURAT ERROR', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Gagal menyimpan surat']);
        }
    }


    /**
     * Detail surat keluar.
     */
    public function show(SuratKeluar $surat_keluar)
    {
        return view('surat_keluar.show', compact('surat_keluar'));
    }

    /**
     * Form edit Surat Keluar.
     */
    public function edit($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        return view('surat_keluar.edit', [
            'surat' => $surat,
            'nomor_surat' => $surat->nomor_surat,
            'jenis' => $surat->jenis_surat,
        ]);
    }


    /**
     * Update Surat Keluar.
     */
    public function update(Request $request, $id)
    {
        // Ambil data surat lama
        $surat = SuratKeluar::findOrFail($id);

        // VALIDASI
        $request->validate([
            'tgl_surat' => 'required|date',
            'nomor_surat' => [
                'required',
                'string',
                'max:100',
                Rule::unique('surat_keluar', 'nomor_surat')->ignore($id)
            ],
            'tujuan' => 'required|string|max:255',
            'konten_surat' => 'required|string',
            'jenis_surat' => ['required', Rule::in(['umum', 'keuangan', 'operasional'])],
            'lampiran' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $lampiran = $surat->lampiran; // default: pakai lampiran lama
            $dok_surat = $surat->dok_surat; // default: pakai PDF lama
            $htmlContent = $request->konten_surat;

            /* ================================
         * 1. Upload Lampiran (Jika Ada)
         * ================================ */
            if ($request->hasFile('lampiran')) {

                // Hapus lampiran lama jika ada
                if ($lampiran && Storage::disk('public')->exists($lampiran)) {
                    Storage::disk('public')->delete($lampiran);
                }

                $lampiran = $request->file('lampiran')->store('surat_keluar_lampiran', 'public');
            }

            /* ================================
         * 2. Replace Logo Menjadi Base64
         * ================================ */
            $logoPath = public_path('image/logo.png');
            Log::info('PDF Update: Checking logo path', [
                'path' => $logoPath,
                'exists' => file_exists($logoPath)
            ]);

            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                $base64Image = "data:image/png;base64,$logoData";

                $assetUrlToFind = '../image/logo.png';

                if (strpos($htmlContent, $assetUrlToFind) !== false) {
                    $htmlContent = str_replace($assetUrlToFind, $base64Image, $htmlContent);
                    Log::info('PDF Update: Logo replaced successfully');
                } else {
                    Log::warning('PDF Update: Logo URL NOT FOUND in HTML content');
                }
            } else {
                Log::error('PDF Update: Logo NOT FOUND on server');
            }

            /* ================================
         * 3. Generate PDF Baru
         * ================================ */

            // Hapus PDF lama jika ada
            if ($dok_surat && Storage::disk('public')->exists($dok_surat)) {
                Storage::disk('public')->delete($dok_surat);
            }

            $pdf = PDF::loadHtml($htmlContent)->setPaper('A4', 'portrait');

            $safeNomorSurat = str_replace(['/', '\\', ' '], '-', $request->nomor_surat);
            $tanggal = date('Ymd');
            $fileName = "SK-{$tanggal}-{$safeNomorSurat}.pdf";

            // Simpan PDF baru
            $dok_surat = 'surat_keluar_pdf/' . $fileName;
            Storage::disk('public')->put($dok_surat, $pdf->output());

            /* ================================
         * 4. Update Database
         * ================================ */

            $surat->update([
                'id_pengguna' => Auth::id(),
                'nomor_surat' => $request->nomor_surat,
                'konten_surat' => $htmlContent,
                'tgl_surat' => $request->tgl_surat,
                'tujuan' => $request->tujuan,
                'perihal' => $request->perihal,
                'lampiran' => $lampiran,
                'jenis_surat' => $request->jenis_surat,
                'dok_surat' => $dok_surat,
            ]);

            return redirect()->route('surat-keluar.index')
                ->with('success', 'Surat Keluar berhasil diperbarui dan PDF baru berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('PDF Update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->withErrors([
                'error' => 'Gagal memperbarui data atau membuat PDF baru. Silakan cek log.'
            ]);
        }
    }

    public function destroy($id_surat)
    {
        $surat_keluar = SuratKeluar::findOrFail($id_surat);

        try {
            // Hapus lampiran jika ada
            if ($surat_keluar->lampiran && Storage::disk('public')->exists($surat_keluar->lampiran)) {
                Storage::disk('public')->delete($surat_keluar->lampiran);
            }

            // Hapus dokumen PDF jika ada
            if ($surat_keluar->dok_surat && Storage::disk('public')->exists($surat_keluar->dok_surat)) {
                Storage::disk('public')->delete($surat_keluar->dok_surat);
            }

            // Hapus data dari database
            $surat_keluar->delete();

            return redirect()->route('surat-keluar.index')
                ->with('success', 'Surat Keluar beserta lampiran dan PDF berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus data. ' . $e->getMessage()]);
        }
    }


    public function updateDokumen(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'surat_id' => 'required|exists:surat_keluar,id',
            // Menggunakan nama input file 'dok_surat'
            'dok_surat' => 'required|file|mimes:pdf|max:5120', // Maks 5MB
        ]);

        // 2. Cari Surat
        $surat = SuratKeluar::findOrFail($request->surat_id);

        // 3. Proses File Upload
        if ($request->hasFile('dok_surat')) {
            $file = $request->file('dok_surat');

            // Tentukan folder penyimpanan. Sesuaikan nama folder jika Anda menggunakan nama yang berbeda!
            $storageDisk = 'public';
            $pathFolder = 'surat_keluar_pdf';

            // 4. Hapus file lama (JIKA ADA) dari storage
            if ($surat->dok_surat && Storage::disk($storageDisk)->exists($surat->dok_surat)) {
                Storage::disk($storageDisk)->delete($surat->dok_surat);
            }

            // Buat nama file baru (menggantikan dokumen lama)
            $namaFile = time() . '_' . $surat->id . '_final.' . $file->getClientOriginalExtension();

            // 5. Simpan file baru
            $path = $file->storeAs($pathFolder, $namaFile, $storageDisk);

            // 6. Update kolom di database (menggunakan kolom dok_surat yang sudah ada)
            $surat->dok_surat = $path;
            $surat->save();

            return redirect()->route('surat-keluar.index')
                ->with('success', 'Dokumen Surat Keluar berhasil diperbarui dengan versi TTD.');
        }

        return redirect()->back()->with('error', 'Gagal memproses dokumen.');
    }
}
