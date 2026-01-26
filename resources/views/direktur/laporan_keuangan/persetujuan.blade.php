@extends('layout.direktur-template')

@section('title', 'Persetujuan Laporan Keuangan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Alert Notifikasi untuk Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert Notifikasi untuk Error/Gagal --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">

            <div class="card shadow-lg border-0 rounded-3">
                {{-- Menambahkan kelas bg-primary agar header memiliki warna latar belakang --}}
                <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i> Persetujuan Laporan Keuangan</h5>
                    <a href="{{ url('direktur/keuangan-laporan') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h6 class="text-muted">Detail Transaksi</h6>
                        <hr>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Tanggal:</strong>
                                <span>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Pengguna:</strong>
                                <span>{{ $laporan->pengguna->nama ?? '-' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Keperluan:</strong>
                                <span>{{ $laporan->keperluan }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Jenis:</strong>
                                <span>
                                    @switch($laporan->jenis)
                                        @case('uang_masuk')
                                            <span class="badge bg-success">Uang Masuk</span>
                                            @break
                                        @case('pengeluaran')
                                            <span class="badge bg-danger">Pengeluaran</span>
                                            @break
                                        @default
                                            <span class="badge bg-warning text-dark">Kasbon</span>
                                    @endswitch
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Nominal:</strong>
                                {{-- Perlu dipastikan kolom 'tipe' ada di model laporan --}}
                                <span class="fw-bold {{ ($laporan->jenis === 'uang_masuk') ? 'text-success' : 'text-danger' }}">
                                    Rp{{ number_format($laporan->nominal, 0, ',', '.') }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Metode:</strong>
                                <span>{{ ucfirst($laporan->jenis_uang) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Bukti Transaksi:</strong>
                                <span>
                                    @if ($laporan->bukti_transaksi)
                                        {{-- Menggunakan asset('storage/') sesuai path storage yang umum --}}
                                        <a href="{{ asset('storage/' . $laporan->bukti_transaksi) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>

                    <form action="{{ url('direktur/keuangan-laporan/persetujuan/' . $laporan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Input Catatan -->
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan Direktur</label>
                            <textarea name="catatan" id="catatan" rows="3" class="form-control" placeholder="Tambahkan catatan persetujuan atau alasan penolakan...">{{ old('catatan', $laporan->catatan ?? '') }}</textarea>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" name="persetujuan" value="disetujui" class="btn btn-success w-50">
                                <i class="bi bi-check-circle me-1"></i> Setujui
                            </button>
                            <button type="submit" name="persetujuan" value="ditolak" class="btn btn-danger w-50">
                                <i class="bi bi-x-circle me-1"></i> Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

    </div>
</div>
@endsection
