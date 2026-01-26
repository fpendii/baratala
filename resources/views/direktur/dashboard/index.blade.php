@extends('layout.direktur-template')

@section('title', 'Dashboard Direktur')

@section('content')
<!-- Judul Utama Halaman -->
{{-- Mengganti format py-3 menjadi mb-4 agar konsisten dengan template sebelumnya --}}
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold">
            <span class="text-muted fw-light">Direktur /</span> Dashboard
        </h4>
    </div>
</div>


<!-- Greeting Card / Welcome Banner -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card bg-primary text-white shadow-lg">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title text-white mb-2">Selamat Datang di Panel Direktur! 🎉</h4>
                        <p class="mb-0">
                            Ringkasan harian dan metrik utama untuk pemantauan surat dan karyawan.
                        </p>
                    </div>
                    {{-- Icon Besar di Pojok --}}
                    <i class="ri ri-briefcase-line ri-4x ms-3 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metric Cards (Ringkasan Data Cepat) -->
<div class="row">
    {{-- Card 1: Surat Menunggu Disposisi (Paling Penting untuk Direktur) --}}
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="fw-semibold d-block mb-1">Menunggu Disposisi</h5>
                        <small class="text-muted">Perlu Tindakan Anda</small>
                    </div>
                    <span class="badge bg-label-danger p-2 rounded-circle">
                        <i class="ri ri-inbox-unarchive-fill ri-2x text-danger"></i>
                    </span>
                </div>
                {{-- Data Statistik --}}
                <h3 class="mt-3 mb-0 fw-bold text-danger">3</h3>
            </div>
        </div>
    </div>

    {{-- Card 2: Total Surat Masuk Bulan Ini --}}
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="fw-semibold d-block mb-1">Surat Masuk</h5>
                        <small class="text-muted">Total Bulan Ini</small>
                    </div>
                    <span class="badge bg-label-success p-2 rounded-circle">
                        <i class="ri ri-mail-open-fill ri-2x text-success"></i>
                    </span>
                </div>
                {{-- Data Statistik --}}
                <h3 class="mt-3 mb-0">45</h3>
            </div>
        </div>
    </div>

    {{-- Card 3: Total Karyawan Aktif --}}
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="fw-semibold d-block mb-1">Total Karyawan</h5>
                        <small class="text-muted">Jumlah Aktif</small>
                    </div>
                    <span class="badge bg-label-info p-2 rounded-circle">
                        <i class="ri ri-user-2-fill ri-2x text-info"></i>
                    </span>
                </div>
                {{-- Data Statistik --}}
                <h3 class="mt-3 mb-0">128</h3>
            </div>
        </div>
    </div>

    {{-- Card 4: Surat Keluar Bulan Ini --}}
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="fw-semibold d-block mb-1">Surat Keluar</h5>
                        <small class="text-muted">Total Bulan Ini</small>
                    </div>
                    <span class="badge bg-label-warning p-2 rounded-circle">
                        <i class="ri ri-mail-send-fill ri-2x text-warning"></i>
                    </span>
                </div>
                {{-- Data Statistik --}}
                <h3 class="mt-3 mb-0">32</h3>
            </div>
        </div>
    </div>
</div>
<!-- End Key Metric Cards -->

<div class="row g-4">
    {{-- Recent Activity / Quick Summary Table (Surat Masuk Terbaru) --}}
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <h5 class="card-header"><i class="ri ri-mail-unread-line me-1"></i> 5 Surat Masuk Terbaru</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%;">Kode</th>
                            <th style="width: 35%;">Perihal</th>
                            <th style="width: 25%;">Pengirim</th>
                            <th style="width: 15%;">Tanggal</th>
                            <th style="width: 10%;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr>
                            <td>SM-001</td>
                            <td><strong class="text-dark">Permohonan Kerjasama</strong></td>
                            <td>PT Maju Jaya</td>
                            <td>01/09/2025</td>
                            <td><span class="badge bg-warning">Menunggu</span></td>
                        </tr>
                        <tr>
                            <td>SM-002</td>
                            <td>Laporan Keuangan Q3</td>
                            <td>Departemen Keuangan</td>
                            <td>30/08/2025</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>SM-003</td>
                            <td>Undangan Rapat Tahunan</td>
                            <td>Kementerian XYZ</td>
                            <td>28/08/2025</td>
                            <td><span class="badge bg-info">Didisposisi</span></td>
                        </tr>
                        <tr>
                            <td>SM-004</td>
                            <td>Konfirmasi Kedatangan</td>
                            <td>Kantor Pusat</td>
                            <td>25/08/2025</td>
                            <td><span class="badge bg-danger">Ditolak</span></td>
                        </tr>
                        <tr>
                            <td>SM-005</td>
                            <td>Pengajuan Cuti</td>
                            <td>SDM</td>
                            <td>20/08/2025</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <a href="#" class="text-primary small">Lihat Semua Surat Masuk</a>
            </div>
        </div>
    </div>

    {{-- Distribusi Karyawan (diambil dari kolom kanan sebelumnya) --}}
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-light fw-bold">
                Distribusi Karyawan
            </div>
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <div class="mb-3">
                    <p class="mb-0 text-muted">Total 128 Karyawan</p>
                    <h6 class="fw-bold">Berdasarkan Role Utama:</h6>
                </div>
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Administrasi
                        <span class="badge bg-primary rounded-pill">45</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Pemasaran
                        <span class="badge bg-info rounded-pill">35</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Keuangan
                        <span class="badge bg-success rounded-pill">25</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Teknis
                        <span class="badge bg-warning rounded-pill">22</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    /* Menggunakan skema warna yang mirip dengan Bootstrap 5 untuk badge-label */
    .bg-label-primary {
        background-color: rgba(105, 108, 255, 0.16) !important;
        color: #696cff !important;
    }
    .bg-label-danger {
        background-color: rgba(255, 62, 29, 0.16) !important;
        color: #ff3e1d !important;
    }
    .bg-label-success {
        background-color: rgba(113, 221, 55, 0.16) !important;
        color: #71dd37 !important;
    }
    .bg-label-info {
        background-color: rgba(0, 208, 255, 0.16) !important;
        color: #00d0ff !important;
    }
    .bg-label-warning {
        background-color: rgba(255, 171, 0, 0.16) !important;
        color: #ffab00 !important;
    }
    .ri-2x {
        font-size: 1.5rem; /* Ukuran ikon di dalam badge */
    }
    .ri-4x {
        font-size: 3rem; /* Ukuran ikon di greeting card */
    }
</style>
{{-- Catatan: Pastikan Anda telah menyertakan library Remix Icon (ri) di file layout Anda untuk menampilkan ikon. --}}
@endsection
