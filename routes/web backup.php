<?php

use App\Http\Controllers\administrasi\DashboardControllerAdministrasi;
use App\Http\Controllers\administrasi\JobdeskControllerAdministrasi;
use App\Http\Controllers\administrasi\ProfilControllerAdministrasi;
use App\Http\Controllers\administrasi\RencanaControllerAdministrasi;
use App\Http\Controllers\administrasi\SuratMasukControllerAdministrasi;
use App\Http\Controllers\administrasi\KaryawanControllerAdministrasi;
use App\Http\Controllers\direktur\DashboardControllerDirektur;
use App\Http\Controllers\direktur\JobdeskControllerDirektur;
use App\Http\Controllers\direktur\KaryawanControllerDirektur;
use App\Http\Controllers\direktur\LaporanControllerDirektur;
use App\Http\Controllers\direktur\LaporanJobdeskControllerDirektur;
use App\Http\Controllers\direktur\LaporanKeuanganControllerDirektur;
use App\Http\Controllers\direktur\ProfilControllerDirektur;
use App\Http\Controllers\direktur\SuratMasukControllerDirektur;
use App\Http\Controllers\direktur\SuratKeluarControllerDirektur;
use App\Http\Controllers\enginer\DashboardControllerEnginer;
use App\Http\Controllers\enginer\JobdeskControllerEnginer;
use App\Http\Controllers\enginer\ProfilControllerEnginer;
use App\Http\Controllers\enginer\RencanaControllerEnginer;
use App\Http\Controllers\karyawan\DashboardControllerKaryawan;
use App\Http\Controllers\karyawan\JobdeskControllerKaryawan;
use App\Http\Controllers\karyawan\ProfilControllerKaryawan;
use App\Http\Controllers\karyawan\RencanaControllerKaryawan;
use App\Http\Controllers\karyawan\SuratMasukControllerKaryawan;
use App\Http\Controllers\KepalaTeknik\DashboardControllerKepalaTeknik;
use App\Http\Controllers\KepalaTeknik\JobdeskControllerKepalaTeknik;
use App\Http\Controllers\KepalaTeknik\ProfilControllerKepalaTeknik;
use App\Http\Controllers\KepalaTeknik\RencanaControllerKepalaTeknik;
use App\Http\Controllers\produksi\DashboardControllerProduksi;
use App\Http\Controllers\produksi\JobdeskControllerProduksi;
use App\Http\Controllers\produksi\ProfilControllerProduksi;
use App\Http\Controllers\produksi\RencanaControllerProduksi;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\direktur\RencanaControllerDirektur;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\karyawan\KeuanganControllerKaryawan;
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


Route::get('/', [LandingPageController::class, 'index'])->name('landingpage');

