<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\Pengguna;
use App\Models\Komentar; // Ditambahkan: Model Komentar diperlukan untuk fitur komentar
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\WhatsAppHelper;
use Illuminate\Support\Facades\Log;

class RencanaController extends Controller
{
    public function index()
    {
        // Jika login sebagai Direktur → tampilkan semua tugas
        if (Auth::user()->role === 'direktur') {
            $tugas = Tugas::with('komentar')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Jika bukan Direktur → tampilkan tugas miliknya saja
            $tugas = Tugas::with('komentar')
                ->where('id_pengguna', Auth::id())
                ->orWhereHas('pengguna', function ($query) {
                    $query->where('pengguna.id', Auth::id());
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('rencana.index', compact('tugas'));
    }


    public function create()
    {
        // Ambil semua pengguna kecuali dirinya sendiri untuk penugasan
        $users = Pengguna::where('id', '!=', Auth::id())->get();
        return view('rencana.create', compact('users'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI DATA 🎯
        $validated = $request->validate([
            'judul_rencana'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'status'           => 'required|string',
            'jenis'            => 'required|string',
            'prioritas'        => 'nullable|string',
            'lampiran'         => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            // Input pengguna: berisi ID pengguna yang ditugaskan, dipisahkan koma
            'pengguna_ditugaskan' => 'nullable|string',
        ]);

        // 2. PREPARASI DATA UNTUK TABEL 'tugas' 📋
        // Kolom 'id_pengguna' di tabel 'tugas' diisi dengan ID pembuat tugas (yang sedang login).
        $validated['id_pengguna'] = Auth::id(); // Ini adalah Foreign Key ke user pembuat (creator)

        // Penanganan Lampiran
        if ($request->hasFile('lampiran')) {
            $filePath = $request->file('lampiran')->store('rencana_kerja_lampiran', 'public');
            $validated['lampiran'] = $filePath;
        }

        // Hapus key 'pengguna_ditugaskan' dari array validated agar tidak masuk ke Tugas::create
        $penggunaInput = $request->pengguna_ditugaskan;
        unset($validated['pengguna_ditugaskan']);

        // 3. SIMPAN KE TABEL 'tugas'
        $tugas = Tugas::create($validated);

        // Panggil notifikasi (sesuaikan jika logic ini berada di model atau event)
        $this->sendWhatsAppNotificationToAdminAndDirector($tugas);

        // 4. LOGIKA PIVOT KE TABEL 'tugas_pengguna' (Many-to-Many) 🤝

        // Ambil ID pengguna dari input (pecah string jadi array)
        $penggunaIds = [];
        if (!empty($penggunaInput)) {
            // Explode string input menjadi array ID
            $penggunaIds = array_filter(explode(',', $penggunaInput));
        }

        // Tambahkan user yang sedang login (pembuat tugas) ke daftar pengguna yang ditugaskan (jika belum ada)
        // Ini penting jika pembuat tugas juga harus menjadi penanggung jawab tugas.
        if (!in_array(Auth::id(), $penggunaIds)) {
            $penggunaIds[] = Auth::id();
        }

        // Hilangkan duplikasi & validasi hanya ID yang benar-benar valid di tabel pengguna
        // Ini mencegah error jika ada ID yang tidak valid dikirim melalui form.
        $penggunaIds = Pengguna::whereIn('id', array_unique($penggunaIds))->pluck('id')->toArray();

        // Simpan relasi ke tabel pivot 'tugas_pengguna'
        // Asumsi: Anda telah mendefinisikan relasi many-to-many bernama 'pengguna' di model Tugas.
        $tugas->pengguna()->sync($penggunaIds);

        // 5. REDIRECT
        return redirect()->route('rencana.index')->with('success', 'Rencana berhasil ditambahkan.');
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

        return view('rencana.detail', compact('tugas', 'allUsers'));
    }

    /**
     * Menampilkan formulir untuk mengedit tugas.
     */
    public function edit($id)
    {
        $rencana = Tugas::with(['pengguna', 'komentar.pengguna'])->findOrFail($id);
        // Ambil semua pengguna kecuali dirinya sendiri untuk penugasan
        $users = Pengguna::where('id', '!=', Auth::id())->get();
        return view('rencana.edit', compact('rencana', 'users'));
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

        return redirect()->route('rencana.index')->with('success', 'Rencana berhasil diperbarui.');
    }

    /**
     * Menyimpan komentar/progres dari karyawan pada tugas tertentu.
     */
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

    /**
     * Menghapus tugas dari database.
     */
    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();

        return redirect()->route('rencana.index')->with('success', 'Rencana berhasil dihapus.');
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

    public function updatePengguna(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        // validasi input pengguna (array id user)
        $validated = $request->validate([
            'pengguna'   => 'nullable|array',
            'pengguna.*' => 'exists:pengguna,id', // pastikan semua id user valid
        ]);

        $penggunaIdsBaru = $validated['pengguna'] ?? [];

        // Ambil user sebelumnya
        $penggunaIdsLama = $tugas->pengguna()->pluck('pengguna.id')->toArray();


        // Sync pivot (hapus & tambah)
        $tugas->pengguna()->sync($penggunaIdsBaru);

        // Cari user yang baru ditambahkan
        $penggunaTambahan = array_diff($penggunaIdsBaru, $penggunaIdsLama);

        if (!empty($penggunaTambahan)) {
            $this->sendWhatsAppToAssignedUsers($tugas, $penggunaTambahan);
        }

        return redirect()
            ->route('rencana.show', $tugas->id)
            ->with('success', 'Pengguna berhasil diperbarui & notifikasi terkirim.');
    }

    private function sendWhatsAppToAssignedUsers($tugas, $userIds)
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

        // Ambil pengguna yg baru ditambahkan
        $users = \App\Models\Pengguna::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            if (!$user->no_hp) continue;

            $message = "👋 *{$salam}, {$user->nama}!*\n\n"
                . "Anda baru saja ditugaskan untuk rencana kerja:\n\n"
                . "📌 *{$tugas->judul_rencana}*\n"
                . "📅 *Mulai:* " . $tugas->tanggal_mulai . "\n"
                . "📅 *Selesai:* " . $tugas->tanggal_selesai . "\n"
                . "⚡ *Status:* {$tugas->status}\n\n"
                . "Silakan cek aplikasi untuk detail tugas.\n\n"
                . "_Notifikasi otomatis dari Sistem Baratala_";

            \App\Helpers\WhatsAppHelper::send($user->no_hp, $message);
        }
    }
}
