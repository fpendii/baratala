<?php

namespace App\Http\Controllers\karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Pengguna;
use App\Models\Komentar; // Ditambahkan: Model Komentar diperlukan untuk fitur komentar
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\WhatsAppHelper;
use Illuminate\Support\Facades\Log;

class RencanaControllerKaryawan extends Controller
{
    /**
     * Menampilkan daftar tugas (rencana) yang ditugaskan kepada pengguna yang sedang login.
     * Tugas yang dibuat sendiri juga otomatis masuk karena creator dimasukkan ke pivot di store().
     */
    public function index()
    {
        // Ambil semua tugas, urutkan terbaru dulu
        $tugas = Tugas::orderBy('created_at', 'desc')->where('id_pengguna', Auth::id())
            ->orWhereHas('pengguna', function ($query) {
                $query->where('pengguna.id', Auth::id());
            })->get();
        // dd($tugas);
        return view('karyawan.rencana.index', compact('tugas'));
    }

    /**
     * Menampilkan formulir untuk membuat tugas baru.
     */
    public function create()
    {
        // Ambil semua pengguna kecuali dirinya sendiri untuk penugasan
        $users = Pengguna::where('id', '!=', Auth::id())->get();
        return view('karyawan.rencana.create', compact('users'));
    }

    /**
     * Menyimpan tugas baru ke database.
     */
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
            $filePath = $request->file('lampiran')->store('rencana_kerja_lampiran', 'public');
            $validated['lampiran'] = $filePath;
        }

        // Simpan tugas
        $tugas = Tugas::create($validated);

        $this->sendWhatsAppNotificationToAdminAndDirector($tugas);


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

        return redirect()->route('karyawan.rencana.index')->with('success', 'Rencana berhasil ditambahkan.');
    }

    private function sendWhatsAppNotificationToAdminAndDirector($tugas)
    {
        // Tentukan salam otomatis berdasarkan waktu
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

        // Format pesan WA
        $message = "👋 *Halo, {$salam} Direktur & Admin PD Baratala!*\n\n"
            . "──────────────────────────────\n"
            . "🗓️ *Rencana Kerja Baru Telah Dibuat*\n"
            . "──────────────────────────────\n\n"
            . "📌 *Judul:* _{$tugas->judul_rencana}_\n"
            . "🧑‍💼 *Dibuat oleh:* _" . Auth::user()->nama . "_\n"
            . "📅 *Tanggal Mulai:* _{$tugas->tanggal_mulai}_\n"
            . "📅 *Tanggal Selesai:* _{$tugas->tanggal_selesai}_\n"
            . "⚡ *Status:* _{$tugas->status}_\n"
            . "📁 *Jenis:* _{$tugas->jenis}_\n\n"
            . "🗂️ Silakan cek sistem untuk detail rencana kerja ini.\n\n"
            . "──────────────────────────────\n"
            . "📎 _Notifikasi otomatis dari Sistem Baratala_";

        // Ambil semua pengguna dengan role direktur dan admin
        $targets = \App\Models\Pengguna::whereIn('role', ['direktur', 'admin'])
            ->whereNotNull('no_hp') // pastikan kolom nomor WhatsApp tidak kosong
            ->pluck('no_hp')
            ->toArray();

        // Kirim pesan ke setiap nomor WA
        foreach ($targets as $target) {
            \App\Helpers\WhatsAppHelper::send($target, $message);
        }
    }



    /**
     * Menampilkan detail tugas, termasuk daftar pengguna dan komentar.
     */
    public function show($id)
    {
        // Eager load pengguna yang ditugaskan dan komentar beserta penggunanya
        $tugas = Tugas::with(['pengguna', 'komentar.pengguna'])->findOrFail($id);

        // Ambil semua pengguna untuk opsi penugasan (jika diperlukan di view detail)
        $allUsers = Pengguna::all();

        return view('karyawan.rencana.detail', compact('tugas', 'allUsers'));
    }

    /**
     * Menampilkan formulir untuk mengedit tugas.
     */
    public function edit($id)
    {
        $rencana = Tugas::with(['pengguna', 'komentar.pengguna'])->findOrFail($id);
        // Ambil semua pengguna kecuali dirinya sendiri untuk penugasan
        $users = Pengguna::where('id', '!=', Auth::id())->get();
        return view('karyawan.rencana.edit', compact('rencana', 'users'));
    }

    /**
     * Memperbarui tugas di database.
     */
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
            'pengguna'         => 'nullable|string', // Hidden input, koma dipisahkan - DITAMBAHKAN
        ]);

        // Kalau ada lampiran baru, simpan
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('public/uploads');
            $validated['lampiran'] = str_replace('public/', '', $lampiranPath);
        }

        // Update tugas
        $tugas->update($validated);

        // Update pivot pengguna (logika sama seperti store)
        $penggunaIds = [];
        if (!empty($request->pengguna)) {
            $penggunaIds = array_filter(explode(',', $request->pengguna));
        }

        // Tambahkan user yang sedang login (pembuat/editor) ke daftar pengguna yang ditugaskan
        $penggunaIds[] = Auth::id();

        // Hilangkan duplikasi & validasi hanya id yang valid
        $penggunaIds = Pengguna::whereIn('id', array_unique($penggunaIds))->pluck('id')->toArray();

        // Sync pivot → otomatis tambah kalau ada id baru, hapus kalau tidak ada lagi
        $tugas->pengguna()->sync($penggunaIds);

        return redirect()->route('karyawan.rencana.index')->with('success', 'Rencana berhasil diperbarui.');
    }

    /**
     * Menyimpan komentar/progres dari karyawan pada tugas tertentu.
     */
    public function komentar(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'isi_komentar' => 'required|string|max:1000', // Mengubah nama field agar lebih generik
        ]);

        // Pastikan tugas ada
        $tugas = Tugas::findOrFail($id);

        // Simpan komentar dengan status 'menunggu' untuk diverifikasi Direktur
        Komentar::create([
            'tugas_id'    => $tugas->id,
            'pengguna_id' => Auth::id(),  // Ambil user yang login (karyawan)
            'isi'         => $request->isi_komentar,
            'status'      => 'menunggu', // Status default dari karyawan: menunggu persetujuan/verifikasi direktur
        ]);

        return redirect()->back()->with('success', 'Progres/Komentar berhasil ditambahkan dan menunggu persetujuan.');
    }

    /**
     * Menghapus tugas dari database.
     */
    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();

        return redirect()->route('karyawan.rencana.index')->with('success', 'Rencana berhasil dihapus.');
    }

     public function updateStatus(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        // Simpan status lama sebelum diupdate
        $oldStatus = ucfirst($tugas->status);

        $validated = $request->validate([
            'status' => 'required|string|in:belum dikerjakan,on progress,selesai',
        ]);

        $tugas->update($validated);

        // Simpan status baru
        $newStatus = ucfirst($tugas->status);

        // === 🔔 Kirim Notifikasi WhatsApp ===
        $this->sendWhatsAppStatusUpdate($tugas, $oldStatus, $newStatus);

        return redirect()->back()->with('success', 'Status rencana berhasil diperbarui.');
    }


    private function sendWhatsAppStatusUpdate($tugas, $oldStatus, $newStatus)
    {
        // 1. Tentukan waktu salam otomatis
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

        // Asumsi user yang sedang login adalah yang melakukan update
        $userUpdater = Auth::user()->nama;
        // Ambil data pengguna yang membuat
        $pengguna = Pengguna::find($tugas->id_pengguna);

        // 2. Format Pesan WhatsApp
        $message = "👋 *Halo, {$salam} Bapak/Ibu!*\n\n"
            . "──────────────────────────────\n"
            . "🔄 {$userUpdater} Baru Saja Mengupdate Status Rencana Kerja\n"
            . "──────────────────────────────\n\n"
            . "📌 *Rencana:* _{$tugas->judul_rencana}_\n"
            // Asumsi model Tugas memiliki relasi 'user' (atau nama relasi lain) ke model 'Pengguna' (pembuat tugas)
            . "👤 *Dibuat oleh:* _{$pengguna->nama}_\n"
            . "➡️ *Diubah oleh:* _{$userUpdater}_\n"
            . "🔄 *Perubahan Status:*\n"
            . "   • Dari: _" . $oldStatus . "_\n"
            . "   • Menjadi: *_{$newStatus}_*\n"
            . "📅 *Batas Waktu:* _" . Carbon::parse($tugas->tanggal_selesai)->format('d M Y') . "_\n\n"
            . "🔗 Silakan cek detail di sistem untuk memantau kemajuan.\n\n"
            . "──────────────────────────────\n"
            . "📎 _Notifikasi otomatis dari Sistem Baratala_";


        // 3. Ambil Daftar Pengguna Target (Direktur dan Admin)
        $targetRoles = ['admin', 'direktur'];

        // CARA MENGAMBIL PENGGUNA (Pilih salah satu, paling umum adalah menggunakan kolom 'role')

        // OPSI 1: Jika model Pengguna memiliki kolom 'role' langsung
        $penggunaTarget = Pengguna::whereIn('role', $targetRoles)->get();

        /* // OPSI 2: Jika model Pengguna menggunakan relasi many-to-many (seperti Spatie)
        // Pastikan model Pengguna memiliki relasi 'roles'
        $penggunaTarget = Pengguna::whereHas('roles', function ($query) use ($targetRoles) {
            $query->whereIn('name', $targetRoles);
        })->get();
        */


        // 4. Kirim notifikasi ke setiap pengguna
        $sentCount = 0;
        foreach ($penggunaTarget as $pengguna) {
            // Pastikan kolom nomor HP ada, berisi data, dan helper WA Anda siap mengirim
            if ($pengguna->no_hp) {
                WhatsAppHelper::send($pengguna->no_hp, $message);
                $sentCount++;
            }
        }

        // Opsional: Tambahkan logging atau notifikasi jika tidak ada pengguna yang ditemukan/terkirim
        if ($sentCount === 0) {
            Log::warning('WA Status Update: Tidak ada Admin/Direktur dengan no_hp yang valid ditemukan.');
        }
    }
}
