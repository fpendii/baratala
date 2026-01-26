@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold">Dashboard</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card bg-primary text-white shadow-lg">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title text-white mb-2">Selamat Datang {{ auth()->user()->nama }}</h4>
                            <p class="mb-0">Ringkasan harian dan metrik utama untuk pemantauan surat dan karyawan.</p>
                        </div>
                        <i class="ri ri-briefcase-line ri-4x ms-3 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Card 1: Surat Keluar --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-semibold mb-1">Surat Keluar</h5>
                            <small class="text-muted">Bulan Ini</small>
                        </div>
                        <span class="badge bg-label-warning p-2 rounded-circle">
                            <i class="ri ri-mail-send-fill ri-2x text-warning"></i>
                        </span>
                    </div>
                    <h3 class="mt-3 mb-0">{{ $totalSuratKeluarBulanIni }}</h3>
                </div>
            </div>
        </div>

        {{-- Card 2: Surat Masuk --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-semibold mb-1">Surat Masuk</h5>
                            <small class="text-muted">Bulan Ini</small>
                        </div>
                        <span class="badge bg-label-success p-2 rounded-circle">
                            <i class="ri ri-mail-open-fill ri-2x text-success"></i>
                        </span>
                    </div>
                    <h3 class="mt-3 mb-0">{{ $totalSuratMasukBulanIni }}</h3>
                </div>
            </div>
        </div>

        {{-- Card 3: Karyawan --}}
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-semibold mb-1">Karyawan</h5>
                            <small class="text-muted">Total Karyawan Terdaftar</small>
                        </div>
                        <span class="badge bg-label-info p-2 rounded-circle">
                            <i class="ri ri-user-2-fill ri-2x text-info"></i>
                        </span>
                    </div>
                    <h3 class="mt-3 mb-0">{{ $totalPengguna }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                    <h5 class="mb-0">Aktivitas Surat Terbaru</h5>
                    <i class="ri-history-line text-muted"></i>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-fill mb-3" id="suratTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk" type="button">Masuk</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="keluar-tab" data-bs-toggle="tab" data-bs-target="#keluar" type="button">Keluar</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="suratTabContent">
                        <div class="tab-pane fade show active" id="masuk" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No. Surat</th>
                                            <th>Asal/Perihal</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($suratMasukTerbaru as $sm)
                                        <tr>
                                            <td><span class="fw-bold">{{ $sm->nomor_surat }}</span></td>
                                            <td>{{ $sm->pengirim }}</td>
                                            <td>{{ $sm->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="keluar" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No. Surat</th>
                                            <th>Tujuan/Perihal</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($suratKeluarTerbaru as $sk)
                                        <tr>
                                            <td><span class="fw-bold">{{ $sk->nomor_surat }}</span></td>
                                            <td>{{ $sk->perihal }}</td>
                                            <td>{{ $sk->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent border-bottom mb-3">
                    <h5 class="mb-0">Jobdesk Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="vertical-timeline">
                        @forelse($LaporanJobdeskTerbaru as $job)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 me-3">
                                <span class="badge bg-label-primary p-2"><i class="ri-task-line"></i></span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">{{ $job->divisi }}</h6>
                                <small class="text-muted text-truncate d-block">{{ $job->nama }}</small>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-muted">Belum ada laporan jobdesk.</p>
                        @endforelse
                    </div>
                    <a href="#" class="btn btn-outline-primary btn-sm w-100 mt-2">Lihat Semua Laporan Jobdesk</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-label-primary { background-color: rgba(105, 108, 255, 0.16) !important; color: #696cff !important; }
        .bg-label-success { background-color: rgba(113, 221, 55, 0.16) !important; color: #71dd37 !important; }
        .bg-label-info { background-color: rgba(0, 208, 255, 0.16) !important; color: #00d0ff !important; }
        .bg-label-warning { background-color: rgba(255, 171, 0, 0.16) !important; color: #ffab00 !important; }
        .ri-2x { font-size: 1.5rem; }
        .ri-4x { font-size: 3rem; }
        .nav-tabs .nav-link.active { border-bottom: 2px solid #696cff; color: #696cff; background: transparent; }
    </style>
@endsection