Route::middleware('guest')->group(function () {




    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/login', [AuthController::class, 'attempt'])->name('login.attempt');

    Route::get('/lupa-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{toke}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// Route Dashboard
Route::get('dashboard', [DashboardController::class, 'index']);

// Route Joddesk
Route::get('data-jobdesk', [DataJobdeskController::class, 'index']);
Route::get('data-jobdesk/create', [DataJobdeskController::class, 'create']);
Route::post('data-jobdesk/store', [DataJobdeskController::class, 'store']);
Route::get('data-jobdesk/{id}', [DataJobdeskController::class, 'show']);
Route::get('data-jobdesk/edit/{id}', [DataJobdeskController::class, 'edit']);
Route::put('data-jobdesk/update/{id}', [DataJobdeskController::class, 'update']);
Route::delete('data-jobdesk/delete/{id}', [DataJobdeskController::class, 'destroy']);

// Keuangan
Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
Route::get('keuangan/pengeluaran/create', [KeuanganController::class, 'createPengeluaran']);
Route::post('keuangan/pengeluaran/store', [KeuanganController::class, 'storePengeluaran']);
Route::get('keuangan/kasbon/create', [KeuanganController::class, 'createKasbon']);
Route::post('keuangan/kasbon/store', [KeuanganController::class, 'storeKasbon']);
Route::get('keuangan/uang-masuk/create', [KeuanganController::class, 'createUangMasuk']);
Route::post('keuangan/uang-masuk/store', [KeuanganController::class, 'storeUangMasuk']);
Route::delete('keuangan/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
Route::get('keuangan/{id}/edit', [KeuanganController::class, 'edit'])->name('keuangan.edit');
Route::get('keuangan/persetujuan/{id}', [KeuanganController::class, 'persetujuan'])->name('keuangan.persetujuan');
Route::put('keuangan/persetujuan/{id}', [KeuanganController::class, 'updatePersetujuan'])->name('keuangan.updatePersetujuan');
Route::get('keuangan/generate-pdf/{id}', [KeuanganController::class, 'generatePDF'])->name('keuangan.generate-pdf');


Route::get('keuangan/preview/{id}', [KeuanganController::class, 'previewPdfView'])->name('keuangan.preview');

// Route baru untuk menghasilkan PDF
Route::get('/laporan-keuangan/{id}/generate-pdf', [KeuanganControllerKaryawan::class, 'generatePDF'])->name('karyawan   .keuangan-laporan.generate-pdf');
Route::get('keuangan/export', [KeuanganControllerKaryawan::class, 'exportExcel'])->name('keuangan.export');

// Route Rencana Kerja
Route::get('rencana', [RencanaController::class, 'index'])->name('rencana.index');
Route::get('rencana/create', [RencanaController::class, 'create'])->name('rencana.create');
Route::post('rencana/store', [RencanaController::class, 'store'])->name('rencana.store');
Route::get('rencana/{id}', [RencanaController::class, 'show'])->name('rencana.show');
Route::get('rencana/{id}/edit', [RencanaController::class, 'edit'])->name('rencana.edit');
Route::put('rencana/{id}', [RencanaController::class, 'update'])->name('rencana.update');
Route::get('rencana', [RencanaController::class, 'index'])->name('rencana.index');
Route::delete('rencana/delete/{id}', [RencanaController::class, 'destroy'])->name('rencana.destroy');
Route::patch('rencana/{id}/update-status', [RencanaController::class, 'updateStatus'])->name('rencana.updateStatus');
Route::post('rencana/updatePengguna/{id}', [RencanaController::class, 'updatePengguna'])->name('rencana.updatePengguna');
Route::post('rencana/komentar/{id}', [RencanaController::class, 'komentar'])->name('rencana.komentar');
Route::post('rencana/komentar/{id}/status', [RencanaController::class, 'updateKomentarStatus'])->name('rencana.komentar.status');

Route::post('rencana/komentar/{id}', [RencanaController::class, 'komentar'])->name('rencana.komentar');


//Surat Masuk
Route::get('surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
Route::get('surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
Route::post('surat-masuk/store', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
Route::get('surat-masuk/{id}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
Route::get('surat-masuk/edit/{id}', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
Route::put('surat-masuk/update/{id}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
Route::delete('surat-masuk/delete/{id}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
Route::get('surat-masuk/download/{id}', [SuratMasukController::class, 'downloadLampiran'])->name('surat-masuk.download');

// routee surat keluar
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
Route::get('profil', [ProfilController::class, 'index']);
Route::post('profil/update/{id}', [ProfilController::class, 'update'])->name('profil.update');


// Route Direktur
Route::prefix('direktur')->name('direktur.')->group(function () {
    // // Dashboard
    // Route::get('dashboard', [DashboardControllerDirektur::class, 'index'])->name('dashboard');

    // // Rencana kerja
    // Route::resource('rencana', RencanaControllerDirektur::class);
    // Route::post('rencana/updatePengguna/{id}', [RencanaControllerDirektur::class, 'updatePengguna'])->name('rencana.updatePengguna');
    // Route::post('rencana/komentar/{id}', [RencanaControllerDirektur::class, 'komentar'])->name('rencana.komentar');
    // // Ubah status komentar
    // Route::post('rencana/komentar/{id}/status', [RencanaControllerDirektur::class, 'updateKomentarStatus'])->name('rencana.komentar.status');

    //Surat Masuk
    Route::get('surat-masuk', [SuratMasukControllerDirektur::class, 'index'])->name('surat-masuk.index');
    Route::get('surat-masuk/create', [SuratMasukControllerDirektur::class, 'create'])->name('surat-masuk.create');
    Route::post('surat-masuk/store', [SuratMasukControllerDirektur::class, 'store'])->name('surat-masuk.store');
    Route::get('surat-masuk/{id}', [SuratMasukControllerDirektur::class, 'show'])->name('surat-masuk.show');
    Route::get('surat-masuk/edit/{id}', [SuratMasukControllerDirektur::class, 'edit'])->name('surat-masuk.edit');
    Route::put('surat-masuk/{id}', [SuratMasukControllerDirektur::class, 'update'])->name('surat-masuk.update');
    Route::delete('surat-masuk/delete/{id}', [SuratMasukControllerDirektur::class, 'destroy'])->name('surat-masuk.destroy');
    Route::get('surat-masuk/download/{id}', [SuratMasukControllerDirektur::class, 'downloadLampiran'])->name('surat-masuk.download');

    // Karyawan
    Route::resource('karyawan', KaryawanControllerDirektur::class);

    // Jobdesk (CRUD)
    Route::resource('data-jobdesk', JobdeskControllerDirektur::class);

    // Laporan (pakai resource biar rapih, tapi bisa disesuaikan kalau memang tidak semua method)
    Route::resource('laporan', LaporanControllerDirektur::class)->only(['index', 'show', 'update']);
    Route::get('laporan/tabel', [LaporanControllerDirektur::class, 'TampilanTabel'])->name('laporan.tabel');
    Route::get('laporan/grafik', [LaporanControllerDirektur::class, 'TampilanGrafik'])->name('laporan.grafik');
    Route::post('laporan/keputusan/{id}', [LaporanControllerDirektur::class, 'updateKeputusan'])->name('laporan.keputusan');

    // Laporan Keuangan
    Route::resource('keuangan-laporan', LaporanKeuanganControllerDirektur::class)->only(['index', 'show']);
    Route::get('keuangan-laporan/persetujuan/{id}', [LaporanKeuanganControllerDirektur::class, 'persetujuan'])->name('keuangan-laporan.persetujuan');
    Route::put('keuangan-laporan/persetujuan/{id}', [LaporanKeuanganControllerDirektur::class, 'updatePersetujuan'])->name('keuangan-laporan.updatePersetujuan');
    Route::get('keuangan-laporan/generate-pdf/{id}', [LaporanKeuanganControllerDirektur::class, 'generatePDF'])->name('keuangan-laporan.generate-pdf');

    // Laporan Jobdesk
    Route::get('jobdesk-laporan', [LaporanJobdeskControllerDirektur::class, 'index'])->name('laporan-jobdesk.index');
    Route::get('jobdesk-laporan/detail/{id}', [LaporanJobdeskControllerDirektur::class, 'detail'])->name('laporan-jobdesk.detail');
    Route::get('jobdesk-laporan/jobdesk-karyawan', [LaporanJobdeskControllerDirektur::class, 'JobdeskKaryawan'])->name('laporan-jobdesk.karyawan');
    Route::get('jobdesk-laporan/jobdesk-karyawan/detail/{id}', [LaporanJobdeskControllerDirektur::class, 'detailJobdeskKaryawan'])->name('laporan-jobdesk.karyawan.detail');

    // Profil
    Route::resource('profil', ProfilControllerDirektur::class)->only(['index', 'edit', 'update']);
});


// Route Administrasi
Route::prefix('administrasi')->group(function () {

    Route::get('dashboard', [DashboardControllerAdministrasi::class, 'index']);

    // Route::get('rencana-kerja', [RencanaControllerAdministrasi::class, 'index']);

    Route::get('rencana', [RencanaControllerAdministrasi::class, 'index']);
    Route::get('rencana/create', [RencanaControllerAdministrasi::class, 'create']);
    Route::post('rencana/store', [RencanaControllerAdministrasi::class, 'store']);
    Route::get('rencana/{id}', [RencanaControllerAdministrasi::class, 'show']);
    Route::get('rencana/edit/{id}', [RencanaControllerAdministrasi::class, 'edit']);
    Route::put('rencana/update/{id}', [RencanaControllerAdministrasi::class, 'update']);
    Route::delete('rencana/delete/{id}', [RencanaControllerAdministrasi::class, 'destroy']);
    Route::post('rencana/komentar/{id}', [RencanaControllerAdministrasi::class, 'komentar']);

    Route::get('jobdesk', [JobdeskControllerAdministrasi::class, 'index']);
    Route::get('jobdesk/create', [JobdeskControllerAdministrasi::class, 'create']);
    Route::post('jobdesk/store', [JobdeskControllerAdministrasi::class, 'store']);
    Route::get('jobdesk/{id}', [JobdeskControllerAdministrasi::class, 'show']);
    Route::get('jobdesk/edit/{id}', [JobdeskControllerAdministrasi::class, 'edit']);
    Route::put('jobdesk/update/{id}', [JobdeskControllerAdministrasi::class, 'update']);
    Route::delete('jobdesk/delete/{id}', [JobdeskControllerAdministrasi::class, 'destroy']);

    Route::get('surat-masuk', [SuratMasukControllerAdministrasi::class, 'index']);
    Route::get('surat-masuk/create', [SuratMasukControllerAdministrasi::class, 'create']);
    Route::post('surat-masuk/store', [SuratMasukControllerAdministrasi::class, 'store']);
    Route::get('surat-masuk/{id}', [SuratMasukControllerAdministrasi::class, 'show']);
    Route::get('surat-masuk/edit/{id}', [SuratMasukControllerAdministrasi::class, 'edit']);
    Route::put('surat-masuk/update/{id}', [SuratMasukControllerAdministrasi::class, 'update']);
    Route::delete('surat-masuk/delete/{id}', [SuratMasukControllerAdministrasi::class, 'destroy']);

    Route::get('profil', [ProfilControllerAdministrasi::class, 'index']);

    Route::get('karyawan', [KaryawanControllerAdministrasi::class, 'index'])->name('administrasi.karyawan.index');
    Route::get('karyawan/create', [KaryawanControllerAdministrasi::class, 'create'])->name('administrasi.karyawan.create');
    Route::post('karyawan/store', [KaryawanControllerAdministrasi::class, 'store'])->name('administrasi.karyawan.store');
    Route::get('karyawan/{id}', [KaryawanControllerAdministrasi::class, 'show'])->name('administrasi.karyawan.show');
    Route::get('karyawan/edit/{id}', [KaryawanControllerAdministrasi::class, 'edit'])->name('administrasi.karyawan.edit');
    Route::put('karyawan/update/{id}', [KaryawanControllerAdministrasi::class, 'update'])->name('administrasi.karyawan.update');
    Route::delete('karyawan/delete/{id}', [KaryawanControllerAdministrasi::class, 'destroy'])->name('administrasi.karyawan.destroy');
});

// Route karyawan
Route::prefix('karyawan')->name('karyawan.')->group(function () {

    Route::get('dashboard', [DashboardControllerKaryawan::class, 'index']);

    // Route::get('rencana', [RencanaControllerKaryawan::class, 'index']);
    // Route::get('rencana/create', [RencanaControllerKaryawan::class, 'create']);

    Route::get('rencana', [RencanaControllerKaryawan::class, 'index'])->name('rencana.index');
    Route::get('rencana/create', [RencanaControllerKaryawan::class, 'create'])->name('rencana.create');
    Route::post('rencana/store', [RencanaControllerKaryawan::class, 'store'])->name('rencana.store');
    Route::get('rencana/{id}', [RencanaControllerKaryawan::class, 'show'])->name('rencana.show');
    Route::get('rencana/{id}/edit', [RencanaControllerKaryawan::class, 'edit'])->name('rencana.edit');
    Route::put('rencana/{id}', [RencanaControllerKaryawan::class, 'update'])->name('rencana.update');
    Route::get('rencana', [RencanaControllerKaryawan::class, 'index'])->name('rencana.index');
    Route::delete('rencana/delete/{id}', [RencanaControllerKaryawan::class, 'destroy'])->name('rencana.destroy');
    Route::patch('rencana/{id}/update-status', [RencanaControllerKaryawan::class, 'updateStatus'])->name('rencana.updateStatus');

    Route::post('rencana/komentar/{id}', [RencanaControllerKaryawan::class, 'komentar'])->name('rencana.komentar');


    Route::get('jobdesk', [JobdeskControllerKaryawan::class, 'index'])->name('jobdesk.index');
    Route::get('jobdesk/create', [JobdeskControllerKaryawan::class, 'create'])->name('jobdesk.create');
    Route::post('jobdesk/store', [JobdeskControllerKaryawan::class, 'store'])->name('jobdesk.store');
    Route::get('jobdesk/{id}', [JobdeskControllerKaryawan::class, 'show'])->name('jobdesk.show');
    Route::get('jobdesk/{id}/edit', [JobdeskControllerKaryawan::class, 'edit'])->name('jobdesk.edit');
    Route::put('jobdesk/{id}', [JobdeskControllerKaryawan::class, 'update'])->name('jobdesk.update');


    Route::get('profil', [ProfilControllerKaryawan::class, 'index']);

    // Keuangan
    Route::get('keuangan', [KeuanganControllerKaryawan::class, 'index'])->name('keuangan.index');
    Route::get('keuangan/pengeluaran/create', [KeuanganControllerKaryawan::class, 'createPengeluaran']);
    Route::post('keuangan/pengeluaran/store', [KeuanganControllerKaryawan::class, 'storePengeluaran']);
    Route::get('keuangan/kasbon/create', [KeuanganControllerKaryawan::class, 'createKasbon']);
    Route::post('keuangan/kasbon/store', [KeuanganControllerKaryawan::class, 'storeKasbon']);
    Route::get('keuangan/uang-masuk/create', [KeuanganControllerKaryawan::class, 'createUangMasuk']);
    Route::post('keuangan/uang-masuk/store', [KeuanganControllerKaryawan::class, 'storeUangMasuk']);
    Route::delete('keuangan/{id}', [KeuanganControllerKaryawan::class, 'destroy'])->name('keuangan.destroy');
    Route::get('keuangan/{id}/edit', [KeuanganControllerKaryawan::class, 'edit'])->name('keuangan.edit');

    // Route baru untuk menghasilkan PDF
    Route::get('/laporan-keuangan/{id}/generate-pdf', [KeuanganControllerKaryawan::class, 'generatePDF'])->name('karyawan   .keuangan-laporan.generate-pdf');
    Route::get('keuangan/export', [KeuanganControllerKaryawan::class, 'exportExcel'])->name('keuangan.export');


    //Surat Masuk
    Route::get('surat-masuk', [SuratMasukControllerKaryawan::class, 'index'])->name('surat-masuk.index');
    Route::get('surat-masuk/create', [SuratMasukControllerKaryawan::class, 'create'])->name('surat-masuk.create');
    Route::post('surat-masuk/store', [SuratMasukControllerKaryawan::class, 'store'])->name('surat-masuk.store');
    Route::get('surat-masuk/{id}', [SuratMasukControllerKaryawan::class, 'show'])->name('surat-masuk.show');
    Route::get('surat-masuk/edit/{id}', [SuratMasukControllerKaryawan::class, 'edit'])->name('surat-masuk.edit');
    Route::put('surat-masuk/update/{id}', [SuratMasukControllerKaryawan::class, 'update'])->name('surat-masuk.update');
    Route::delete('surat-masuk/delete/{id}', [SuratMasukControllerKaryawan::class, 'destroy'])->name('surat-masuk.destroy');
    Route::get('surat-masuk/download/{id}', [SuratMasukControllerKaryawan::class, 'downloadLampiran'])->name('surat-masuk.download');
});
