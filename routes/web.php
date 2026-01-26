<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response; // Tambahkan ini di atasuse App\Http\Controllers\karyawan\KeuanganControllerKaryawan;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RencanaController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\JobdeskController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\DataJobdeskController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DisposisiSuratController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;


// Route::get('/clear-cache', function () {
//     Artisan::call('config:clear');
//     Artisan::call('cache:clear');
//     return "Cache berhasil dibersihkan!";
// });

// Route::get('/cek-public', function () {
//     dd(
//         realpath(base_path('public')),
//         realpath(public_path()),
//         realpath('/home/pdbaratala/domains/pdbaratala.com/public_html')
//     );
// });

Route::get('/', [LandingPageController::class, 'index'])->name('landingpage');

// =================================================================
// RUTE UNTUK GUEST (Belum Login)
// =================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'attempt'])->name('login.attempt');

    // Password Reset
    Route::get('/lupa-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset'); // Perbaikan: variabel {toke} menjadi {token}
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// RUTE LOGOUT (Membutuhkan auth, tapi harus di luar middleware('auth') agar bisa diakses untuk keluar)
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// =================================================================
// RUTE UNTUK PENGGUNA TEROTENTIKASI (Sudah Login)
// Semua rute di dalam grup ini hanya bisa diakses setelah login.
// Jika belum login, akan diarahkan ke route 'login' (default Laravel)
// =================================================================
Route::get('/disposisi/saya', [DisposisiSuratController::class, 'disposisiSaya'])->name('disposisi.saya');
Route::middleware('login')->group(function () {

    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{id}/revision', [DocumentController::class, 'storeRevision'])->name('documents.revision');

    // Route khusus untuk penambahan instan lewat AJAX
    Route::post('/categories/ajax', [CategoryController::class, 'storeAjax'])->name('categories.store_ajax');
    Route::post('/tags/ajax', [TagController::class, 'storeAjax'])->name('tags.store_ajax');

    // Route Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Joddesk Data
    Route::get('data-jobdesk', [DataJobdeskController::class, 'index'])->name('data-jobdesk.index');
    Route::get('data-jobdesk/create', [DataJobdeskController::class, 'create'])->name('data-jobdesk.create');
    Route::post('data-jobdesk/store', [DataJobdeskController::class, 'store'])->name('data-jobdesk.store');
    Route::get('data-jobdesk/{id}', [DataJobdeskController::class, 'show'])->name('data-jobdesk.show');
    Route::get('data-jobdesk/edit/{id}', [DataJobdeskController::class, 'edit'])->name('data-jobdesk.edit');
    Route::put('data-jobdesk/update/{id}', [DataJobdeskController::class, 'update'])->name('data-jobdesk.update');
    Route::delete('data-jobdesk/delete/{id}', [DataJobdeskController::class, 'destroy'])->name('data-jobdesk.destroy');
    Route::delete('data-jobdesk/bulk-delete', [DataJobdeskController::class, 'bulkDelete'])->name('data-jobdesk.bulkDelete');

    // Keuangan
    Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('keuangan/pengeluaran/create', [KeuanganController::class, 'createPengeluaran'])->name('keuangan.createPengeluaran');
    Route::post('keuangan/pengeluaran/store', [KeuanganController::class, 'storePengeluaran'])->name('keuangan.storePengeluaran');
    Route::get('keuangan/kasbon/create', [KeuanganController::class, 'createKasbon'])->name('keuangan.createKasbon');
    Route::post('keuangan/kasbon/store', [KeuanganController::class, 'storeKasbon'])->name('keuangan.storeKasbon');
    Route::get('keuangan/uang-masuk/create', [KeuanganController::class, 'createUangMasuk'])->name('keuangan.createUangMasuk');
    Route::post('keuangan/uang-masuk/store', [KeuanganController::class, 'storeUangMasuk'])->name('keuangan.storeUangMasuk');
    Route::delete('keuangan/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
    Route::get('keuangan/{id}/edit', [KeuanganController::class, 'edit'])->name('keuangan.edit');
    Route::get('keuangan/persetujuan/{id}', [KeuanganController::class, 'persetujuan'])->name('keuangan.persetujuan');
    Route::put('keuangan/persetujuan/{id}', [KeuanganController::class, 'updatePersetujuan'])->name('keuangan.updatePersetujuan');
    Route::get('keuangan/generate-pdf/{id}', [KeuanganController::class, 'generatePDF'])->name('keuangan.generate-pdf');
    Route::get('keuangan/preview/{id}', [KeuanganController::class, 'previewPdfView'])->name('keuangan.preview');
    Route::get('/laporan-keuangan/{id}/generate-pdf', [KeuanganController::class, 'generatePDF'])->name('karyawan.keuangan-laporan.generate-pdf'); // Perbaikan nama route
    Route::get('keuangan/export', [KeuanganController::class, 'exportExcel'])->name('keuangan.export');

    // Route Rencana Kerja
    Route::get('rencana', [RencanaController::class, 'index'])->name('rencana.index');
    Route::get('rencana/create', [RencanaController::class, 'create'])->name('rencana.create');
    Route::post('rencana/store', [RencanaController::class, 'store'])->name('rencana.store');
    Route::get('rencana/{id}', [RencanaController::class, 'show'])->name('rencana.show');
    Route::get('rencana/{id}/edit', [RencanaController::class, 'edit'])->name('rencana.edit');
    Route::put('rencana/{id}', [RencanaController::class, 'update'])->name('rencana.update');
    Route::delete('rencana/delete/{id}', [RencanaController::class, 'destroy'])->name('rencana.destroy');
    Route::patch('rencana/{id}/update-status', [RencanaController::class, 'updateStatus'])->name('rencana.updateStatus');
    Route::post('rencana/updatePengguna/{id}', [RencanaController::class, 'updatePengguna'])->name('rencana.updatePengguna');

    // Komentar Rencana Kerja
    Route::post('rencana/komentar/{id}', [RencanaController::class, 'komentar'])->name('rencana.komentar');
    Route::post('rencana/komentar/{id}/status', [RencanaController::class, 'updateKomentarStatus'])->name('rencana.komentar.status');

    // Surat Masuk
    Route::get('surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::get('surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::post('surat-masuk/store', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
    Route::get('surat-masuk/{id}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::get('surat-masuk/edit/{id}', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
    Route::put('surat-masuk/update/{id}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    Route::delete('surat-masuk/delete/{id}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
    Route::get('surat-masuk/download/{id}', [SuratMasukController::class, 'downloadLampiran'])->name('surat-masuk.download');

    // Tampilkan form disposisi (direktur)
    Route::get('/disposisi', [DisposisiSuratController::class, 'index'])->name('disposisi.index');
    Route::get('/disposisi/{surat}', [DisposisiSuratController::class, 'create'])->name('disposisi.create');



    // Simpan disposisi (multi user)
    Route::post('/disposisi/{surat}', [DisposisiSuratController::class, 'store'])->name('disposisi.store');

    // Inbox disposisi untuk user
    Route::get('/disposisi-masuk', [DisposisiSuratController::class, 'inbox'])->name('disposisi.inbox');

    // Tandai disposisi sudah dibaca
    Route::post('/disposisi/{id}/baca', [DisposisiSuratController::class, 'markAsRead'])->name('disposisi.baca');

    // Surat Keluar
    Route::get('surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
    Route::get('surat-keluar/create', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
    Route::post('surat-keluar/store', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
    Route::get('surat-keluar/{id}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
    Route::get('surat-keluar/edit/{id}', [SuratKeluarController::class, 'edit'])->name('surat-keluar.edit');
    Route::put('surat-keluar/update/{id}', [SuratKeluarController::class, 'update'])->name('surat-keluar.update');
    Route::delete('surat-keluar/delete/{id}', [SuratKeluarController::class, 'destroy'])->name('surat-keluar.destroy');
    Route::put('surat-keluar/update-dokumen', [SuratKeluarController::class, 'updateDokumen'])->name('surat-keluar.update-dokumen');

    // Route Jobdesk
    Route::get('jobdesk', [JobdeskController::class, 'index'])->name('jobdesk.index');
    Route::get('jobdesk/create', [JobdeskController::class, 'create'])->name('jobdesk.create');
    Route::post('jobdesk/store', [JobdeskController::class, 'store'])->name('jobdesk.store');
    Route::get('jobdesk/{id}', [JobdeskController::class, 'show'])->name('jobdesk.show');
    Route::get('jobdesk/{id}/edit', [JobdeskController::class, 'edit'])->name('jobdesk.edit');
    Route::put('jobdesk/{id}', [JobdeskController::class, 'update'])->name('jobdesk.update');

    // Route Pengguna
    Route::get('pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
    Route::post('pengguna/store', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('pengguna/{id}', [PenggunaController::class, 'show'])->name('pengguna.show');
    Route::get('pengguna/edit/{id}', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('pengguna/update/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('pengguna/delete/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

    // Profil
    Route::get('profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::post('profil/update/{id}', [ProfilController::class, 'update'])->name('profil.update');

    // Dokumen Digital
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/documents/{document}/revision', [DocumentController::class, 'uploadRevision'])->name('documents.revision');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');


    //Danang Hacker
    Route::get('/buka-surat/{filename}', function ($filename) {

        $path = storage_path('app/public/surat_keluar_dokumen/' . $filename);

        if (!File::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $mimeType = File::mimeType($path);

        // Daftar tipe file yang diperbolehkan buka langsung di browser (Inline)
        $inlineTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];

        if (in_array($mimeType, $inlineTypes)) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }

        // Selain itu (Word, Excel, dll) akan otomatis terdownload
        return response()->download($path, $filename);
    })->name('surat.tampil');


    Route::get('/buka-keuangan/{filename}', function ($filename) {

        $path = storage_path('app/public/lampiran_keuangan/' . $filename);

        if (!File::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $mimeType = File::mimeType($path);

        // File yang boleh dibuka langsung (inline)
        $inlineTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif'
        ];

        if (in_array($mimeType, $inlineTypes)) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }

        // Selain itu (Word, Excel, dll) auto download
        return response()->download($path, $filename);
    })->name('keuangan.tampil');

    Route::get('/buka-keuangan-aprove/{filename}', function ($filename) {

        $path = storage_path('app/public/bukti_persetujuan_pdf/' . $filename);

        if (!File::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $mimeType = File::mimeType($path);

        // File yang boleh dibuka langsung (inline)
        $inlineTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif'
        ];

        if (in_array($mimeType, $inlineTypes)) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }

        // Selain itu (Word, Excel, dll) auto download
        return response()->download($path, $filename);
    })->name('keuangan-aprove.tampil');

    Route::get('/buka-surat-masuk/{filename}', function ($filename) {

        $path = storage_path('app/public/surat_masuk_lampiran/' . $filename);

        if (!File::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $mimeType = File::mimeType($path);

        // Daftar tipe file yang diperbolehkan buka langsung di browser (Inline)
        $inlineTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];

        if (in_array($mimeType, $inlineTypes)) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }

        // Selain itu (Word, Excel, dll) akan otomatis terdownload
        return response()->download($path, $filename);
    })->name('surat-masuk.tampil');
});
