@extends('layout.administrasi-template')

@section('title', 'Dashboard')

@section('content')
<!-- Judul Utama Halaman -->
<h4 class="fw-bold py-3">
    <span class="text-muted fw-light">Administrasi /</span> Dashboard
</h4>

<!-- Greeting Card / Welcome Banner -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card bg-primary text-white shadow-lg">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title text-white mb-2">Selamat Datang di Panel Administrasi! 🎉</h4>
                        <p class="mb-0">
                            Ringkasan hari ini menunjukkan metrik utama perusahaan.
                        </p>
                    </div>
                    {{-- Icon Besar di Pojok (Ganti dengan ikon yang sesuai jika perlu) --}}
                    <i class="ri ri-briefcase-line ri-4x ms-3 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metric Cards (Ringkasan Data Cepat) -->
<div class="row">
    {{-- Card 1: Total Karyawan --}}
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="fw-semibold d-block mb-1">Total Karyawan</h5>
                        <small class="text-muted">Aktif & Non-aktif</small>
                    </div>
                    <span class="badge bg-label-primary p-2 rounded-circle">
                        <i class="ri ri-group-line ri-2x"></i>
                    </span>
                </div>
                {{-- Data Statistik (Ganti dengan data dari Controller Anda) --}}
                <h3 class="mt-3 mb-0">{{ number_format(145, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Card 2: Surat Masuk --}}
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="fw-semibold d-block mb-1">Surat Masuk</h5>
                        <small class="text-muted">Menunggu Review</small>
                    </div>
                    <span class="badge bg-label-warning p-2 rounded-circle">
                        <i class="ri ri-mail-send-line ri-2x"></i>
                    </span>
                </div>
                {{-- Data Statistik (Ganti dengan data dari Controller Anda) --}}
                <h3 class="mt-3 mb-0">{{ number_format(8, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Card 3: Jobdesk Aktif --}}
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="fw-semibold d-block mb-1">Jobdesk Aktif</h5>
                        <small class="text-muted">Bulan Ini</small>
                    </div>
                    <span class="badge bg-label-info p-2 rounded-circle">
                        <i class="ri ri-list-check-2 ri-2x"></i>
                    </span>
                </div>
                {{-- Data Statistik (Ganti dengan data dari Controller Anda) --}}
                <h3 class="mt-3 mb-0">{{ number_format(24, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Card 4: Jabatan Terdaftar --}}
    <div class="col-lg-3 col-md-6 col-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="fw-semibold d-block mb-1">Jabatan</h5>
                        <small class="text-muted">Total Unik Role</small>
                    </div>
                    <span class="badge bg-label-success p-2 rounded-circle">
                        <i class="ri ri-bookmark-line ri-2x"></i>
                    </span>
                </div>
                {{-- Data Statistik (Ganti dengan data dari Controller Anda) --}}
                <h3 class="mt-3 mb-0">{{ number_format(12, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>
<!-- End Key Metric Cards -->

<!-- Recent Activity / Quick Summary Table -->
<div class="card">
    <h5 class="card-header"><i class="ri ri-user-add-line me-1"></i> 5 Karyawan Terbaru</h5>
    <div class="table-responsive text-nowrap">
        {{-- Tabel ini menggunakan placeholder data, ganti dengan @foreach dari Controller --}}
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <tr>
                    <td><strong class="text-dark">Budi Santoso</strong></td>
                    <td>budi@corp.com</td>
                    <td><span class="badge bg-label-info">Staff IT</span></td>
                    <td>2024-10-01</td>
                </tr>
                <tr>
                    <td><strong class="text-dark">Citra Kirana</strong></td>
                    <td>citra@corp.com</td>
                    <td><span class="badge bg-label-primary">Administrasi</span></td>
                    <td>2024-09-28</td>
                </tr>
                <tr>
                    <td><strong class="text-dark">Andi Permana</strong></td>
                    <td>andi@corp.com</td>
                    <td><span class="badge bg-label-success">Direktur</span></td>
                    <td>2024-09-25</td>
                </tr>
                <tr>
                    <td><strong class="text-dark">Dewi Sartika</strong></td>
                    <td>dewi@corp.com</td>
                    <td><span class="badge bg-label-secondary">Supervisor</span></td>
                    <td>2024-09-20</td>
                </tr>
                <tr>
                    <td><strong class="text-dark">Faisal Malik</strong></td>
                    <td>faisal@corp.com</td>
                    <td><span class="badge bg-label-info">Staff IT</span></td>
                    <td>2024-09-15</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer text-end">
        <a href="#" class="text-primary small">Lihat Semua Karyawan</a>
    </div>
</div>

@endsection

{{-- Anda bisa menambahkan @push('scripts') di sini jika ada chart atau interaksi kompleks lainnya --}}
